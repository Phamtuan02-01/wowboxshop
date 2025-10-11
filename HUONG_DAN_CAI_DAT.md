# Hướng dẫn thiết lập và chạy WowBox Shop

## Bước 1: Khởi động XAMPP
1. Mở XAMPP Control Panel
2. Khởi động Apache và MySQL
3. Đảm bảo cả hai dịch vụ đang chạy (có đèn xanh)

## Bước 2: Tạo cơ sở dữ liệu
1. Mở trình duyệt và truy cập: http://localhost/phpmyadmin
2. Tạo database mới với tên: `wowboxshop`
3. Chọn Collation: `utf8mb4_unicode_ci`

## Bước 3: Chạy Migration và Seeder
Mở Command Prompt hoặc PowerShell trong thư mục dự án và chạy các lệnh sau:

```bash
# Di chuyển đến thư mục dự án
cd c:\xampp\htdocs\wowboxshop

# Chạy migration để tạo các bảng
php artisan migrate

# Chạy seeder để tạo dữ liệu mẫu
php artisan db:seed
```

## Bước 4: Khởi động ứng dụng
```bash
# Khởi động Laravel development server
php artisan serve
```

Sau đó truy cập: http://localhost:8000

## Tài khoản mẫu (sau khi chạy seeder)
- **Admin**: 
  - Tên đăng nhập: `admin`
  - Mật khẩu: `123456`
  
- **Khách hàng**: 
  - Tên đăng nhập: `khachhang1`
  - Mật khẩu: `123456`

## Cấu trúc trang web
- **Trang chủ**: `/`
- **Đăng nhập**: `/dang-nhap`
- **Đăng ký**: `/dang-ky`
- **Dashboard Admin**: `/admin/dashboard` (chỉ cho admin)

## Lưu ý
- Đảm bảo XAMPP đang chạy trước khi sử dụng
- Kiểm tra file `.env` để đảm bảo cấu hình database đúng
- Nếu gặp lỗi, kiểm tra logs trong `storage/logs/laravel.log`