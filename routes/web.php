<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DangNhapController;
use App\Http\Controllers\DangKyController;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\DatMonController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\TuChonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Trang chủ
Route::get('/', [TrangChuController::class, 'index'])->name('trangchu');
Route::get('/trangchu', [TrangChuController::class, 'index'])->name('trangchu.view');

// Trang đặt món
Route::get('/dat-mon', [DatMonController::class, 'index'])->name('dat-mon.index');
Route::get('/dat-mon/san-pham/{id}', [DatMonController::class, 'chitiet'])->name('dat-mon.chitiet');
Route::post('/dat-mon/add-to-cart', [DatMonController::class, 'addToCart'])->name('dat-mon.add-to-cart');

// Trang tự chọn
Route::get('/tu-chon', [TuChonController::class, 'index'])->name('tu-chon.index');
Route::post('/tu-chon/update-session', [TuChonController::class, 'updateSession'])->name('tu-chon.update-session');
Route::post('/tu-chon/add-to-cart', [TuChonController::class, 'addToCart'])->name('tu-chon.add-to-cart');
Route::post('/tu-chon/clear-session', [TuChonController::class, 'clearSession'])->name('tu-chon.clear-session');

// Debug page
Route::get('/debug-data', function () {
    return view('debug-data');
})->name('debug-data');

Route::get('/debug-stock', function () {
    return view('debug-stock');
})->name('debug-stock');

// Test route
Route::get('/test-add-to-cart', function () {
    return view('test-add-to-cart');
})->name('test-add-to-cart');

// Trang thanh toán
Route::get('/thanh-toan', [App\Http\Controllers\ThanhToanController::class, 'index'])->name('thanh-toan.index');
Route::post('/thanh-toan/tim-cua-hang', [App\Http\Controllers\ThanhToanController::class, 'timCuaHangGanNhat'])->name('thanh-toan.tim-cua-hang');
Route::post('/thanh-toan/dat-hang', [App\Http\Controllers\ThanhToanController::class, 'datHang'])->name('thanh-toan.dat-hang');
Route::get('/thanh-toan/demo/{ma_don_hang}', [App\Http\Controllers\ThanhToanController::class, 'demo'])->name('thanh-toan.demo');
Route::post('/thanh-toan/demo-complete/{ma_don_hang}', [App\Http\Controllers\ThanhToanController::class, 'demoComplete'])->name('thanh-toan.demo-complete');
Route::get('/thanh-toan/thanh-cong/{ma_don_hang}', [App\Http\Controllers\ThanhToanController::class, 'success'])->name('thanh-toan.thanh-cong');

// MoMo Payment routes
Route::get('/thanh-toan/momo-return', [App\Http\Controllers\ThanhToanController::class, 'momoReturn'])->name('momo.return');
Route::post('/thanh-toan/momo-notify', [App\Http\Controllers\ThanhToanController::class, 'momoNotify'])->name('momo.notify');

Route::get('/momo/payment/{ma_don_hang}', function($ma_don_hang) {
    // Simulate MoMo payment - redirect to success page
    return redirect()->route('thanh-toan.thanh-cong', $ma_don_hang)->with('payment_success', true);
})->name('momo.payment');

// Chi tiết đơn hàng
Route::get('/don-hang/{ma_don_hang}', function($ma_don_hang) {
    // Redirect to order success page for now
    return redirect()->route('thanh-toan.thanh-cong', $ma_don_hang);
})->name('don-hang.chi-tiet');

// Test MoMo Payment
Route::get('/test-momo', function() {
    return view('test-momo');
})->name('test-momo');

Route::post('/test-momo-payment', function(Illuminate\Http\Request $request) {
    try {
        $momoService = new App\Services\MoMoPaymentService();
        
        $orderId = $request->input('orderId');
        $amount = (int) $request->input('amount');
        $orderInfo = $request->input('orderInfo');
        
        $result = $momoService->createPayment($orderId, $amount, $orderInfo);
        
        return response()->json($result);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra',
            'error' => $e->getMessage()
        ]);
    }
})->name('test-momo.payment');

// Test Checkout Flow
Route::get('/test-checkout', function() {
    return view('test-checkout');
})->name('test-checkout');

// Test Payment Features
Route::get('/test-payment-features', function() {
    return view('test-payment-features');
})->name('test-payment-features');

Route::post('/check-database', function() {
    try {
        return response()->json([
            'success' => true,
            'products' => App\Models\SanPham::count(),
            'variants' => App\Models\BienTheSanPham::count(),
            'orders' => App\Models\DonHang::count(),
            'carts' => App\Models\GioHang::count()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
});

// Test Promotion Cart
Route::get('/test-promotion-cart', function() {
    return view('test-promotion-cart');
})->name('test-promotion-cart');

// Trang về chúng tôi
Route::get('/ve-chung-toi', function () {
    return view('veChungToi');
})->name('vechungtoi');

// Routes cho guest (chưa đăng nhập)
Route::middleware('guest')->group(function () {
    // Đăng nhập
    Route::get('/dang-nhap', [DangNhapController::class, 'hienThiFormDangNhap'])->name('dangnhap');
    Route::post('/dang-nhap', [DangNhapController::class, 'xuLyDangNhap']);
    
    // Đăng ký
    Route::get('/dang-ky', [DangKyController::class, 'hienThiFormDangKy'])->name('dangky');
    Route::post('/dang-ky', [DangKyController::class, 'xuLyDangKy']);
});

// Chatbot V2 (available for all users - with AI features)
Route::post('/chatbot/message', [App\Http\Controllers\ChatbotControllerV2::class, 'sendMessage'])->name('chatbot.message');

// Routes cho user đã đăng nhập
Route::middleware('auth')->group(function () {
    // Đăng xuất
    Route::post('/dang-xuat', [DangNhapController::class, 'dangXuat'])->name('dangxuat');
    
    // Thông tin cá nhân
    Route::get('/thong-tin-ca-nhan', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/thong-tin-ca-nhan', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/doi-mat-khau', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Lịch sử đơn hàng
    Route::get('/lich-su-don-hang', [App\Http\Controllers\OrderHistoryController::class, 'index'])->name('orders.history');
    Route::get('/don-hang/{id}', [App\Http\Controllers\OrderHistoryController::class, 'show'])->name('orders.show');
    Route::post('/don-hang/{id}/huy', [App\Http\Controllers\OrderHistoryController::class, 'cancel'])->name('orders.cancel');
    
    // Giỏ hàng
    Route::get('/gio-hang', [App\Http\Controllers\GioHangController::class, 'index'])->name('giohang');
    Route::post('/gio-hang/them', [App\Http\Controllers\GioHangController::class, 'themSanPham'])->name('giohang.them');
    Route::post('/gio-hang/cap-nhat/{id}', [App\Http\Controllers\GioHangController::class, 'capNhatSoLuong'])->name('giohang.capnhat');
    Route::get('/gio-hang/xoa/{id}', [App\Http\Controllers\GioHangController::class, 'xoaSanPham'])->name('giohang.xoa');
    Route::delete('/gio-hang/xoa/{id}', [App\Http\Controllers\GioHangController::class, 'xoaSanPhamAjax'])->name('giohang.xoa.ajax');
    Route::get('/gio-hang/count', [App\Http\Controllers\GioHangController::class, 'demSanPham'])->name('giohang.count');
});

// Route public để kiểm tra mã khuyến mãi (không cần admin)
Route::post('/api/check-promotion-code', [App\Http\Controllers\Admin\PromotionController::class, 'checkCode'])->name('promotions.check-code');

// Routes cho admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Quản lý người dùng
    Route::resource('users', UserManagementController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);
    
    // Các route bổ sung cho quản lý người dùng
    Route::post('/users/{id}/restore', [UserManagementController::class, 'restore'])->name('admin.users.restore');
    Route::post('/users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::get('/users/export/excel', [UserManagementController::class, 'export'])->name('admin.users.export');
    
    // Quản lý sản phẩm
    Route::resource('products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy'
    ]);
    
    // Các route bổ sung cho quản lý sản phẩm
    Route::get('/products/{id}/get-info', [ProductController::class, 'getProductInfo'])->name('admin.products.get-info');
    Route::patch('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('admin.products.toggle-status');
    Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('admin.products.bulk-action');
    
    // Quản lý biến thể sản phẩm
    Route::resource('product-variants', ProductVariantController::class)->names([
        'index' => 'admin.product-variants.index',
        'create' => 'admin.product-variants.create',
        'store' => 'admin.product-variants.store',
        'show' => 'admin.product-variants.show',
        'edit' => 'admin.product-variants.edit',
        'update' => 'admin.product-variants.update',
        'destroy' => 'admin.product-variants.destroy'
    ]);
    
    // Các route bổ sung cho quản lý biến thể sản phẩm
    Route::post('/product-variants/{id}/toggle-status', [ProductVariantController::class, 'toggleStatus'])->name('admin.product-variants.toggle-status');
    Route::post('/product-variants/bulk-action', [ProductVariantController::class, 'bulkAction'])->name('admin.product-variants.bulk-action');
    
    // Quản lý đơn hàng
    Route::resource('orders', OrderController::class)->only(['index', 'show'])->names([
        'index' => 'admin.orders.index',
        'show' => 'admin.orders.show'
    ]);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::get('/orders/statistics', [OrderController::class, 'getStatistics'])->name('admin.orders.statistics');
    Route::get('/orders/export/excel', [OrderController::class, 'export'])->name('admin.orders.export');
    
    // Quản lý vai trò
    Route::resource('roles', RoleController::class)->except(['show'])->names([
        'index' => 'admin.roles.index',
        'create' => 'admin.roles.create',
        'store' => 'admin.roles.store',
        'edit' => 'admin.roles.edit',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy',
    ]);
    
    // Quản lý danh mục sản phẩm
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    
    // Quản lý khuyến mãi
    Route::resource('promotions', PromotionController::class)->names([
        'index' => 'admin.promotions.index',
        'create' => 'admin.promotions.create',
        'store' => 'admin.promotions.store',
        'show' => 'admin.promotions.show',
        'edit' => 'admin.promotions.edit',
        'update' => 'admin.promotions.update',
        'destroy' => 'admin.promotions.destroy',
    ]);
    
    // Các route bổ sung cho khuyến mãi
    Route::patch('promotions/{promotion}/toggle-status', [PromotionController::class, 'toggleStatus'])->name('admin.promotions.toggle-status');
    Route::post('promotions/bulk-action', [PromotionController::class, 'bulkAction'])->name('admin.promotions.bulk-action');
    Route::post('promotions/check-code', [PromotionController::class, 'checkCode'])->name('admin.promotions.check-code');
    
    // Báo cáo
    Route::get('reports/revenue', [App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('admin.reports.revenue');
    Route::get('reports/revenue/export', [App\Http\Controllers\Admin\ReportController::class, 'exportRevenue'])->name('admin.reports.revenue.export');
    Route::get('reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('reports/sales/export', [App\Http\Controllers\Admin\ReportController::class, 'exportSales'])->name('admin.reports.sales.export');
});
