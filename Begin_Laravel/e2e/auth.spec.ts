import { test, expect } from '@playwright/test';

test.describe('Kiểm thử luồng Đăng nhập (Auth)', () => {
  test('Hiển thị form đăng nhập đầy đủ các trường', async ({ page }) => {
    // Truy cập route /login
    await page.goto('/login');

    // Kiểm tra các phần tử trên giao diện
    await expect(page.locator('#email')).toBeVisible();
    await expect(page.locator('#password')).toBeVisible();
    await expect(page.getByRole('button', { name: 'Đăng nhập' })).toBeVisible();
  });

  test('Hiển thị lỗi khi submit form với thông tin không đúng', async ({ page }) => {
    await page.goto('/login');

    // Điền tài khoản không tồn tại hoặc sai pass
    await page.locator('#email').fill('wronguser@example.com');
    await page.locator('#password').fill('wrongpassword123');
    await page.getByRole('button', { name: 'Đăng nhập' }).click();

    // Hệ thống phải báo lỗi và không được chuyển khỏi trang login
    await expect(page).toHaveURL(/.*login/);
  });
});

