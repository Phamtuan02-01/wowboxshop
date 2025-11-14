<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SanPham;
use App\Models\BienTheSanPham;
use App\Models\DanhMuc;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use App\Models\DonHang;
use App\Models\KhuyenMai;
use App\Services\GeminiService;

class ChatbotControllerV2 extends Controller
{
    protected $geminiService;
    
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    
    public function sendMessage(Request $request)
    {
        $message = trim($request->input('message'));
        $context = $request->input('context', []); // Chat history
        
        \Log::info('Chatbot received message: ' . $message);
        
        // Check if Gemini is configured
        if (!$this->geminiService->isAvailable()) {
            return response()->json([
                'type' => 'text',
                'message' => '😔 Xin lỗi, trợ lý AI chưa được cấu hình. Vui lòng liên hệ quản trị viên để thêm GEMINI_API_KEY vào .env'
            ]);
        }
        
        // Process with Gemini AI
        $response = $this->processWithGemini($message, $context);
        
        if (!$response) {
            return response()->json([
                'type' => 'text',
                'message' => '😔 Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau hoặc liên hệ hotline: 028.6685.9055'
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
            
            // Call Gemini
            $geminiResponse = $this->geminiService->generateResponse($prompt, [
                'temperature' => 0.8,
                'maxTokens' => 800,
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
        // Get products with details
        $products = SanPham::with(['bienThes', 'danhMuc'])
            ->where('trang_thai', true)
            ->take(50) // Limit to avoid token overflow
            ->get()
            ->map(function($p) {
                $variants = $p->bienThes;
                $minPrice = $variants->min('gia') ?? 0;
                $maxPrice = $variants->max('gia') ?? 0;
                $calo = $variants->first()->calo ?? 'N/A';
                
                return [
                    'id' => $p->ma_san_pham,
                    'name' => $p->ten_san_pham,
                    'category' => $p->danhMuc->ten_danh_muc ?? 'N/A',
                    'price_min' => $minPrice,
                    'price_max' => $maxPrice,
                    'calo' => $calo,
                    'description' => substr($p->mo_ta ?? '', 0, 100),
                ];
            })
            ->toArray();
        
        // Get categories
        $categories = DanhMuc::withCount('sanPhams')
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->ma_danh_muc,
                    'name' => $c->ten_danh_muc,
                    'product_count' => $c->san_phams_count
                ];
            })
            ->toArray();
        
        // Get user history if logged in
        $userHistory = [];
        $userName = 'Khách';
        
        if (Auth::check()) {
            $userName = Auth::user()->ho_ten;
            
            $orders = DonHang::where('ma_tai_khoan', Auth::id())
                ->with('chiTietDonHangs.sanPham')
                ->latest('ngay_tao')
                ->take(3)
                ->get();
            
            foreach ($orders as $order) {
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
            'user_name' => $userName,
            'user_history' => $userHistory,
            'total_products' => count($products),
        ];
    }
    
    /**
     * Build prompt for Gemini
     */
    private function buildGeminiPrompt($userMessage, $context, $dbContext)
    {
        $productsJson = json_encode($dbContext['products'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $categoriesJson = json_encode($dbContext['categories'], JSON_UNESCAPED_UNICODE);
        $historyJson = json_encode($dbContext['user_history'], JSON_UNESCAPED_UNICODE);
        
        $contextHistory = '';
        if (!empty($context)) {
            $lastMessages = array_slice($context, -3); // Last 3 messages
            foreach ($lastMessages as $msg) {
                $role = $msg['role'] === 'user' ? 'Khách' : 'Bot';
                $contextHistory .= "$role: {$msg['message']}\n";
            }
        }
        
        $prompt = "Bạn là Trợ Lý AI thông minh của WowBox Shop - cửa hàng thực phẩm healthy.

TÊN KHÁCH HÀNG: {$dbContext['user_name']}

NHIỆM VỤ CỦA BẠN:
1. Tư vấn món ăn dựa trên sở thích, ngân sách, dinh dưỡng
2. So sánh sản phẩm (giá, calo, thành phần)
3. Gợi ý combo phù hợp với mục tiêu (giảm cân, tăng cơ, healthy)
4. Trả lời thắc mắc về sản phẩm

DATABASE SẢN PHẨM (JSON):
$productsJson

DANH MỤC: $categoriesJson

LỊCH SỬ MUA CỦA KHÁCH: $historyJson

HỘI THOẠI TRƯỚC ĐÓ:
$contextHistory

QUY TẮC TRẢ LỜI:
1. ✅ LUÔN gợi ý sản phẩm CỤ THỂ từ database (với ID, tên, giá)
2. ✅ Khi gợi ý sản phẩm, format: [ID:123] Tên Sản Phẩm - Giá
3. ✅ So sánh chi tiết khi được hỏi (giá, calo, thành phần)
4. ✅ Ưu tiên sản phẩm khách đã mua nếu có lịch sử
5. ✅ Trả lời ngắn gọn (2-4 câu), thân thiện, dùng emoji
6. ✅ Nếu không chắc, gợi ý 2-3 options để khách chọn
7. ✅ Luôn trả lời bằng TIẾNG VIỆT
8. ❌ KHÔNG nói về sản phẩm không có trong database
9. ❌ KHÔNG trả lời về chính trị, tôn giáo, hay chủ đề nhạy cảm

VÍ DỤ FORMAT TRẢ LỜI TỐT:
\"Dựa vào nhu cầu giảm cân của bạn, tôi gợi ý 2 món này:

[ID:5] Salad Gà Nướng - 65.000đ (350 calo) 🥗
Ít calo nhưng giàu protein, giúp no lâu!

[ID:12] Smoothie Xoài - 45.000đ (280 calo) 🥤
Bổ sung vitamin, thanh mát!

Bạn thích món nào hơn? 😊\"

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

    private function processMessage($message, $context = [], $sessionId = null)
    {
        // 1. Greeting with personalization
        if ($this->containsWords($message, ['xin chào', 'chào', 'hello', 'hi'])) {
            $userName = Auth::check() ? Auth::user()->ho_ten : 'bạn';
            $greeting = $this->getTimeBasedGreeting();
            
            return [
                'type' => 'text',
                'message' => "{$greeting} {$userName}! 👋\n\n" .
                           "Tôi là trợ lý AI thông minh của WowBox Shop. Tôi có thể:\n\n" .
                           "🍕 Gợi ý món ăn phù hợp với bạn\n" .
                           "🎁 Tìm ưu đãi tốt nhất\n" .
                           "🛒 Thêm món vào giỏ hàng ngay\n" .
                           "📦 Tra cứu đơn hàng\n" .
                           "❓ Tư vấn 24/7\n\n" .
                           "Hôm nay bạn muốn gì nào? 😊"
            ];
        }

        // 2. Order tracking
        if ($this->containsWords($message, ['đơn hàng', 'theo dõi đơn', 'kiểm tra đơn', 'đơn của tôi', 'order'])) {
            if (!Auth::check()) {
                return [
                    'type' => 'text',
                    'message' => "Để tra cứu đơn hàng, bạn cần đăng nhập nhé! 🔐\n\n" .
                               "Vui lòng đăng nhập để xem lịch sử đơn hàng của bạn.",
                    'action' => 'login'
                ];
            }

            $recentOrders = DonHang::where('ma_tai_khoan', Auth::id())
                ->orderBy('ngay_tao', 'desc')
                ->take(3)
                ->get();

            if ($recentOrders->isEmpty()) {
                return [
                    'type' => 'text',
                    'message' => "Bạn chưa có đơn hàng nào! 🛒\n\n" .
                               "Hãy khám phá thực đơn và đặt món ngon nào! 😋"
                ];
            }

            $orderList = $recentOrders->map(function($order) {
                $status = $this->getStatusText($order->trang_thai);
                $total = number_format($order->tong_tien, 0, ',', '.');
                return "📦 Đơn #{$order->ma_don_hang} - {$status}\n   💰 {$total}đ - " . 
                       $order->ngay_tao->format('d/m H:i');
            })->implode("\n\n");

            return [
                'type' => 'text',
                'message' => "🛍️ Đơn hàng gần đây của bạn:\n\n{$orderList}\n\n" .
                           "Muốn xem chi tiết? Truy cập trang Lịch sử đơn hàng nhé!"
            ];
        }

        // 3. Smart product recommendations based on user behavior
        if ($this->containsWords($message, ['gợi ý', 'đề xuất', 'giới thiệu', 'món gì ngon', 'nên ăn gì', 'món ngon', 'gợi ý món'])) {
            $products = $this->getSmartRecommendations($context);
            
            return [
                'type' => 'products',
                'message' => "🌟 Dựa trên sở thích của bạn, tôi gợi ý những món này:",
                'products' => $products->toArray(),
                'reason' => 'personalized'
            ];
        }

        // 4. Quick add to cart from chat
        if ($this->containsWords($message, ['thêm', 'mua', 'đặt']) && 
            preg_match('/(\d+)/', $message, $matches)) {
            
            $productId = $matches[1];
            
            if (!Auth::check()) {
                return [
                    'type' => 'text',
                    'message' => "Để thêm món vào giỏ hàng, bạn cần đăng nhập trước nhé! 🔐",
                    'action' => 'login'
                ];
            }

            return $this->quickAddToCart($productId);
        }

        // 5. Cart/Order related
        if ($this->containsWords($message, ['giỏ hàng', 'gio hang', 'cart', 'đặt món', 'dat mon', 'order', 'đặt hàng'])) {
            if ($this->containsWords($message, ['xem', 'kiểm tra', 'check'])) {
                return [
                    'type' => 'text',
                    'message' => "🛒 Để xem giỏ hàng, bạn click vào icon giỏ hàng ở góc trên bên phải nhé!\n\n" .
                               "Hoặc tôi có thể giúp bạn:\n" .
                               "• 🔍 Tìm món ăn\n" .
                               "• 🎁 Xem khuyến mãi\n" .
                               "• 🌟 Gợi ý món ngon\n\n" .
                               "Bạn muốn làm gì?"
                ];
            }
            
            return [
                'type' => 'text',
                'message' => "🍽️ Bạn muốn đặt món à?\n\n" .
                           "Tôi có thể giúp bạn:\n" .
                           "• 🔍 Tìm món theo tên (VD: 'Tìm salad')\n" .
                           "• 📋 Xem danh mục: Nói 'Có món gì?'\n" .
                           "• 🌟 Gợi ý món ngon phù hợp\n" .
                           "• 🎁 Xem khuyến mãi đang có\n\n" .
                           "Hãy nói tôi biết bạn muốn gì! 😊"
            ];
        }

        // 6. Ask about menu/categories
        if ($this->containsWords($message, ['có món gì', 'có gì', 'menu', 'thực đơn', 'món ăn'])) {
            $categories = DanhMuc::withCount('sanPhams')->get();
            $categoryList = $categories->map(function($cat) {
                return "• {$cat->ten_danh_muc} ({$cat->san_phams_count} món)";
            })->implode("\n");
            
            return [
                'type' => 'text',
                'message' => "📋 Danh mục món ăn:\n\n" . $categoryList . 
                           "\n\n💡 Tip: Nói tên danh mục hoặc hỏi 'Gợi ý món ngon' để tôi tư vấn cho bạn!"
            ];
        }

        // 7. Search by category (enhanced)
        $categories = DanhMuc::all();
        foreach ($categories as $category) {
            $categoryNameLower = strtolower($category->ten_danh_muc);
            
            // Check if message contains category name or keywords
            if (str_contains($message, $categoryNameLower) || 
                str_contains($categoryNameLower, $message)) {
                
                $products = SanPham::where('ma_danh_muc', $category->ma_danh_muc)
                    ->with('bienThes')
                    ->inRandomOrder()
                    ->take(6)
                    ->get()
                    ->map(function($product) {
                        return $this->formatProduct($product);
                    })
                    ->toArray();
                
                if (empty($products)) {
                    return [
                        'type' => 'text',
                        'message' => "Hiện tại chưa có món {$category->ten_danh_muc}. Bạn xem danh mục khác nhé! 🍽️"
                    ];
                }
                
                return [
                    'type' => 'products',
                    'message' => "🍽️ Top món {$category->ten_danh_muc} bán chạy:",
                    'products' => $products->toArray()
                ];
            }
        }

        // 8. Popular/trending products
        if ($this->containsWords($message, ['phổ biến', 'bán chạy', 'hot', 'trend', 'nổi tiếng', 'yêu thích', 'có gì hot'])) {
            $products = SanPham::with('bienThes')
                ->inRandomOrder()
                ->take(6)
                ->get()
                ->map(function($product) {
                    return $this->formatProduct($product);
                });
            
            return [
                'type' => 'products',
                'message' => "🔥 Top món HOT nhất hiện nay:",
                'products' => $products->toArray()
            ];
        }

        // 9. Product search (enhanced)
        if ($this->containsWords($message, ['tìm', 'tìm kiếm', 'search', 'có món', 'có không'])) {
            $searchTerm = $this->extractSearchTerm($message);
            
            if ($searchTerm) {
                $products = SanPham::where('ten_san_pham', 'like', "%{$searchTerm}%")
                    ->with('bienThes')
                    ->take(6)
                    ->get()
                    ->map(function($product) {
                        return $this->formatProduct($product);
                    });
                
                if ($products->isEmpty()) {
                    $suggestions = $this->getSimilarProducts($searchTerm);
                    
                    if ($suggestions->isNotEmpty()) {
                        return [
                            'type' => 'products',
                            'message' => "❌ Không tìm thấy '{$searchTerm}'\n\n✨ Có phải bạn muốn tìm:",
                            'products' => $suggestions->toArray()
                        ];
                    }
                    
                    return [
                        'type' => 'text',
                        'message' => "😅 Xin lỗi, không tìm thấy '{$searchTerm}'.\n\n" .
                                   "Bạn thử tìm món khác hoặc xem danh mục nhé!"
                    ];
                }
                
                return [
                    'type' => 'products',
                    'message' => "🔍 Kết quả tìm '{$searchTerm}':",
                    'products' => $products->toArray()
                ];
            }
        }

        // 10. Promotions/discounts
        if ($this->containsWords($message, ['khuyến mãi', 'giảm giá', 'ưu đãi', 'voucher', 'mã giảm', 'sale', 'km'])) {
            $promotions = KhuyenMai::where('trang_thai', 'active')
                ->where('ngay_bat_dau', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->get();
            
            if ($promotions->isEmpty()) {
                return [
                    'type' => 'text',
                    'message' => "😔 Hiện tại chưa có chương trình khuyến mãi nào.\n\n" .
                               "Hãy theo dõi để không bỏ lỡ ưu đãi hấp dẫn nhé!"
                ];
            }
            
            $promoList = $promotions->map(function($promo) {
                $discount = $promo->loai_khuyen_mai === 'phan_tram' 
                    ? "{$promo->gia_tri}%" 
                    : number_format($promo->gia_tri, 0, ',', '.') . "đ";
                
                return "🎁 {$promo->ten_khuyen_mai}\n" .
                       "   💝 Giảm {$discount}\n" .
                       "   📅 Đến " . $promo->ngay_ket_thuc->format('d/m/Y');
            })->implode("\n\n");
            
            return [
                'type' => 'text',
                'message' => "🎉 Khuyến mãi đang diễn ra:\n\n{$promoList}\n\n" .
                           "⚡ Đặt hàng ngay để nhận ưu đãi!"
            ];
        }

        // 11. Delivery information
        if ($this->containsWords($message, ['giao hàng', 'ship', 'vận chuyển', 'phí ship', 'giao tận nơi', 'delivery'])) {
            return [
                'type' => 'text',
                'message' => "🚚 Thông tin giao hàng:\n\n" .
                           "📍 Khu vực nội thành: 10.000đ - 30 phút\n" .
                           "📍 Khu vực ngoại thành: 25.000đ - 45 phút\n" .
                           "📍 Đơn từ 200.000đ: FREESHIP\n\n" .
                           "⏰ Giao hàng từ 8:30 - 20:45 hàng ngày\n\n" .
                           "💡 Tip: Đặt combo để được freeship nhanh hơn!"
            ];
        }

        // 12. Payment methods
        if ($this->containsWords($message, ['thanh toán', 'payment', 'trả tiền', 'momo', 'ví điện tử', 'cod'])) {
            return [
                'type' => 'text',
                'message' => "💳 Phương thức thanh toán:\n\n" .
                           "💵 COD - Thanh toán khi nhận hàng\n" .
                           "📱 MoMo - Ví điện tử\n" .
                           "🏦 Chuyển khoản ngân hàng\n\n" .
                           "✅ Tất cả đều an toàn và bảo mật 100%!"
            ];
        }

        // 13. Contact/support
        if ($this->containsWords($message, ['liên hệ', 'hotline', 'phone', 'số điện thoại', 'gọi điện', 'support'])) {
            return [
                'type' => 'text',
                'message' => "📞 Liên hệ WowBox Shop:\n\n" .
                           "☎️ Hotline: 028.6685.9055\n" .
                           "📱 Hotline: 028.6682.8055\n" .
                           "📧 Email: biz.wowbox@gmail.com\n" .
                           "📍 Địa chỉ: 654 Lương Hữu Khánh, P. Phạm Ngũ Lão, Q.1, TP.HCM\n\n" .
                           "⏰ Làm việc: 8:30 - 20:45 (Thứ 2 - CN)"
            ];
        }

        // 14. Price range inquiry
        if ($this->containsWords($message, ['giá', 'bao nhiêu tiền', 'price', 'rẻ', 'đắt', 'budget'])) {
            $message = "💰 Mức giá tại WowBox:\n\n" .
                      "• Combo tiết kiệm: 50.000đ - 100.000đ\n" .
                      "• Combo trung bình: 100.000đ - 200.000đ\n" .
                      "• Combo cao cấp: 200.000đ+\n\n" .
                      "💡 Bạn muốn xem món trong khoảng giá nào?";
            
            return [
                'type' => 'text',
                'message' => $message
            ];
        }

        // 15. Opening hours
        if ($this->containsWords($message, ['mở cửa', 'đóng cửa', 'giờ', 'hours', 'time'])) {
            return [
                'type' => 'text',
                'message' => "⏰ Giờ hoạt động:\n\n" .
                           "🗓️ Thứ Hai - Chủ Nhật\n" .
                           "🕗 8:30 - 20:45\n\n" .
                           "✨ Chúng tôi phục vụ cả lễ Tết!"
            ];
        }

        // 16. Default response with smart suggestions
        $suggestions = $this->getContextualSuggestions($message, $context);
        
        return [
            'type' => 'text',
            'message' => "🤔 Xin lỗi, tôi chưa hiểu rõ câu hỏi.\n\n" .
                       "Bạn có thể hỏi tôi về:\n\n" .
                       "🍕 Món ăn và thực đơn\n" .
                       "🎁 Khuyến mãi và ưu đãi\n" .
                       "🛒 Giỏ hàng và đặt món\n" .
                       "📦 Theo dõi đơn hàng\n" .
                       "🚚 Giao hàng và thanh toán\n" .
                       "📞 Liên hệ hỗ trợ\n\n" .
                       "Hoặc thử hỏi: \"Gợi ý món ngon\" để tôi tư vấn!",
            'suggestions' => $suggestions
        ];
    }

    // Helper: Get time-based greeting
    private function getTimeBasedGreeting()
    {
        $hour = now()->hour;
        
        if ($hour >= 5 && $hour < 12) {
            return "Chào buổi sáng";
        } elseif ($hour >= 12 && $hour < 18) {
            return "Chào buổi chiều";
        } else {
            return "Chào buổi tối";
        }
    }

    // Helper: Get status text in Vietnamese
    private function getStatusText($status)
    {
        $statuses = [
            'cho_xac_nhan' => '⏳ Chờ xác nhận',
            'da_xac_nhan' => '✅ Đã xác nhận',
            'dang_giao' => '🚚 Đang giao',
            'da_giao' => '✨ Đã giao',
            'da_huy' => '❌ Đã hủy'
        ];
        
        return $statuses[$status] ?? $status;
    }

    // Helper: Smart product recommendations
    private function getSmartRecommendations($context)
    {
        // Get user's order history for personalization
        if (Auth::check()) {
            $userCategories = DonHang::where('ma_tai_khoan', Auth::id())
                ->with('chiTietDonHangs.bienThe.sanPham.danhMuc')
                ->latest('ngay_tao')
                ->take(5)
                ->get()
                ->pluck('chiTietDonHangs.*.bienThe.sanPham.ma_danh_muc')
                ->flatten()
                ->filter()
                ->unique()
                ->values();
            
            if ($userCategories->isNotEmpty()) {
                // Recommend from favorite categories
                $products = SanPham::whereIn('ma_danh_muc', $userCategories)
                    ->with('bienThes')
                    ->inRandomOrder()
                    ->take(6)
                    ->get();
            } else {
                // New user - show popular items
                $products = SanPham::with('bienThes')
                    ->inRandomOrder()
                    ->take(6)
                    ->get();
            }
        } else {
            // Guest - show trending
            $products = SanPham::with('bienThes')
                ->inRandomOrder()
                ->take(6)
                ->get();
        }
        
        return $products->map(function($product) {
            return $this->formatProduct($product);
        });
    }

    // Helper: Quick add to cart
    private function quickAddToCart($productId)
    {
        $product = SanPham::with('bienThes')->find($productId);
        
        if (!$product) {
            return [
                'type' => 'text',
                'message' => "❌ Không tìm thấy sản phẩm này!"
            ];
        }
        
        // Get default variant (first one)
        $variant = $product->bienThes->first();
        
        if (!$variant) {
            return [
                'type' => 'text',
                'message' => "❌ Sản phẩm này hiện không có sẵn!"
            ];
        }
        
        try {
            // Get or create cart
            $gioHang = GioHang::firstOrCreate([
                'ma_tai_khoan' => Auth::id()
            ]);
            
            // Check if variant already in cart
            $chiTiet = ChiTietGioHang::where('ma_gio_hang', $gioHang->ma_gio_hang)
                ->where('ma_bien_the', $variant->ma_bien_the)
                ->first();
            
            if ($chiTiet) {
                $chiTiet->so_luong += 1;
                $chiTiet->save();
            } else {
                ChiTietGioHang::create([
                    'ma_gio_hang' => $gioHang->ma_gio_hang,
                    'ma_san_pham' => $product->ma_san_pham,
                    'ma_bien_the' => $variant->ma_bien_the,
                    'so_luong' => 1
                ]);
            }
            
            $cartCount = $gioHang->chiTietGioHangs->sum('so_luong');
            
            return [
                'type' => 'text',
                'message' => "✅ Đã thêm {$product->ten_san_pham} vào giỏ!\n\n" .
                           "🛒 Giỏ hàng: {$cartCount} món\n\n" .
                           "Bạn muốn tiếp tục mua sắm hay thanh toán?",
                'action' => 'added_to_cart',
                'cartCount' => $cartCount
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'text',
                'message' => "❌ Có lỗi xảy ra khi thêm vào giỏ hàng!"
            ];
        }
    }

    // Helper: Get similar products
    private function getSimilarProducts($searchTerm)
    {
        // Try to find similar products by soundex or partial match
        return SanPham::where(function($query) use ($searchTerm) {
            $words = explode(' ', $searchTerm);
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $query->orWhere('ten_san_pham', 'like', "%{$word}%");
                }
            }
        })
        ->with('bienThes')
        ->take(4)
        ->get()
        ->map(function($product) {
            return $this->formatProduct($product);
        });
    }

    // Helper: Get contextual suggestions
    private function getContextualSuggestions($message, $context)
    {
        $suggestions = [
            'Gợi ý món ngon',
            'Khuyến mãi gì đang có?',
            'Tìm pizza',
            'Có gì hot?'
        ];
        
        return $suggestions;
    }

    // Helper: Format product for response
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
            'url' => route('san-pham.show', $product->ma_san_pham)
        ];
    }

    // Helper: Check if message contains any of the words
    private function containsWords($message, $words)
    {
        foreach ($words as $word) {
            if (str_contains($message, strtolower($word))) {
                return true;
            }
        }
        return false;
    }

    // Helper: Extract search term from message
    private function extractSearchTerm($message)
    {
        $patterns = [
            '/tìm\s+(.+)/i',
            '/tìm kiếm\s+(.+)/i',
            '/có món\s+(.+)/i',
            '/có\s+(.+)\s+không/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                return trim($matches[1]);
            }
        }
        
        return null;
    }
}
