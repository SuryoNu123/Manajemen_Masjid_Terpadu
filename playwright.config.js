import { defineConfig, devices } from '@playwright/test';

const baseURL = process.env.PLAYWRIGHT_BASE_URL ?? 'http://127.0.0.1:4173';

export default defineConfig({
    testDir: './tests/e2e',
    outputDir: './artifacts/ui-audit/playwright-results',
    fullyParallel: false,
    forbidOnly: Boolean(process.env.CI),
    retries: 0,
    workers: 1,
    reporter: [['list']],
    use: {
        baseURL,
        browserName: 'chromium',
        ...devices['Desktop Chrome'],
        trace: 'retain-on-failure',
        screenshot: 'off',
        video: 'off',
    },
});
