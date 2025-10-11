@echo off
echo ==========================================
echo        KHOI DONG WOWBOX SHOP
echo ==========================================
echo.

echo Luu y: Dam bao XAMPP MySQL dang chay truoc khi tiep tuc!
echo.
pause

echo Buoc 1: Refresh database va chay seeder...
php artisan migrate:fresh --seed

echo.
echo Buoc 2: Khoi dong server...
echo Trang web se mo tai: http://localhost:8000
echo.
echo Tai khoan mau:
echo - Admin: admin / 123456
echo - Khach hang: khachhang1 / 123456
echo.
echo Nhan Ctrl+C de dung server
echo.
php artisan serve