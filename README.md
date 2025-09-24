# WowBoxShop - Healthy Food E-commerce Platform

WowBoxShop là một nền tảng thương mại điện tử chuyên về thực phẩm healthy, được xây dựng bằng Laravel framework. Website cung cấp các sản phẩm như smoothie, salad, nước ép tươi, protein bowl và các món ăn healthy khác.

## Tính năng chính

- 🥗 Quản lý sản phẩm healthy food với đầy đủ nguyên liệu
- 🛒 Hệ thống giỏ hàng và đặt hàng
- 👤 Quản lý tài khoản và vai trò người dùng  
- 📍 Quản lý địa chỉ giao hàng
- ⭐ Hệ thống đánh giá sản phẩm
- 📝 Blog về dinh dưỡng và lối sống healthy
- 🔍 Phân loại sản phẩm theo danh mục

## Yêu cầu hệ thống

- **PHP**: >= 8.0
- **Composer**: Latest version
- **MySQL**: >= 5.7
- **XAMPP/WAMP/LAMP**: Hoặc môi trường web server tương tự
- **Node.js**: >= 14.x (nếu sử dụng Vite)

## Hướng dẫn cài đặt

### 1. Clone repository

```bash
git clone <repository-url>
cd wowboxshop
```

### 2. Cài đặt dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies (nếu cần)
npm install
```

### 3. Cấu hình môi trường

**Bước 3.1:** Copy file cấu hình môi trường
```bash
cp .env.example .env
```

**Bước 3.2:** Tạo APP_KEY
```bash
php artisan key:generate
```

**Bước 3.3:** Cấu hình database trong file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wowboxshop
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Thiết lập database

**Bước 4.1:** Khởi động XAMPP
- Mở XAMPP Control Panel
- Start **Apache** và **MySQL**

**Bước 4.2:** Tạo database
- Truy cập http://localhost/phpmyadmin
- Tạo database mới tên `wowboxshop`
- Chọn Collation: `utf8mb4_unicode_ci`

**Bước 4.3:** Chạy migrations
```bash
php artisan migrate
```

**Bước 4.4:** Chạy seeder để tạo dữ liệu mẫu
```bash
php artisan db:seed
```

### 5. Chạy ứng dụng

**Phương pháp 1:** Sử dụng Laravel development server
```bash
php artisan serve
```
Truy cập: http://localhost:8000

**Phương pháp 2:** Sử dụng XAMPP
- Copy project vào thư mục `C:\xampp\htdocs\`
- Truy cập: http://localhost/wowboxshop/public

### 6. Compile assets (nếu cần)

```bash
# Development
npm run dev

# Production
npm run build
```

## Dữ liệu mẫu

Sau khi chạy seeder, hệ thống sẽ có sẵn:

### Tài khoản mẫu
- **Admin**: 
  - Username: `admin`
  - Email: `admin@wowboxshop.com`
  - Password: `password`
  
- **Manager**:
  - Username: `manager01` 
  - Email: `manager@wowboxshop.com`
  - Password: `password`

- **Customer**:
  - Username: `customer01`
  - Email: `nguyenvana@gmail.com`
  - Password: `password`

### Dữ liệu có sẵn
- ✅ 3 vai trò người dùng
- ✅ 10 danh mục sản phẩm (có cấp bậc)
- ✅ 7 nhóm nguyên liệu
- ✅ 26 nguyên liệu với thông tin dinh dưỡng
- ✅ 12 sản phẩm healthy food
- ✅ 26 biến thể sản phẩm (kích thước khác nhau)
- ✅ 5 bài viết blog
- ✅ 11 đánh giá sản phẩm
- ✅ Dữ liệu giỏ hàng mẫu

## Cấu trúc database

Database bao gồm 18 bảng chính:

### Bảng người dùng & quyền
- `vai_tro` - Vai trò người dùng
- `tai_khoan` - Thông tin tài khoản  
- `dia_chi` - Địa chỉ giao hàng

### Bảng sản phẩm
- `danh_muc` - Danh mục sản phẩm
- `san_pham` - Thông tin sản phẩm
- `bien_the_san_pham` - Biến thể (size, giá)

### Bảng nguyên liệu
- `nhom_nguyen_lieu` - Nhóm nguyên liệu
- `nguyen_lieu` - Thông tin nguyên liệu
- `san_pham_nguyen_lieu` - Công thức sản phẩm

### Bảng giỏ hàng
- `gio_hang` - Giỏ hàng người dùng
- `chi_tiet_gio_hang` - Sản phẩm trong giỏ
- `chi_tiet_nguyen_lieu_gio_hang` - Nguyên liệu chi tiết

### Bảng đơn hàng  
- `don_hang` - Thông tin đơn hàng
- `chi_tiet_don_hang` - Sản phẩm trong đơn hàng
- `chi_tiet_nguyen_lieu_don_hang` - Nguyên liệu chi tiết

### Bảng khác
- `bai_viet_blog` - Bài viết blog
- `danh_gia` - Đánh giá sản phẩm

## Các lệnh hữu ích

```bash
# Xóa cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Chạy lại migration (cẩn thận: sẽ xóa dữ liệu)
php artisan migrate:refresh --seed

# Tạo symbolic link cho storage
php artisan storage:link

# Chạy queue jobs (nếu có)
php artisan queue:work
```

## Xử lý sự cố

### Lỗi thường gặp

**1. "Target machine actively refused it"**
- Kiểm tra MySQL đã khởi động trong XAMPP
- Xác nhận thông tin database trong `.env`

**2. "Class not found"**
- Chạy: `composer dump-autoload`

**3. "Permission denied"** 
- Cấp quyền write cho thư mục `storage` và `bootstrap/cache`

**4. "Mix manifest not found"**
- Chạy: `npm run dev` hoặc `npm run build`

### Debug mode
Để bật debug mode, thay đổi trong `.env`:
```env
APP_DEBUG=true
```

## Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## Liên hệ

- **Team Lead**: [Tên team lead]
- **Email**: [Email team]
- **Project Repository**: [Link repository]

## License

Dự án này được phát hành dưới [MIT License](https://opensource.org/licenses/MIT).

---

**Lưu ý**: Đảm bảo đã backup dữ liệu trước khi chạy `migrate:refresh` trong môi trường production!
