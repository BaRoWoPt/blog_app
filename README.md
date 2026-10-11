# 📝 Blog App - Laravel 11 Management & Authorization System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Playwright](https://img.shields.io/badge/Playwright-E2E-2EAD33?style=for-the-badge&logo=playwright&logoColor=white)](https://playwright.dev)

Một ứng dụng web quản lý bài viết (Blog), bình luận và phân quyền người dùng được xây dựng hoàn chỉnh trên nền tảng **Laravel 11** và **PHP 8.2+**. Dự án áp dụng kiến trúc chuẩn mực: **Service Layer**, **Form Request Validation**, **Policy/Gate Authorization**, **PHP 8 Backed Enums**, **Database Transactions**, **Background Queue & Scheduled Jobs**, cùng hệ thống kiểm thử tự động (**Unit, Feature & E2E Testing**).

---

## 🚀 Tính năng nổi bật (Key Features)

### 1. 🔐 Xác thực & Bảo mật (Authentication & Security)
- **Đăng ký & Đăng nhập:**
  - Quy tắc mật khẩu mạnh (`Password::min(8)->mixedCase()->numbers()->symbols()`).
  - Mật khẩu được băm một chiều bằng **Bcrypt** an toàn qua Eloquent Model Casts (`'password' => 'hashed'`).
  - Ghi nhớ đăng nhập (`Remember Me`) bảo mật bằng cookie token.
- **Quên mật khẩu & Khôi phục:** Gửi link đặt lại mật khẩu bảo mật qua email token.
- **Bảo mật phiên làm việc (Session Security):** Tự động đổi mã phiên (`session()->regenerate()`) sau khi xác thực, phòng chống tấn công **Session Fixation**.
- **Chống DoS / Brute-force:** Tích hợp **Rate Limiting** (`RateLimiter`) giới hạn tần suất request theo IP/User:
  - Giới hạn tạo bài viết (5 bài / phút).
  - Giới hạn bình luận (10 bình luận / phút).
  - Giới hạn tác vụ nhạy cảm: Đăng ký, Quên mật khẩu (5 lần / phút).
- **Chống XSS & CSRF:** Tự động bảo vệ mọi form bằng CSRF Token; làm sạch dữ liệu bình luận chống Stored XSS bằng `{!! nl2br(e($comment->content)) !!}`.

### 2. 🛡️ Phân quyền đa tầng (Role-Based Authorization & Policies)
- Quản lý vai trò chuẩn xác bằng **PHP 8 Backed Enum**: `UserRole::USER` và `UserRole::ADMIN`.
- **Phân quyền người dùng thường (`User`):**
  - Quản lý bài viết cá nhân tại mục **"Bài viết của tôi"** (`my-blogs`).
  - Chỉ được chỉnh sửa hoặc xóa bài viết do chính mình tạo ra (phòng chống lỗ hổng **IDOR**).
  - Đăng bình luận và xóa bình luận của chính mình.
  - Có quyền xóa bình luận của người khác **nếu bình luận đó nằm trên bài viết của mình**.
- **Phân quyền Quản trị viên (`Admin`):**
  - Quản trị toàn bộ bài viết trong hệ thống (`/admin/blogs`).
  - Xóa bài viết vi phạm kèm theo **lý do xóa**, hệ thống tự động gửi email thông báo cho tác giả.
  - Quản lý Danh mục (Categories) và Thẻ tag (Tags).
- **Bảo vệ đa tầng:**
  - *Route Middleware:* `auth`, `admin`, `guest`.
  - *Controller Policy:* `$this->authorize('update', $blog)`, `$this->authorize('delete', $comment)`.
  - *Blade Directives:* `@can('update', $blog)`, `@can('delete', $comment)`.

### 3. 📰 Quản lý Bài viết & Danh mục (Blog & Content Management)
- CRUD bài viết chuẩn RESTful với kiến trúc **Service Layer** ([`BlogService`](Begin_Laravel/app/Services/BlogService.php)).
- Upload ảnh thumbnail bài viết; tự động **thu hồi và xóa file rác (Orphan Files)** nếu Database Transaction gặp lỗi.
- Xóa ảnh thumbnail cũ an toàn chỉ sau khi cập nhật bài viết thành công.
- Quan hệ dữ liệu:
  - **1 - N:** `Category` ➔ `Blogs`.
  - **N - N:** `Blog` 🔄 `Tag` (thông qua bảng trung gian `blog_tag`, hỗ trợ Composite Primary Key và `cascadeOnDelete`).
- Lọc bài viết theo Danh mục với URL thân thiện SEO (`?category=slug`).
- Phân trang (Pagination) thông minh, giữ nguyên bộ lọc tìm kiếm (`withQueryString()`).
- Tối ưu hiệu năng, triệt tiêu lỗi **N+1 Query** bằng Eager Loading (`with(['category', 'tags'])`).

### 4. ⚡ Hàng đợi & Tác vụ tự động (Queues, Jobs & Scheduling)
- Gửi email chào mừng và email thông báo bất đồng bộ qua **Queue** (`Mail::to()->queue()`), giúp trang web phản hồi tức thì không bị giật lag.
- **Scheduled Job (`SendReminderEmailJob`):** Tự động quét người dùng và gửi email nhắc nhở định kỳ lúc **08:00 sáng mỗi ngày**.
- Xử lý dữ liệu lớn an toàn bộ nhớ bằng con trỏ chỉ mục **`chunkById(100)`**, ngăn ngừa tràn RAM (Memory Exhaustion).

### 5. 🧪 Kiểm thử tự động (Testing)
- **Feature Tests:** Kiểm thử phân quyền và chống tấn công đổi ID bài viết ([`UserBlogAuthorizationTest`](Begin_Laravel/tests/Feature/UserBlogAuthorizationTest.php)).
- **Unit Tests:** Kiểm thử logic phân quyền `BlogPolicy` ([`BlogPolicyTest`](Begin_Laravel/tests/Unit/BlogPolicyTest.php)).
- **E2E Tests:** Kiểm thử tự động giao diện Đăng ký / Đăng nhập với **Playwright** ([`auth.spec.ts`](Begin_Laravel/e2e/auth.spec.ts)).

---

## 🛠️ Công nghệ sử dụng (Tech Stack)

| Thành phần | Công nghệ / Thư viện |
| :--- | :--- |
| **Backend Framework** | Laravel 11.x |
| **Ngôn ngữ** | PHP 8.2+ |
| **Cơ sở dữ liệu** | MySQL 8.0 (Docker) |
| **Frontend / Template** | Blade Template, CSS3, Vite |
| **Queue & Cache Driver** | Database / Redis |
| **Testing** | PHPUnit (Feature & Unit Test), Playwright (E2E Test) |

---

## 📂 Cấu trúc thư mục tiêu biểu

```text
Begin_Laravel/
├── app/
│   ├── Enums/
│   │   └── UserRole.php              # PHP 8 Enum định nghĩa quyền (user, admin)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                # Controller dành riêng cho khu vực quản trị
│   │   │   ├── User/                 # Controller quản lý bài viết cá nhân
│   │   │   └── AuthController.php    # Xử lý Đăng ký, Đăng nhập, Đăng xuất
│   │   └── Requests/                 # Form Request validate dữ liệu form
│   ├── Jobs/
│   │   └── SendReminderEmailJob.php  # Scheduled Job gửi email theo chunk
│   ├── Models/                       # Eloquent Models (User, Blog, Category, Tag, Comment)
│   ├── Notifications/                # Mail Notifications
│   ├── Policies/                     # BlogPolicy, CommentPolicy phân quyền
│   └── Services/
│       └── BlogService.php           # Business Logic xử lý Transaction & File Upload
├── database/
│   ├── migrations/                   # Quản lý cấu trúc bảng và khóa ngoại
│   └── seeders/                      # Dữ liệu mẫu (Category, Tag, Blog, User)
├── e2e/                              # Kịch bản kiểm thử giao diện Playwright
├── resources/views/                  # Giao diện Blade Templates
├── routes/
│   ├── web.php                       # Định tuyến Web & Rate Limiting
│   └── console.php                   # Định lịch chạy Schedule cronjob
└── tests/                            # PHPUnit Feature & Unit Tests
```

---

## ⚙️ Hướng dẫn cài đặt & Chạy dự án (Installation)

### 1. Yêu cầu môi trường
- PHP >= 8.2 (kèm các extension: `pdo_mysql`, `mbstring`, `openssl`, `bcmath`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL >= 8.0 (hoặc Docker)

### 2. Các bước cài đặt

**Bước 1: Clone mã nguồn về máy**
```bash
git clone https://github.com/BaRoWoPt/blog_app.git
cd blog_app/Begin_Laravel
```

**Bước 2: Cài đặt các gói phụ thuộc (Dependencies)**
```bash
composer install
npm install
```

**Bước 3: Thiết lập file môi trường `.env`**
```bash
cp .env.example .env
php artisan key:generate
```

Cấu hình thông tin Database và Mail trong file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_app
DB_USERNAME=root
DB_PASSWORD=your_password

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Bước 4: Chạy Migration và Seed dữ liệu mẫu**
```bash
php artisan migrate --seed
php artisan storage:link
```

**Bước 5: Khởi chạy ứng dụng**

Mở 3 terminal riêng biệt để chạy các tiến trình:

1. **Chạy Laravel Web Server:**
   ```bash
   php artisan serve
   ```
2. **Chạy Vite (Frontend Asset Compiler):**
   ```bash
   npm run dev
   ```
3. **Chạy Queue Worker (Xử lý email & background tasks):**
   ```bash
   php artisan queue:work
   ```

Ứng dụng sẽ hoạt động tại: `http://127.0.0.1:8000`

---

## 🧪 Chạy Kiểm thử (Run Tests)

* **Chạy Unit & Feature Tests với PHPUnit:**
  ```bash
  php artisan test
  ```

* **Chạy E2E Tests với Playwright:**
  ```bash
  npx playwright test
  ```

---

## 👤 Tác giả (Author)

* **Đào Gia Bảo** ([@BaRoWoPt](https://github.com/BaRoWoPt))
* Email: `dgbao.cntt@gmail.com`
