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
        // Get products with FULL details (limit 80 for better coverage)
        $products = SanPham::with(['bienThes', 'danhMuc', 'khuyenMaisRelation'])
            ->where('trang_thai', true)
            ->take(80)
            ->get()
            ->map(function($p) {
                $variants = $p->bienThes;
                $minPrice = $variants->min('gia') ?? 0;
                $maxPrice = $variants->max('gia') ?? 0;
                
                // Get ALL variants with details
                $variantDetails = $variants->map(function($v) {
                    return [
                        'size' => $v->kich_co ?? 'Standard',
                        'price' => $v->gia,
                        'calo' => $v->calo ?? 'N/A',
                        'protein' => $v->protein ?? 'N/A',
                        'carb' => $v->carb ?? 'N/A',
                        'fat' => $v->fat ?? 'N/A',
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
                
                return [
                    'id' => $p->ma_san_pham,
                    'name' => $p->ten_san_pham,
                    'category' => $p->danhMuc->ten_danh_muc ?? 'N/A',
                    'category_id' => $p->ma_danh_muc,
                    'price_min' => $minPrice,
                    'price_max' => $maxPrice,
                    'description' => $p->mo_ta ?? '',
                    'variants' => $variantDetails,
                    'avg_rating' => round($p->danhGias()->avg('sao') ?? 0, 1),
                    'review_count' => $p->danhGias()->count(),
                    'promotions' => $productPromotions,
                    'has_promotion' => count($productPromotions) > 0,
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
        
        if (Auth::check()) {
            $userName = Auth::user()->ho_ten;
            
            // Get order history
            $orders = DonHang::where('ma_tai_khoan', Auth::id())
                ->with('chiTietDonHangs.sanPham')
                ->latest('ngay_tao')
                ->take(5)
                ->get();
            
            foreach ($orders as $order) {
                $recentOrders[] = [
                    'id' => $order->ma_don_hang,
                    'status' => $order->trang_thai,
                    'total' => $order->tong_tien,
                    'date' => $order->ngay_tao->format('d/m/Y')
                ];
                
                foreach ($order->chiTietDonHangs as $detail) {
                    if ($detail->sanPham) {
                        $userHistory[] = $detail->sanPham->ten_san_pham;
                    }
                }
            }
            
            $userHistory = array_unique($userHistory);
        }
        
        return [
            'products' => $products,
            'categories' => $categories,
            'promotions' => $promotions,
            'user_name' => $userName,
            'user_history' => array_values($userHistory),
            'recent_orders' => $recentOrders,
            'total_products' => count($products),
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
        
        $prompt = "Bạn là Trợ Lý AI chuyên nghiệp của WowBox Shop - cửa hàng thực phẩm healthy cao cấp.

TÊN KHÁCH HÀNG: {$dbContext['user_name']}
TỔNG SỐ SẢN PHẨM: {$dbContext['total_products']}

NHIỆM VỤ CỦA BẠN (Quan trọng - đọc kỹ):
1. 🎯 Tư vấn CHÍNH XÁC dựa trên database bên dưới
2. 💰 So sánh giá, calo, dinh dưỡng (protein, carb, fat) CHI TIẾT
3. 🥗 Gợi ý combo phù hợp mục tiêu: giảm cân/tăng cơ/healthy/tiết kiệm
4. ⭐ Ưu tiên sản phẩm có rating cao, phù hợp lịch sử khách
5. 🎁 Tự động suggest khuyến mãi phù hợp với giá trị đơn
6. 📦 Trả lời về giao hàng, thanh toán, chính sách
7. 🔍 Tra cứu đơn hàng nếu khách yêu cầu

DATABASE SẢN PHẨM (Có promotions riêng cho từng sản phẩm):
$productsJson

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
$historyJson

ĐƠN HÀNG GẦN ĐÂY:
$ordersJson

HỘI THOẠI TRƯỚC ĐÓ:
$contextHistory

QUY TẮC TRẢ LỜI BẮT BUỘC:
1. ✅ ĐỌC KỸ DATABASE - Chỉ gợi ý sản phẩm CÓ TRONG DATABASE
2. ✅ FORMAT ĐÚNG: [ID:123] Tên Sản Phẩm - Giá (Calo: xxx, Protein: xxxg)
3. ✅ SO SÁNH CHI TIẾT: Giá, Calo, Protein, Carb, Fat, Rating
4. ✅ ƯU TIÊN: Sản phẩm khách đã mua > Rating cao > Phù hợp ngân sách
5. ✅ TƯ VẤN COMBO: Tính tổng giá, tổng calo, cân bằng dinh dưỡng
6. ✅ KHUYẾN MÃI THÔNG MINH:
   - Ưu tiên sản phẩm CÓ KHUYẾN MÃI RIÊNG (has_promotion = true)
   - Hiển thị mã giảm giá TRỰC TIẾP của sản phẩm nếu có
   - Suggest khuyến mãi danh mục nếu khách hỏi về danh mục
   - Tính toán giá sau giảm chính xác
   - Nếu có nhiều KM, gợi ý KM TỐT NHẤT cho khách
7. ✅ GIẢI THÍCH: Tại sao gợi ý món này (dinh dưỡng/giá/khuyến mãi/phù hợp mục tiêu)
8. ✅ TRẢ LỜI NGẮN: 3-6 câu, dễ đọc, có emoji phù hợp
9. ✅ TIẾNG VIỆT: Tự nhiên, thân thiện, chuyên nghiệp
10. ✅ XEM LẠI VARIANTS: Mỗi sản phẩm có nhiều size/giá khác nhau
11. ✅ KHI KHÁCH HỎI KHUYẾN MÃI: Ưu tiên sản phẩm có promotions[], sau đó mới đến khuyến mãi chung
12. ❌ TUYỆT ĐỐI KHÔNG bịa sản phẩm không có trong database
13. ❌ KHÔNG trả lời về chủ đề không liên quan đến food/health

THÔNG TIN LIÊN HỆ & CHÍNH SÁCH:
- Hotline: 028.6685.9055 | 028.6682.8055
- Email: biz.wowbox@gmail.com
- Địa chỉ: 654 Lương Hữu Khánh, P. Phạm Ngũ Lão, Q.1, TP.HCM
- Giờ hoạt động: 8:30 - 20:45 (Thứ 2 - CN)
- Phí ship nội thành: 10.000đ - 30 phút
- Phí ship ngoại thành: 25.000đ - 45 phút
- FREESHIP cho đơn từ 200.000đ
- Thanh toán: COD, MoMo, Chuyển khoản

VÍ DỤ FORMAT TRẢ LỜI CHUẨN (CÓ KHUYẾN MÃI):
\"Combo giảm cân SALE HOT cho bạn:

[ID:5] Salad Gà Nướng (Size M) - 65.000đ → 52.000đ 🔥
→ 350 calo, Protein: 28g, Carb: 15g, Fat: 8g ⭐4.8
→ ✨ GIẢM 20% - Mã: SALAD20 (khuyến mãi riêng sản phẩm này!)
Lý do: Protein cao, ít carb, ĐANG SALE cực sốc! 🥗

[ID:12] Smoothie Xoài (Size L) - 45.000đ
→ 280 calo, Vitamin C: 120%, đường tự nhiên ⭐4.6
Lý do: Giải khát, tăng đề kháng, giá tốt! 🥤

💰 Tổng: 97.000đ (đã giảm 13k) | Tổng calo: 630
🎁 Bonus: Dùng thêm mã FREESHIP (đơn từ 200k) để FREE SHIP!\"

VÍ DỤ KHI KHÁCH HỎI KHUYẾN MÃI:
\"🎁 TOP SẢN PHẨM ĐANG SALE HOT:

[ID:8] Cơm Gà Teriyaki - 85.000đ → 68.000đ (-20%)
[ID:15] Salad Tôm Bơ - 95.000đ → 76.000đ (-20%)
[ID:22] Smoothie Dâu - 42.000đ → 33.600đ (-20%)

Cả 3 món đều áp dụng mã FLASH20! ⚡
Tổng: 177.600đ (tiết kiệm 42.400đ!) 💰\"

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
