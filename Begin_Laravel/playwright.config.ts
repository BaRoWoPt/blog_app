import { defineConfig, devices } from '@playwright/test';
import process from 'node:process';

/**
 * See https://playwright.dev/docs/test-configuration.
 */
export default defineConfig({
  testDir: './e2e',
  /* Chạy test song song */
  fullyParallel: true,
  /* Báo lỗi trên CI nếu có test.only */
  forbidOnly: !!process.env.CI,
  /* Thử lại khi fail */
  retries: process.env.CI ? 2 : 0,
  /* Số worker */
  workers: process.env.CI ? 1 : undefined,
  /* Reporter: In kết quả ra terminal và tạo báo cáo HTML (không tự mở để tránh treo terminal) */
  reporter: [
    ['list'],
    ['html', { open: 'never' }]
  ],
  /* Cấu hình chung */
  use: {
    /* Base URL trỏ thẳng về server Laravel */
    baseURL: 'http://127.0.0.1:8000',

    /* Ghi lại trace khi bài test bị fail */
    trace: 'on-first-retry',

    /* Tự chụp ảnh khi có lỗi */
    screenshot: 'only-on-failure',
  },

  /* Cấu hình trình duyệt */
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],

  /* Tự động bật server Laravel nếu chưa bật */
  webServer: {
    command: 'php artisan serve --port=8000',
    url: 'http://127.0.0.1:8000',
    reuseExistingServer: true,
    timeout: 120 * 1000,
  },
});
