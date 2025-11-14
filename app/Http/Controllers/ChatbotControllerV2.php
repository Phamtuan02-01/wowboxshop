<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\DonHang;
use App\Models\KhuyenMai;
use App\Models\DanhGia;
use App\Services\GeminiService;

class ChatbotControllerV2 extends Controller
{
    protected $geminiService;
    
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    
    /**
     * Send message to Gemini AI chatbot
     */
    public function sendMessage(Request $request)
    {
        $message = trim($request->input('message'));
        $context = $request->input('context', []); // Chat history
        
        \Log::info('Chatbot received message: ' . $message);
        
        // Check if Gemini is configured
        if (!$this->geminiService->isAvailable()) {
            \Log::error('Gemini API key not configured');
            return response()->json([
                'type' => 'text',
                'message' => '😔 Xin lỗi, trợ lý AI chưa được cấu hình.\n\n' .
                           'Vui lòng liên hệ quản trị viên để thêm GEMINI_API_KEY vào .env\n\n' .
                           'Hoặc gọi hotline: 028.6685.9055'
            ]);
        }
        
        // Process with Gemini AI
        $response = $this->processWithGemini($message, $context);
        
        if (!$response) {
            \Log::error('Gemini AI failed to generate response');
            return response()->json([
                'type' => 'text',
                'message' => '😔 Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau.\n\n' .
                           'Nếu vẫn lỗi, vui lòng liên hệ hotline: 028.6685.9055'
            ]);
        }
        
        \Log::info('Chatbot response type: ' . ($response['type'] ?? 'unknown') . ' (Gemini AI)');
        
        return response()->json($response);
    }
    
    /**
     * Process message with Gemini AI
     */
    private function processWithGemini($message, $context)
    {
        try {
            // Get database context
            $databaseContext = $this->getDatabaseContext();
            
            // Build prompt
            $prompt = $this->buildGeminiPrompt($message, $context, $databaseContext);
            
            // Call Gemini with higher token limit for detailed responses
            $geminiResponse = $this->geminiService->generateResponse($prompt, [
                'temperature' => 0.7, // Slightly lower for more focused answers
                'maxTokens' => 1500, // Increased for detailed nutrition info
            ]);
            
            if (!$geminiResponse) {
                return null;
            }
            
            // Parse and format response
            return $this->formatGeminiResponse($geminiResponse);
            
        } catch (\Exception $e) {
            \Log::error('Gemini processing error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get database context for Gemini
     */
    private function getDatabaseContext()
    {
        // Get products with FULL details (limit 100 for better coverage)
        $products = SanPham::with(['bienThes', 'danhMuc', 'khuyenMaisRelation', 'danhGias'])
            ->where('trang_thai', true)
            ->take(100)
            ->get()
            ->map(function($p) {
                $variants = $p->bienThes;
                $minPrice = $variants->min('gia') ?? 0;
                $maxPrice = $variants->max('gia') ?? 0;
                
                // Get ALL variants with FULL details including size (kich_thuoc)
                $variantDetails = $variants->map(function($v) {
                    return [
                        'id' => $v->ma_bien_the,
                        'size' => $v->kich_thuoc ?? 'Standard',
                        'price' => $v->gia,
                        'calo' => $v->calo ?? 'N/A',
                        'stock' => $v->so_luong_ton ?? 0,
                        'stock_status' => $v->so_luong_ton > 10 ? 'Còn hàng' : ($v->so_luong_ton > 0 ? 'Sắp hết' : 'Hết hàng'),
                        'active' => $v->trang_thai ? 'Đang bán' : 'Ngưng bán',
                    ];
                })->toArray();
                
                // Get active promotions for this product (already eager loaded)
                $productPromotions = collect($p->khuyenMaisRelation ?? [])
                    ->filter(function($promo) {
                        return $promo->trang_thai == 'active' && 
                               $promo->ngay_bat_dau <= now() && 
                               $promo->ngay_ket_thuc >= now();
                    })
                    ->map(function($promo) {
                        return [
                            'name' => $promo->ten_khuyen_mai,
                            'code' => $promo->ma_code,
                            'value' => $promo->gia_tri,
                            'type' => $promo->loai_khuyen_mai,
                            'special_discount' => $promo->pivot->gia_tri_giam_cu_the ?? null,
                        ];
                    })->toArray();
                
                // Get recent reviews for this product
                $recentReviews = $p->danhGias->take(3)->map(function($review) {
                    return [
                        'rating' => $review->sao,
                        'comment' => $review->noi_dung ?? '',
                        'date' => $review->ngay_tao ? $review->ngay_tao->format('d/m/Y') : 'N/A',
                    ];
                })->toArray();
                
                return [
                    'id' => $p->ma_san_pham,
                    'name' => $p->ten_san_pham,
                    'category' => $p->danhMuc->ten_danh_muc ?? 'N/A',
                    'category_id' => $p->ma_danh_muc,
                    'price_min' => $minPrice,
                    'price_max' => $maxPrice,
                    'description' => $p->mo_ta ?? '',
                    'image' => $p->hinh_anh ?? 'default-product.png',
                    'variants' => $variantDetails,
                    'total_variants' => count($variantDetails),
                    'avg_rating' => round($p->danhGias->avg('sao') ?? 0, 1),
                    'review_count' => $p->danhGias->count(),
                    'recent_reviews' => $recentReviews,
                    'promotions' => $productPromotions,
                    'has_promotion' => count($productPromotions) > 0,
                    'status' => $p->trang_thai ? 'Đang bán' : 'Ngưng bán',
                ];
            })
            ->toArray();
        
        // Get categories with promotions
        $categories = DanhMuc::withCount('sanPhams')
            ->with('khuyenMais')
            ->get()
            ->map(function($c) {
                // Get active promotions for this category (already eager loaded)
                $categoryPromotions = collect($c->khuyenMais ?? [])
                    ->filter(function($promo) {
                        return $promo->trang_thai == 'active' && 
                               $promo->ngay_bat_dau <= now() && 
                               $promo->ngay_ket_thuc >= now();
                    })
                    ->map(function($promo) {
                        return [
                            'name' => $promo->ten_khuyen_mai,
                            'code' => $promo->ma_code,
                            'value' => $promo->gia_tri,
                            'type' => $promo->loai_khuyen_mai,
                            'special_discount' => $promo->pivot->gia_tri_giam_cu_the ?? null,
                        ];
                    })->toArray();
                
                return [
                    'id' => $c->ma_danh_muc,
                    'name' => $c->ten_danh_muc,
                    'product_count' => $c->san_phams_count,
                    'promotions' => $categoryPromotions,
                    'has_promotion' => count($categoryPromotions) > 0,
                ];
            })
            ->toArray();
        
        // Get promotions with MORE details
        $promotions = KhuyenMai::where('trang_thai', 'active')
            ->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->take(10)
            ->get()
            ->map(function($promo) {
                $description = '';
                if ($promo->loai_khuyen_mai === 'percentage') {
                    $description = "Giảm {$promo->gia_tri}%";
                } else {
                    $description = "Giảm " . number_format($promo->gia_tri, 0, ',', '.') . 'đ';
                }
                
                return [
                    'name' => $promo->ten_khuyen_mai,
                    'code' => $promo->ma_code,
                    'description' => $description,
                    'min_order' => $promo->gia_tri_don_hang_toi_thieu ?? 0,
                    'max_discount' => $promo->giam_gia_toi_da ?? 0,
                    'end_date' => $promo->ngay_ket_thuc->format('d/m/Y'),
                    'conditions' => $promo->mo_ta ?? 'Không có điều kiện đặc biệt'
                ];
            })
            ->toArray();
        
        // Get user info and history if logged in
        $userName = 'Khách';
        $userHistory = [];
        $recentOrders = [];
        $userStats = null;
        
        if (Auth::check()) {
            $userName = Auth::user()->ho_ten;
            $userId = Auth::id();
            
            // Get order history with FULL details
            $orders = DonHang::where('ma_tai_khoan', $userId)
                ->with(['chiTietDonHangs.sanPham', 'chiTietDonHangs.bienThe', 'diaChi'])
                ->latest('ngay_tao')
                ->take(10)
                ->get();
            
            // Map order status to Vietnamese
            $statusMap = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'preparing' => 'Đang chuẩn bị',
                'shipping' => 'Đang giao',
                'delivered' => 'Đã giao',
                'cancelled' => 'Đã hủy',
                'completed' => 'Hoàn thành',
            ];
            
            // Payment method map
            $paymentMap = [
                'cod' => 'Tiền mặt',
                'momo' => 'MoMo',
                'bank_transfer' => 'Chuyển khoản',
            ];
            
            foreach ($orders as $order) {
                // Get order items
                $orderItems = $order->chiTietDonHangs->map(function($detail) {
                    $price = $detail->gia_tai_thoi_diem_mua ?? 0;
                    return [
                        'product_name' => $detail->sanPham->ten_san_pham ?? 'N/A',
                        'size' => $detail->bienThe->kich_thuoc ?? 'Standard',
                        'quantity' => $detail->so_luong,
                        'price' => $price,
                        'subtotal' => $price * $detail->so_luong,
                    ];
                })->toArray();
                
                // Get shipping address
                $shippingAddress = 'N/A';
                if ($order->phuong_thuc_giao_hang === 'giao_hang') {
                    if ($order->diaChi) {
                        $shippingAddress = $order->diaChi->dia_chi_cu_the . ', ' . 
                                         $order->diaChi->phuong_xa . ', ' . 
                                         $order->diaChi->quan_huyen . ', ' . 
                                         $order->diaChi->tinh_thanh_pho;
                    } elseif ($order->dia_chi) {
                        $shippingAddress = $order->dia_chi . ', ' . ($order->tinh_thanh_pho ?? '');
                    }
                } else {
                    $shippingAddress = 'Nhận tại: ' . ($order->cua_hang_nhan ?? 'Cửa hàng WowBox');
                }
                
                $recentOrders[] = [
                    'id' => $order->ma_don_hang,
                    'status' => $statusMap[$order->trang_thai] ?? $order->trang_thai,
                    'status_code' => $order->trang_thai,
                    'total' => $order->tong_tien,
                    'discount' => $order->giam_gia_khuyen_mai ?? 0,
                    'promotion_code' => $order->ma_khuyen_mai ?? null,
                    'payment_method' => $paymentMap[$order->phuong_thuc_thanh_toan] ?? $order->phuong_thuc_thanh_toan,
                    'delivery_method' => $order->phuong_thuc_giao_hang === 'giao_hang' ? 'Giao hàng tận nơi' : 'Nhận tại cửa hàng',
                    'shipping_address' => $shippingAddress,
                    'customer_name' => $order->ho_ten ?? 'N/A',
                    'customer_phone' => $order->so_dien_thoai ?? 'N/A',
                    'note' => $order->ghi_chu ?? '',
                    'date' => $order->ngay_tao->format('d/m/Y H:i'),
                    'items' => $orderItems,
                    'item_count' => count($orderItems),
                ];
                
                // Collect user history
                foreach ($order->chiTietDonHangs as $detail) {
                    if ($detail->sanPham) {
                        $userHistory[] = $detail->sanPham->ten_san_pham;
                    }
                }
            }
            
            $userHistory = array_unique($userHistory);
            
            // Calculate user statistics
            $allOrders = DonHang::where('ma_tai_khoan', $userId)->get();
            $userStats = [
                'total_orders' => $allOrders->count(),
                'total_spent' => $allOrders->sum('tong_tien'),
                'completed_orders' => $allOrders->where('trang_thai', 'completed')->count(),
                'pending_orders' => $allOrders->whereIn('trang_thai', ['pending', 'confirmed', 'preparing', 'shipping'])->count(),
                'cancelled_orders' => $allOrders->where('trang_thai', 'cancelled')->count(),
                'favorite_products' => array_slice(array_count_values($userHistory), 0, 5),
            ];
        }
        
        return [
            'products' => $products,
            'categories' => $categories,
            'promotions' => $promotions,
            'user_name' => $userName,
            'user_history' => array_values($userHistory),
            'recent_orders' => $recentOrders,
            'user_stats' => $userStats,
            'total_products' => count($products),
            'is_logged_in' => Auth::check(),
        ];
    }
    
    /**
     * Build prompt for Gemini
     */
    private function buildGeminiPrompt($userMessage, $context, $dbContext)
    {
        $productsJson = json_encode($dbContext['products'], JSON_UNESCAPED_UNICODE);
        $categoriesJson = json_encode($dbContext['categories'], JSON_UNESCAPED_UNICODE);
        $promotionsJson = json_encode($dbContext['promotions'], JSON_UNESCAPED_UNICODE);
        $historyJson = json_encode($dbContext['user_history'], JSON_UNESCAPED_UNICODE);
        $ordersJson = json_encode($dbContext['recent_orders'], JSON_UNESCAPED_UNICODE);
        
        $contextHistory = '';
        if (!empty($context)) {
            $lastMessages = array_slice($context, -5); // Last 5 messages
            foreach ($lastMessages as $msg) {
                $role = $msg['role'] === 'user' ? 'Khách' : 'Bot';
                $contextHistory .= "$role: {$msg['message']}\n";
            }
        }
        
        // Add user stats if available
        $userStatsText = '';
        if ($dbContext['user_stats']) {
            $stats = $dbContext['user_stats'];
            $userStatsText = "\n\nTHỐNG KÊ KHÁCH HÀNG:\n" . json_encode($stats, JSON_UNESCAPED_UNICODE);
        }
        
        $prompt = "Bạn là Trợ Lý AI chuyên nghiệp của WowBox Shop - cửa hàng thực phẩm healthy cao cấp.

TÊN KHÁCH HÀNG: {$dbContext['user_name']}
TRẠNG THÁI: " . ($dbContext['is_logged_in'] ? 'Đã đăng nhập ✅' : 'Chưa đăng nhập') . "
TỔNG SỐ SẢN PHẨM: {$dbContext['total_products']}

NHIỆM VỤ CỦA BẠN (Quan trọng - đọc kỹ):
1. 🎯 Tư vấn CHÍNH XÁC dựa trên database bên dưới
2. � SO SÁNH CHI TIẾT theo SIZE (kich_thuoc): Giá, Calo, Tồn kho cho từng size
3. 💰 Giúp khách chọn size phù hợp với ngân sách và nhu cầu
4. 🥗 Gợi ý combo phù hợp mục tiêu: giảm cân/tăng cơ/healthy/tiết kiệm
5. ⭐ Ưu tiên sản phẩm có rating cao, phù hợp lịch sử khách
6. 🎁 Tự động suggest khuyến mãi phù hợp với giá trị đơn
7. 📦 TRA CỨU ĐƠN HÀNG CHI TIẾT - Khi khách hỏi về đơn hàng, trả lời đầy đủ:
   - Trạng thái đơn (Chờ xác nhận/Đang giao/Đã giao...)
   - Sản phẩm trong đơn (tên, size, số lượng)
   - Tổng tiền, phí ship, giảm giá
   - Địa chỉ giao hàng
   - Phương thức thanh toán
8. 🔍 Thông tin giao hàng, thanh toán, chính sách

DATABASE SẢN PHẨM (Có variants với SIZE và thông tin chi tiết):
$productsJson

LƯU Ý VỀ VARIANTS (Biến thể sản phẩm):
- Mỗi sản phẩm có NHIỀU SIZE khác nhau (variants[])
- Mỗi size có: id, size (kich_thuoc), price, calo, stock, stock_status
- KHI TƯ VẤN: Nêu rõ SIZE và giá của từng size
- Ví dụ: \"Salad Gà có 3 size: S (45k-300cal), M (65k-450cal), L (85k-600cal)\"
- Kiểm tra stock_status để biết còn hàng hay hết
- Gợi ý size phù hợp với nhu cầu (ăn nhẹ→S, bữa chính→M, chia sẻ→L)

DANH MỤC (Có promotions áp dụng cho cả danh mục): 
$categoriesJson

KHUYẾN MÃI CHUNG (Áp dụng cho tất cả đơn hàng):
$promotionsJson

LƯU Ý QUAN TRỌNG VỀ KHUYẾN MÃI:
- Mỗi sản phẩm có thể có khuyến mãi riêng (xem trong products[x].promotions)
- Mỗi danh mục có thể có khuyến mãi riêng (xem trong categories[x].promotions)
- Khuyến mãi chung áp dụng cho tổng đơn hàng
- Khi tư vấn, ưu tiên khuyến mãi TRỰC TIẾP trên sản phẩm trước
- \"special_discount\" là giá trị giảm ĐẶC BIỆT riêng cho sản phẩm/danh mục đó

LỊCH SỬ MUA CỦA KHÁCH: 
$historyJson$userStatsText

ĐƠN HÀNG GẦN ĐÂY (Chi tiết đầy đủ):
$ordersJson

HỘI THOẠI TRƯỚC ĐÓ:
$contextHistory

QUY TẮC TRẢ LỜI BẮT BUỘC:
1. ✅ ĐỌC KỸ DATABASE - Chỉ gợi ý sản phẩm CÓ TRONG DATABASE
2. ✅ FORMAT ĐÚNG: [ID:123] Tên Sản Phẩm (Size X) - Giá (Calo: xxx)
3. ✅ SO SÁNH SIZE CHI TIẾT: 
   - Luôn hiển thị TẤT CẢ SIZE có sẵn của sản phẩm
   - Format: \"Size S: 45k (300cal), Size M: 65k (450cal), Size L: 85k (600cal)\"
   - Nêu rõ tình trạng còn hàng/sắp hết/hết hàng cho từng size
4. ✅ ƯU TIÊN: Sản phẩm khách đã mua > Rating cao > Phù hợp ngân sách
5. ✅ TƯ VẤN COMBO: Tính tổng giá, tổng calo, cân bằng dinh dưỡng
6. ✅ KHUYẾN MÃI THÔNG MINH:
   - Ưu tiên sản phẩm CÓ KHUYẾN MÃI RIÊNG (has_promotion = true)
   - Hiển thị mã giảm giá TRỰC TIẾP của sản phẩm nếu có
   - Suggest khuyến mãi danh mục nếu khách hỏi về danh mục
   - Tính toán giá sau giảm chính xác
   - Nếu có nhiều KM, gợi ý KM TỐT NHẤT cho khách
7. ✅ TRA CỨU ĐƠN HÀNG:
   - Khi khách hỏi \"đơn hàng của tôi\" hoặc \"đơn số X\"
   - Trả lời: Trạng thái, Sản phẩm (tên + size), Tổng tiền, Địa chỉ giao
   - Nếu khách hỏi \"đơn mới nhất\", lấy đơn đầu tiên trong recent_orders
   - Giải thích ý nghĩa trạng thái (VD: \"Đang giao\" = ship đang mang đến)
8. ✅ THỐNG KÊ KHÁCH HÀNG:
   - Khi khách hỏi \"tôi đã mua gì\", \"lịch sử mua hàng\"
   - Trả lời: Tổng đơn đã đặt, Tổng chi tiêu, Món ăn yêu thích
9. ✅ GIẢI THÍCH: Tại sao gợi ý món này (dinh dưỡng/giá/khuyến mãi/size phù hợp)
10. ✅ TRẢ LỜI NGẮN: 3-6 câu, dễ đọc, có emoji phù hợp
11. ✅ TIẾNG VIỆT: Tự nhiên, thân thiện, chuyên nghiệp
12. ✅ XEM KỸ VARIANTS: Đọc hết variants[] để biết tất cả size có sẵn
13. ✅ KHI KHÁCH HỎI KHUYẾN MÃI: Ưu tiên sản phẩm có promotions[]
14. ❌ TUYỆT ĐỐI KHÔNG bịa sản phẩm/size không có trong database
15. ❌ KHÔNG trả lời về chủ đề không liên quan đến food/health/đơn hàng

THÔNG TIN LIÊN HỆ & CHÍNH SÁCH:
- Hotline: 028.6685.9055 | 028.6682.8055
- Email: biz.wowbox@gmail.com
- Địa chỉ: 654 Lương Hữu Khánh, P. Phạm Ngũ Lão, Q.1, TP.HCM
- Giờ hoạt động: 8:30 - 20:45 (Thứ 2 - CN)
- Phí ship nội thành: 10.000đ - 30 phút
- Phí ship ngoại thành: 25.000đ - 45 phút
- FREESHIP cho đơn từ 200.000đ
- Thanh toán: COD, MoMo, Chuyển khoản

VÍ DỤ FORMAT TRẢ LỜI CHUẨN (CÓ NHIỀU SIZE):
\"Salad Gà Nướng có 3 size cho bạn chọn:

[ID:5] Salad Gà Nướng ⭐4.8 (127 đánh giá)
📏 Size S: 45.000đ (300 calo) - Còn hàng ✅
📏 Size M: 65.000đ (450 calo) - Còn hàng ✅  ← PHỔ BIẾN
📏 Size L: 85.000đ (600 calo) - Sắp hết ⚠️

✨ GIẢM 20% - Mã: SALAD20 (áp dụng tất cả size!)
💡 Gợi ý: Size M vừa đủ cho bữa trưa, protein cao 28g! 🥗\"

VÍ DỤ KHI KHÁCH HỎI ĐƠN HÀNG:
\"📦 ĐƠN HÀNG #1234 của bạn:

🚚 Trạng thái: Đang giao hàng (ship đang mang đến)
📅 Ngày đặt: 15/11/2025 10:30

Sản phẩm:
• Salad Gà Nướng (Size M) x2 - 130.000đ
• Smoothie Xoài (Size L) x1 - 45.000đ

💰 Tổng cộng: 175.000đ
🎁 Giảm giá: -20.000đ (Mã: FLASH20)
✅ Thanh toán: COD (Tiền mặt)
🚚 Hình thức: Giao hàng tận nơi

📍 Giao đến: 123 Nguyễn Văn A, P.1, Q.1, TP.HCM
👤 Người nhận: Nguyễn Văn A - 0901234567

⏰ Dự kiến giao: Hôm nay trong 30-45 phút\"

VÍ DỤ KHI KHÁCH HỎI LỊCH SỬ:
\"📊 Thống kê của bạn tại WowBox:

✅ Tổng đơn: 15 đơn
💰 Tổng chi tiêu: 1.850.000đ
🎯 Hoàn thành: 12 đơn

❤️ Món bạn thích nhất:
1. Salad Gà Nướng (đã mua 8 lần)
2. Smoothie Xoài (đã mua 5 lần)
3. Cơm Gà Teriyaki (đã mua 4 lần)

🎁 Bạn là khách hàng thân thiết! Có mã FLASH20 giảm 20% đấy!\"

CÂU HỎI CỦA KHÁCH:
$userMessage

TRẢ LỜI (Tiếng Việt, ngắn gọn, có [ID:x] nếu gợi ý sản phẩm):";

        return $prompt;
    }
    
    /**
     * Format Gemini response
     */
    private function formatGeminiResponse($geminiText)
    {
        // Extract product IDs from [ID:x] tags
        preg_match_all('/\[ID:(\d+)\]/', $geminiText, $matches);
        $productIds = $matches[1] ?? [];
        
        $products = [];
        if (!empty($productIds)) {
            $products = SanPham::whereIn('ma_san_pham', $productIds)
                ->with('bienThes')
                ->get()
                ->map(function($product) {
                    return $this->formatProduct($product);
                })
                ->toArray();
        }
        
        // Remove [ID:x] tags from text for cleaner display
        $cleanText = preg_replace('/\[ID:\d+\]\s*/', '', $geminiText);
        $cleanText = trim($cleanText);
        
        return [
            'type' => !empty($products) ? 'products' : 'text',
            'message' => $cleanText,
            'products' => $products,
            'ai_powered' => true
        ];
    }
    
    /**
     * Format product for response
     */
    private function formatProduct($product)
    {
        $variants = $product->bienThes;
        
        if ($variants->isEmpty()) {
            $priceRange = 'Liên hệ';
        } elseif ($variants->count() == 1) {
            $priceRange = number_format($variants->first()->gia, 0, ',', '.') . 'đ';
        } else {
            $minPrice = $variants->min('gia');
            $maxPrice = $variants->max('gia');
            $priceRange = number_format($minPrice, 0, ',', '.') . 'đ - ' . 
                         number_format($maxPrice, 0, ',', '.') . 'đ';
        }
        
        return [
            'id' => $product->ma_san_pham,
            'name' => $product->ten_san_pham,
            'image' => asset('images/products/' . $product->hinh_anh),
            'price_range' => $priceRange,
            'url' => route('dat-mon.chitiet', $product->ma_san_pham)
        ];
    }
}
