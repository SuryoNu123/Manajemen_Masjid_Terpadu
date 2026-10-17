import { spawn } from 'node:child_process';
import { mkdtemp, rm, writeFile } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const temporaryDirectory = await mkdtemp(join(tmpdir(), 'masjid-terpadu-playwright-'));
const databasePath = join(temporaryDirectory, 'playwright.sqlite');
const baseURL = process.env.PLAYWRIGHT_BASE_URL ?? 'http://127.0.0.1:4173';
const php = process.env.PHP_BINARY ?? 'php';
const environment = {
    ...process.env,
    APP_ENV: 'testing',
    APP_DEBUG: 'true',
    APP_URL: baseURL,
    DB_CONNECTION: 'sqlite',
    DB_DATABASE: databasePath,
    DB_URL: '',
    SESSION_DRIVER: 'cookie',
    CACHE_STORE: 'array',
    QUEUE_CONNECTION: 'sync',
    MAIL_MAILER: 'array',
    LOG_CHANNEL: 'stderr',
};

let server;
let browserTests;

await writeFile(databasePath, '');

function run(command, argumentsList) {
    return new Promise((resolveRun, rejectRun) => {
        const child = spawn(command, argumentsList, {
            cwd: projectRoot,
            env: environment,
            stdio: 'inherit',
            windowsHide: true,
        });

        child.once('error', rejectRun);
        child.once('exit', (code) => {
            if (code === 0) {
                resolveRun();
                return;
            }

            rejectRun(new Error(`${command} exited with code ${code}`));
        });
    });
}

async function terminateProcessTree(child, includeChildren = false) {
    if (!child || child.exitCode !== null) {
        return;
    }

    const exited = new Promise((resolveExit) => child.once('exit', resolveExit));

    if (process.platform === 'win32' && includeChildren) {
        await new Promise((resolveKill) => {
            const taskkill = spawn('taskkill', ['/PID', String(child.pid), '/T', '/F'], {
                stdio: 'ignore',
                windowsHide: true,
            });
            taskkill.once('close', resolveKill);
            taskkill.once('error', resolveKill);
        });
    } else {
        child.kill('SIGTERM');
    }

    await Promise.race([
        exited,
        new Promise((resolveTimeout) => setTimeout(resolveTimeout, 5_000)),
    ]);
}

async function waitForServer() {
    const deadline = Date.now() + 30_000;

    while (Date.now() < deadline) {
        if (server.exitCode !== null) {
            throw new Error(`Laravel server exited with code ${server.exitCode}`);
        }

        try {
            const response = await fetch(`${baseURL}/up`);
            if (response.ok) {
                return;
            }
        } catch {
            // Retry while Laravel starts listening on the local port.
        }

        await new Promise((resolveDelay) => setTimeout(resolveDelay, 250));
    }

    throw new Error(`Laravel did not become ready at ${baseURL}/up`);
}

let exitCode = 1;

try {
    await run(php, ['artisan', 'migrate', '--force', '--no-interaction']);
    await run(php, ['artisan', 'db:seed', '--force', '--no-interaction']);

    const createJamaahUser = [
        'require "vendor/autoload.php";',
        '$app = require "bootstrap/app.php";',
        '$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();',
        'if (! app()->environment("testing") || config("database.default") !== "sqlite") { throw new RuntimeException("Playwright users may only be created in the isolated SQLite test environment."); }',
        '$role = App\\Models\\Role::where("name", "jamaah")->firstOrFail();',
        'App\\Models\\User::updateOrCreate(',
        '    ["email" => "jamaah@masjid.test"],',
        '    ["name" => "Jamaah Pengujian", "password" => Illuminate\\Support\\Facades\\Hash::make("password123"), "role_id" => $role->id]',
        ');',
    ].join(' ');

    await run(php, ['-r', createJamaahUser]);

    const routerScript = resolve(
        projectRoot,
        'vendor',
        'laravel',
        'framework',
        'src',
        'Illuminate',
        'Foundation',
        'resources',
        'server.php',
    );

    server = spawn(php, ['-S', '127.0.0.1:4173', routerScript], {
        cwd: resolve(projectRoot, 'public'),
        env: environment,
        stdio: 'inherit',
        windowsHide: true,
    });

    await waitForServer();

    const playwrightCli = resolve(projectRoot, 'node_modules', '@playwright', 'test', 'cli.js');
    browserTests = spawn(process.execPath, [playwrightCli, 'test', ...process.argv.slice(2)], {
        cwd: projectRoot,
        env: environment,
        stdio: ['ignore', 'pipe', 'pipe'],
        windowsHide: true,
    });

    let summaryExitCode;
    let summaryTimer;
    let summaryOutput = '';
    const captureOutput = (stream, output) => {
        process[stream].write(output);
        summaryOutput = (summaryOutput + output.toString()).slice(-2_000);
        const normalizedOutput = summaryOutput.replace(/\u001b\[[0-?]*[ -/]*[@-~]/g, '');

        if (summaryExitCode === undefined) {
            if (/^\s*\d+\s+failed\b/m.test(normalizedOutput)) {
                summaryExitCode = 1;
            } else if (/^\s*\d+\s+passed\b/m.test(normalizedOutput)) {
                summaryExitCode = 0;
            }
        }

        if (summaryExitCode !== undefined && !summaryTimer) {
            summaryTimer = setTimeout(() => void terminateProcessTree(browserTests), 5_000);
        }
    };

    browserTests.stdout.on('data', (output) => captureOutput('stdout', output));
    browserTests.stderr.on('data', (output) => captureOutput('stderr', output));

    exitCode = await new Promise((resolveExit, rejectExit) => {
        browserTests.once('error', rejectExit);
        browserTests.once('exit', (code) => {
            clearTimeout(summaryTimer);
            resolveExit(summaryExitCode ?? code ?? 1);
        });
    });
} catch (error) {
    process.stderr.write(`${error.stack ?? error}\n`);
} finally {
    await terminateProcessTree(browserTests, true);
    await terminateProcessTree(server);
    await rm(temporaryDirectory, { recursive: true, force: true });
}

process.exitCode = exitCode;
