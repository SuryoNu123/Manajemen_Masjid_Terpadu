import { expect, test } from '@playwright/test';
import { resolve } from 'node:path';

const viewports = [
    { name: 'wide-desktop', width: 1920, height: 1080 },
    { name: 'desktop', width: 1440, height: 900 },
    { name: 'laptop', width: 1366, height: 768 },
    { name: 'tablet', width: 768, height: 1024 },
    { name: 'mobile', width: 390, height: 844 },
    { name: 'mobile-375', width: 375, height: 812 },
    { name: 'small-mobile', width: 360, height: 800 },
];

const publicPages = [
    { name: 'home', path: '/' },
    { name: 'profile', path: '/profil' },
    { name: 'prayer-schedules', path: '/jadwal-sholat-harian' },
    { name: 'activities', path: '/kegiatan' },
    { name: 'announcements', path: '/pengumuman' },
    { name: 'articles', path: '/artikel' },
    { name: 'library', path: '/perpustakaan' },
    { name: 'donations', path: '/donasi' },
    { name: 'zakat', path: '/zakat' },
    { name: 'transparency', path: '/transparansi' },
    { name: 'digital-signage', path: '/digital-signage' },
];

const dashboardUsers = [
    { role: 'ketua', email: 'ketua@masjid.test' },
    { role: 'bendahara', email: 'bendahara@masjid.test' },
    { role: 'sekretaris', email: 'sekretaris@masjid.test' },
    { role: 'marbot', email: 'marbot@masjid.test' },
    { role: 'jamaah', email: 'jamaah@masjid.test' },
];

const rolePages = [
    {
        role: 'ketua',
        email: 'ketua@masjid.test',
        paths: [
            '/profile', '/transaksi-keuangan', '/laporan-keuangan', '/business-units', '/business-units/create',
            '/business-unit-sales', '/business-unit-sales/create', '/business-unit-sales/report', '/business-unit-items',
            '/business-unit-items/create', '/jamaahs', '/jamaahs/create', '/jadwal-sholat', '/jadwal-sholat/create',
            '/presensi', '/laporan-presensi', '/activities', '/activities/create', '/officers', '/officers/create',
            '/youth-members', '/youth-members/create', '/youth-programs', '/youth-programs/create', '/santris',
            '/santris/create', '/tpq-classes', '/tpq-classes/create', '/santri-attendances', '/santri-attendances/report',
            '/assets', '/assets/create', '/asset-maintenances', '/asset-maintenances/create', '/library-books',
            '/library-books/create', '/book-loans', '/book-loans/create', '/book-loans/report', '/book-loan-requests',
            '/announcements', '/announcements/create', '/dakwah-contents', '/dakwah-contents/create',
            '/admin/pengguna', '/admin/pengguna/create', '/activity-logs',
        ],
    },
    {
        role: 'bendahara',
        email: 'bendahara@masjid.test',
        paths: [
            '/profile', '/transaksi-keuangan', '/transaksi-keuangan/create', '/laporan-keuangan',
            '/laporan-keuangan/create', '/kelola-donasi', '/mustahik', '/mustahik/create', '/business-unit-sales',
            '/business-unit-sales/create', '/business-unit-sales/report', '/business-unit-items', '/business-unit-items/create',
        ],
    },
    {
        role: 'sekretaris',
        email: 'sekretaris@masjid.test',
        paths: [
            '/profile', '/jamaahs', '/jamaahs/create', '/officers', '/officers/create', '/activities', '/activities/create',
            '/announcements', '/announcements/create', '/dakwah-contents', '/dakwah-contents/create', '/youth-members',
            '/youth-members/create', '/youth-programs', '/youth-programs/create', '/santris', '/santris/create',
            '/tpq-classes', '/tpq-classes/create', '/santri-attendances', '/santri-attendances/report', '/library-books',
            '/library-books/create', '/book-loans', '/book-loans/create', '/book-loans/report', '/book-loan-requests',
        ],
    },
    {
        role: 'marbot',
        email: 'marbot@masjid.test',
        paths: [
            '/profile', '/jadwal-sholat', '/jadwal-sholat/create', '/presensi', '/laporan-presensi', '/assets',
            '/assets/create', '/asset-maintenances', '/asset-maintenances/create', '/santri-attendances',
            '/santri-attendances/report',
        ],
    },
    {
        role: 'jamaah',
        email: 'jamaah@masjid.test',
        paths: ['/profile', '/akun/peminjaman', '/akun/pengaturan-notifikasi'],
    },
];

async function checkLayout(page) {
    const dimensions = await page.evaluate(() => ({
        viewportWidth: document.documentElement.clientWidth,
        documentWidth: document.documentElement.scrollWidth,
    }));

    expect(
        dimensions.documentWidth,
        `Unexpected horizontal overflow: ${dimensions.documentWidth}px document in ${dimensions.viewportWidth}px viewport`,
    ).toBeLessThanOrEqual(dimensions.viewportWidth + 1);

    const clippedControls = await page.locator('input, select, textarea, button, table').evaluateAll((elements) => {
        return elements.flatMap((element) => {
            const bounds = element.getBoundingClientRect();

            if (bounds.width === 0 || bounds.height === 0) {
                return [];
            }

            const insideScrollableRegion = element.closest('.overflow-x-auto, [data-scroll-container]');
            if (insideScrollableRegion) {
                return [];
            }

            return bounds.left < -1 || bounds.right > window.innerWidth + 1
                ? [element.tagName.toLowerCase() + (element.id ? `#${element.id}` : '')]
                : [];
        });
    });

    expect(clippedControls, 'Interactive controls or tables are clipped by the viewport').toEqual([]);
}

async function capturePage(page, viewportName, name) {
    await page.screenshot({
        path: resolve('artifacts/ui-audit', viewportName, `${name}.png`),
        fullPage: true,
    });
}

for (const viewport of viewports) {
    test.describe(`${viewport.name} ${viewport.width}x${viewport.height}`, () => {
        test.use({ viewport: { width: viewport.width, height: viewport.height } });

        test('login and Jamaah registration screens render responsively and link to each other', async ({ page }) => {
            const browserErrors = [];
            page.on('pageerror', (error) => browserErrors.push(error.message));
            page.on('console', (message) => {
                if (message.type() === 'error') {
                    browserErrors.push(message.text());
                }
            });

            await page.goto('/login');
            await expect(page.getByRole('button', { name: 'Masuk' })).toBeVisible();
            await capturePage(page, viewport.name, 'auth-login');
            await checkLayout(page);

            await page.getByRole('link', { name: 'Daftar sekarang' }).click();
            await expect(page).toHaveURL(/\/register$/);
            await expect(page.getByRole('button', { name: 'Daftar' })).toBeVisible();
            await expect(page.locator('input[name="role_id"]')).toHaveCount(0);
            await capturePage(page, viewport.name, 'auth-register');
            await checkLayout(page);

            await page.getByRole('link', { name: 'Masuk', exact: true }).click();
            await expect(page).toHaveURL(/\/login$/);
            expect(browserErrors).toEqual([]);
        });

        test('a new Jamaah can register, log out, and log in again', async ({ page }) => {
            const email = `registration-${viewport.name}@masjid.test`;
            const password = 'SecurePassword123!';

            await page.goto('/register');
            await page.getByLabel('Nama Lengkap').fill('Jamaah Browser');
            await page.getByLabel('Email').fill(email);
            await page.getByLabel('Password', { exact: true }).fill(password);
            await page.getByLabel('Konfirmasi Password').fill(password);
            await page.getByRole('button', { name: 'Daftar' }).click();

            await expect(page).toHaveURL(/\/dashboard$/);
            await expect(page.getByRole('heading', { name: 'Ahlan wa Sahlan, Jamaah Browser' })).toBeVisible();
            await checkLayout(page);
            await capturePage(page, viewport.name, 'auth-registration-dashboard');

            await page.getByRole('button', { name: 'Logout' }).click();
            await expect(page).toHaveURL(/\/$/);
            await page.goto('/login');
            await page.getByLabel('Email').fill(email.toUpperCase());
            await page.getByLabel('Password').fill(password);
            await page.getByRole('button', { name: 'Masuk' }).click();

            await expect(page).toHaveURL(/\/dashboard$/);
            await expect(page.getByRole('heading', { name: 'Ahlan wa Sahlan, Jamaah Browser' })).toBeVisible();
        });

        test('public pages render without layout or JavaScript errors', async ({ page }) => {
            const browserErrors = [];
            page.on('pageerror', (error) => browserErrors.push(error.message));
            page.on('console', (message) => {
                if (message.type() === 'error') {
                    browserErrors.push(message.text());
                }
            });

            for (const publicPage of publicPages) {
                const response = await page.goto(publicPage.path);

                expect(response?.status(), `${publicPage.path} should respond successfully`).toBeLessThan(400);
                await expect(page.locator('body')).not.toBeEmpty();
                if (['/donasi', '/zakat'].includes(publicPage.path)) {
                    await expect(page.locator('form:visible').first(), `${publicPage.path} should show its public form`).toBeVisible();
                }
                await capturePage(page, viewport.name, publicPage.name);
                await checkLayout(page);

                if (publicPage.path === '/transparansi' && viewport.width < 640) {
                    const table = page.locator('.overflow-x-auto table').first();
                    const scrollContainer = table.locator('xpath=..');
                    const dimensions = await scrollContainer.evaluate((element) => ({
                        clientWidth: element.clientWidth,
                        scrollWidth: element.scrollWidth,
                    }));

                    expect(dimensions.scrollWidth).toBeGreaterThan(dimensions.clientWidth);
                    await scrollContainer.evaluate((element) => {
                        element.scrollLeft = element.scrollWidth;
                    });
                    const statusHeaderIsVisible = await scrollContainer.evaluate((element) => {
                        const statusHeader = element.querySelector('table thead th:last-child');
                        const containerBounds = element.getBoundingClientRect();
                        const statusBounds = statusHeader?.getBoundingClientRect();

                        return statusBounds !== undefined
                            && statusBounds.left >= containerBounds.left - 1
                            && statusBounds.right <= containerBounds.right + 1;
                    });

                    expect(statusHeaderIsVisible).toBe(true);
                }

                if (publicPage.path === '/') {
                    const menuButton = page.getByRole('button', { name: 'Buka atau tutup menu' });
                    if (viewport.width < 1024) {
                        await menuButton.click();
                        await expect(page.getByRole('navigation', { name: 'Navigasi utama' })).toBeVisible();
                        await expect(page.getByRole('navigation', { name: 'Navigasi utama' })).toBeInViewport();
                        await checkLayout(page);
                        await capturePage(page, viewport.name, 'home-navigation-open');
                    } else {
                        await expect(menuButton).toBeHidden();
                    }
                }
            }

            expect(browserErrors).toEqual([]);
        });

        test('administrative search and filter forms work without overflowing', async ({ page }) => {
            test.setTimeout(90_000);

            await page.goto('/login');
            await page.getByLabel('Email').fill('ketua@masjid.test');
            await page.getByLabel('Password').fill('password123');
            await page.getByRole('button', { name: 'Masuk' }).click();
            await expect(page).toHaveURL(/\/dashboard$/);

            const leadershipFilters = [
                { path: '/activities', search: 'Kajian', filter: 'status', value: 'scheduled' },
                { path: '/officers', search: 'Imam', filter: 'position', value: 'imam' },
                { path: '/laporan-keuangan', search: null, filter: 'year', value: '2026' },
            ];

            for (const screen of leadershipFilters) {
                await page.goto(screen.path);
                const filterForm = page.locator('form[method="GET"]').first();
                await expect(filterForm).toBeVisible();
                if (screen.search) {
                    await filterForm.locator('input[name="search"]').fill(screen.search);
                }
                if (screen.filter === 'year') {
                    await filterForm.locator(`[name="${screen.filter}"]`).fill(screen.value);
                } else {
                    await filterForm.locator(`[name="${screen.filter}"]`).selectOption(screen.value);
                }
                await filterForm.getByRole('button', { name: /Terapkan/ }).click();
                await expect(page).toHaveURL(new RegExp(`${screen.filter}=${screen.value}`));
                await checkLayout(page);
                await page.getByRole('link', { name: 'Reset' }).click();
                await expect(page).toHaveURL(new RegExp(`${screen.path}$`));
            }

            await page.getByRole('button', { name: 'Logout' }).click();
            await page.goto('/login');
            await page.getByLabel('Email').fill('bendahara@masjid.test');
            await page.getByLabel('Password').fill('password123');
            await page.getByRole('button', { name: 'Masuk' }).click();
            await expect(page).toHaveURL(/\/dashboard$/);

            await page.goto('/mustahik');
            const mustahikFilters = page.locator('form[method="GET"]').first();
            await mustahikFilters.locator('input[name="search"]').fill('Ahmad');
            await mustahikFilters.locator('[name="asnaf"]').selectOption('fakir');
            await mustahikFilters.locator('[name="status"]').selectOption('pending');
            await mustahikFilters.getByRole('button', { name: 'Terapkan filter' }).click();
            await expect(page).toHaveURL(/search=Ahmad.*asnaf=fakir.*status=pending/);
            await checkLayout(page);
            await page.getByRole('link', { name: 'Reset' }).click();
            await expect(page).toHaveURL(/\/mustahik$/);

            await page.goto('/kelola-donasi');
            const donationFilters = page.locator('form[method="GET"]').first();
            await donationFilters.locator('input[name="search"]').fill('DON-TEST');
            await donationFilters.locator('[name="type"]').selectOption('infaq');
            await donationFilters.locator('[name="payment_status"]').selectOption('pending');
            await donationFilters.locator('[name="payment_method"]').selectOption('qris');
            await donationFilters.getByRole('button', { name: 'Filter' }).click();
            await expect(page).toHaveURL(/search=DON-TEST.*type=infaq.*payment_status=pending.*payment_method=qris/);
            await checkLayout(page);
            await page.getByRole('link', { name: 'Reset' }).click();
            await expect(page).toHaveURL(/\/kelola-donasi$/);

            await page.goto('/transaksi-keuangan');
            const transactionFilters = page.locator('form[method="GET"]').first();
            await transactionFilters.locator('input[name="search"]').fill('Infaq');
            await transactionFilters.locator('[name="type"]').selectOption('income');
            await transactionFilters.getByRole('button', { name: 'Filter' }).click();
            await expect(page).toHaveURL(/search=Infaq.*type=income/);
            await checkLayout(page);
            await page.getByRole('link', { name: 'Reset' }).click();
            await expect(page).toHaveURL(/\/transaksi-keuangan$/);
        });

        for (const user of dashboardUsers) {
            test(`${user.role} dashboard renders through normal login`, async ({ page }) => {
                const browserErrors = [];
                page.on('pageerror', (error) => browserErrors.push(error.message));
                page.on('console', (message) => {
                    if (message.type() === 'error') {
                        browserErrors.push(message.text());
                    }
                });
                page.on('requestfailed', (request) => {
                    browserErrors.push(`${request.url()}: ${request.failure()?.errorText}`);
                });

                await page.goto('/login');
                await page.getByLabel('Email').fill(user.email);
                await page.getByLabel('Password').fill('password123');
                await page.getByRole('button', { name: 'Masuk' }).click();
                await expect(page).toHaveURL(/\/dashboard$/);
                await expect(page.locator('#dashboard-sidebar')).toBeVisible();
                if (viewport.width < 1024) {
                    await expect(page.locator('#dashboard-sidebar')).not.toBeInViewport();
                }
                if (user.role === 'ketua' && viewport.width < 640) {
                    const transactionCard = page.getByRole('heading', { name: 'Transaksi Kas Terakhir' }).locator('xpath=../..');
                    const scrollContainer = transactionCard.locator('.overflow-x-auto');
                    const dimensions = await scrollContainer.evaluate((element) => ({
                        clientWidth: element.clientWidth,
                        scrollWidth: element.scrollWidth,
                    }));

                    expect(dimensions.scrollWidth).toBeGreaterThan(dimensions.clientWidth);
                    const rightmostCellIsVisible = await scrollContainer.evaluate((element) => {
                        element.scrollLeft = element.scrollWidth;
                        const rightmostCell = element.querySelector('tbody tr:first-child td:last-child');
                        const containerBounds = element.getBoundingClientRect();
                        const cellBounds = rightmostCell?.getBoundingClientRect();

                        return cellBounds !== undefined
                            && cellBounds.left >= containerBounds.left - 1
                            && cellBounds.right <= containerBounds.right + 1;
                    });

                    expect(rightmostCellIsVisible).toBe(true);
                    await scrollContainer.evaluate((element) => {
                        element.scrollLeft = 0;
                    });
                }
                await capturePage(page, viewport.name, `${user.role}-dashboard`);
                await checkLayout(page);

                if (viewport.width < 1024) {
                    await page.getByRole('button', { name: 'Buka atau tutup menu navigasi' }).click();
                    await expect(page.locator('#dashboard-sidebar')).toBeVisible();
                    await expect(page.locator('#dashboard-sidebar')).toBeInViewport();
                    await checkLayout(page);
                    await capturePage(page, viewport.name, `${user.role}-dashboard-sidebar-open`);
                }

                if (user.role === 'ketua') {
                    const financialGroup = page.locator('#dashboard-sidebar details').filter({ hasText: 'Keuangan & Bisnis' });

                    await expect(financialGroup.locator('summary')).toBeVisible();
                    await expect(financialGroup.locator('a').first()).toBeHidden();
                    await financialGroup.locator('summary').click();
                    await expect(financialGroup.getByRole('link', { name: 'Transaksi Keuangan' })).toBeVisible();

                    await page.goto('/transaksi-keuangan');
                    await expect(page.locator('#dashboard-sidebar details[open]').filter({ hasText: 'Keuangan & Bisnis' }))
                        .toHaveAttribute('open', '');
                    await checkLayout(page);
                }

                expect(browserErrors).toEqual([]);
            });
        }

        test('administration, notifications, and Jamaah account screens render responsively', async ({ page }) => {
            test.setTimeout(90000);

            await page.goto('/login');
            await page.getByLabel('Email').fill('ketua@masjid.test');
            await page.getByLabel('Password').fill('password123');
            await page.getByRole('button', { name: 'Masuk' }).click();
            await expect(page).toHaveURL(/\/dashboard$/);

            for (const screen of [
                { name: 'user-administration', path: '/admin/pengguna', heading: 'Akun pengguna' },
                { name: 'book-loan-requests', path: '/book-loan-requests', heading: 'Permintaan peminjaman dari Jamaah' },
                { name: 'notifications', path: '/notifikasi', heading: 'Notifikasi' },
            ]) {
                await page.goto(screen.path);
                await expect(page.getByRole('heading', { name: screen.heading }).first()).toBeVisible();
                await capturePage(page, viewport.name, screen.name);
                await checkLayout(page);
            }

            await page.goto('/book-loan-requests');
            const loanRequestFilters = page.locator('form[method="GET"]').first();
            await loanRequestFilters.locator('input[name="search"]').fill('Demo');
            await loanRequestFilters.locator('[name="status"]').selectOption('pending');
            await loanRequestFilters.getByRole('button', { name: 'Terapkan filter' }).click();
            await expect(page).toHaveURL(/search=Demo.*status=pending/);
            await checkLayout(page);
            await page.getByRole('link', { name: 'Reset' }).click();

            await page.goto('/notifikasi');
            const notificationFilters = page.locator('form[method="GET"]').first();
            await notificationFilters.locator('input[name="search"]').fill('Kajian');
            await notificationFilters.locator('[name="read"]').selectOption('unread');
            await notificationFilters.getByRole('button', { name: 'Terapkan' }).click();
            await expect(page).toHaveURL(/search=Kajian.*read=unread/);
            await checkLayout(page);
            await page.getByRole('link', { name: 'Reset' }).click();

            await page.getByRole('button', { name: 'Logout' }).click();
            await page.goto('/login');
            await page.getByLabel('Email').fill('jamaah@masjid.test');
            await page.getByLabel('Password').fill('password123');
            await page.getByRole('button', { name: 'Masuk' }).click();
            await expect(page).toHaveURL(/\/dashboard$/);

            for (const screen of [
                { name: 'jamaah-book-loans', path: '/akun/peminjaman', heading: 'Peminjaman Saya' },
                { name: 'jamaah-notification-preferences', path: '/akun/pengaturan-notifikasi', heading: 'Preferensi Notifikasi' },
            ]) {
                await page.goto(screen.path);
                await expect(page.getByRole('heading', { name: screen.heading }).first()).toBeVisible();
                await capturePage(page, viewport.name, screen.name);
                await checkLayout(page);
            }
        });

        test('profile form and account confirmation modal work without submitting deletion', async ({ page }) => {
            const browserErrors = [];
            page.on('pageerror', (error) => browserErrors.push(error.message));
            page.on('console', (message) => {
                if (message.type() === 'error') {
                    browserErrors.push(message.text());
                }
            });

            await page.goto('/login');
            await page.getByLabel('Email').fill('ketua@masjid.test');
            await page.getByLabel('Password').fill('password123');
            await page.getByRole('button', { name: 'Masuk' }).click();
            await expect(page).toHaveURL(/\/dashboard$/);

            await page.goto('/profile');
            await expect(page.locator('form:visible').first()).toBeVisible();
            await capturePage(page, viewport.name, 'profile-settings');
            await checkLayout(page);

            await page.getByRole('button', { name: 'Delete Account' }).first().click();
            const confirmationModal = page.locator('div.fixed.inset-0.overflow-y-auto').filter({
                hasText: 'Are you sure you want to delete your account?',
            });
            await expect(confirmationModal).toBeVisible();
            await checkLayout(page);
            await capturePage(page, viewport.name, 'profile-delete-confirmation-modal');
            await page.getByRole('button', { name: 'Cancel' }).click();
            await expect(confirmationModal).toBeHidden();

            expect(browserErrors).toEqual([]);
        });

        test('role module pages render without clipping or horizontal overflow', async ({ page }) => {
            test.setTimeout(180_000);
            test.skip(!['wide-desktop', 'laptop', 'tablet', 'mobile-375'].includes(viewport.name));

            for (const rolePage of rolePages) {
                await page.goto('/login');
                await page.getByLabel('Email').fill(rolePage.email);
                await page.getByLabel('Password').fill('password123');
                await page.getByRole('button', { name: 'Masuk' }).click();
                await expect(page).toHaveURL(/\/dashboard$/);

                for (const path of rolePage.paths) {
                    const response = await page.goto(path);

                    expect(response?.status(), `${rolePage.role} ${path} should respond successfully`).toBeLessThan(400);
                    await checkLayout(page);
                }

                await page.context().clearCookies();
            }
        });
    });
}
