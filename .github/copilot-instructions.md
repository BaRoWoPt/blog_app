Project Context for GitHub Copilot
Mục đích dự án

Đây là project training Laravel nội bộ, mục tiêu là học và thực hành để tự xây dựng được 1 ứng dụng CRUD hoàn chỉnh theo lộ trình training của công ty (Setup ENV → Git → HTML/CSS/JS → SQL → PHP → Laravel overview → Routing/Controller → View/Blade → Middleware → Database → Eloquent ORM → Validation → Collection/Helper → Session/Cookie → Authentication → Mail → Command/Scheduling → Queue → ...).

Người code là người đang học Laravel — ưu tiên code rõ ràng, dễ hiểu, đúng convention chuẩn của framework hơn là tối ưu/thủ thuật phức tạp.

Stack
Laravel (PHP), Blade templates (không dùng React/Vue cho phần view)
MySQL, chạy qua Docker
Quản lý DB bằng TablePlus
Coding convention
Chưa có pint.json hoặc .editorconfig riêng trong project → dùng convention mặc định của Laravel, tương đương chuẩn PSR-12(4-space indent, PascalCase cho class, camelCase cho method/biến, của method xuống dòng riêng)
Đặt tên bảng database theo số nhiều (ví dụ: users, orders, products)
Model tương ứng viết số ít (ví dụ: bảng users → Model User)
Controller dùng resource methods chuẩn REST (index, create, store, show, edit, update, destroy)
Validation nên tách ra Form Request class thay vì validate inline trong Controller
Cách hỗ trợ khi trả lời/code
Khi sinh code, ưu tiên cách làm "chuẩn Laravel" (theo docs chính thức) để đúng mục tiêu học, tránh dùng package ngoài không cần thiết trừ khi được yêu cầu
Khi giải thích, giải thích ngắn gọn tại sao code viết như vậy, không chỉ đưa code
Testing (PHPUnit) chưa được dùng trong project — nếu gợi ý thêm test, giải thích đơn giản, từng bước, vì đây là phần đang bắt đầu học
Lưu ý về ngữ cảnh khi làm việc liên tục
Mỗi khi bắt đầu yêu cầu mới trong cùng phiên làm việc, nêu rõ trạng thái hiện tại (phần nào đã xong, đang làm phần nào) để tránh lặp lại việc đã hoàn thành trước đó
Không tự ý sửa lại các phần code đã hoàn thành và đã test OK trừ khi được yêu cầu rõ
