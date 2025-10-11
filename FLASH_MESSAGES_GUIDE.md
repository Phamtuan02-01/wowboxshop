# Hướng dẫn sử dụng Flash Messages trong WowBox Shop

## 1. Tự động hiển thị và ẩn sau 3 giây

Tất cả Flash Messages trong hệ thống sẽ tự động ẩn sau 3 giây. Điều này được cấu hình trong layout chính (`app.blade.php`).

## 2. Các loại thông báo

### 2.1 Sử dụng Helper Class (Khuyến nghị)

```php
use App\Helpers\FlashMessage;

// Thông báo thành công
FlashMessage::success('Đăng nhập thành công!');

// Thông báo lỗi
FlashMessage::error('Đã xảy ra lỗi!');

// Thông báo cảnh báo
FlashMessage::warning('Vui lòng kiểm tra lại thông tin!');

// Thông báo thông tin
FlashMessage::info('Thông tin được cập nhật!');

// Nhiều thông báo cùng lúc
FlashMessage::multiple([
    'success' => 'Tạo thành công!',
    'info' => 'Vui lòng kiểm tra email để xác nhận.'
]);
```

### 2.2 Sử dụng Session trực tiếp

```php
// Thông báo thành công
return redirect()->back()->with('success', 'Thành công!');

// Thông báo lỗi
return redirect()->back()->with('error', 'Có lỗi xảy ra!');

// Thông báo cảnh báo
return redirect()->back()->with('warning', 'Cảnh báo!');

// Thông báo thông tin
return redirect()->back()->with('info', 'Thông tin!');
```

## 3. Hiển thị từ JavaScript

```javascript
// Hiển thị thông báo từ JavaScript
showFlashMessage('success', 'Đã lưu thành công!');
showFlashMessage('error', 'Có lỗi xảy ra!');
showFlashMessage('warning', 'Cảnh báo!');
showFlashMessage('info', 'Thông tin!');
```

## 4. Tùy chỉnh thời gian hiển thị

Để thay đổi thời gian hiển thị (mặc định 3 giây), sửa trong `app.blade.php`:

```javascript
setTimeout(function() {
    // Code ẩn thông báo
}, 5000); // Thay đổi từ 3000 thành 5000 (5 giây)
```

## 5. Styling

CSS cho Flash Messages được định nghĩa trong `header-footer.css` với các class:
- `.flash-alert` - Style chung
- `.alert-success` - Thông báo thành công (xanh)
- `.alert-danger` - Thông báo lỗi (đỏ)  
- `.alert-warning` - Thông báo cảnh báo (vàng)
- `.alert-info` - Thông báo thông tin (xanh dương)

## 6. Ví dụ sử dụng trong Controller

```php
<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMessage;

class ExampleController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Logic tạo dữ liệu
            
            FlashMessage::success('Tạo thành công!');
            return redirect()->route('index');
            
        } catch (Exception $e) {
            FlashMessage::error('Có lỗi xảy ra: ' . $e->getMessage());
            return back()->withInput();
        }
    }
    
    public function update(Request $request, $id)
    {
        // Logic cập nhật
        
        FlashMessage::info('Đã cập nhật thông tin!');
        return redirect()->back();
    }
    
    public function delete($id)
    {
        // Logic xóa
        
        FlashMessage::warning('Đã xóa dữ liệu!');
        return redirect()->back();
    }
}
```