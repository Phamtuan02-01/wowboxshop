# 🤖 WowBox AI Chatbot - Hướng Dẫn Sử Dụng

## 📋 Tổng Quan

WowBox AI Chatbot là trợ lý ảo thông minh được tích hợp vào website WowBox Shop, giúp khách hàng tìm kiếm sản phẩm, đặt hàng và nhận tư vấn 24/7.

## ✨ Tính Năng Nâng Cấp V2

### 🧠 **AI Context Memory**
- Ghi nhớ lịch sử 5 tin nhắn gần nhất
- Hiểu ngữ cảnh hội thoại
- Đưa ra câu trả lời phù hợp với cuộc trò chuyện

### 🎯 **Smart Recommendations**
- Gợi ý sản phẩm dựa trên lịch sử đặt hàng
- Phân tích sở thích người dùng
- Đề xuất món ăn cá nhân hóa

### 🛒 **Quick Add to Cart**
- Thêm sản phẩm vào giỏ ngay từ chat
- Không cần rời khỏi cửa sổ chat
- Cập nhật số lượng giỏ hàng realtime

### 📦 **Order Tracking**
- Tra cứu đơn hàng nhanh chóng
- Xem trạng thái đơn hàng realtime
- Lịch sử 3 đơn gần nhất

### 🎤 **Voice Input**
- Nhập lệnh bằng giọng nói
- Hỗ trợ tiếng Việt
- Chuyển đổi giọng nói thành text tự động

### 🎨 **Enhanced UI/UX**
- Giao diện gradient đẹp mắt
- Animation mượt mà
- Dark mode support
- Responsive trên mọi thiết bị
- Emoji picker tích hợp

## 🎯 Cách Sử Dụng

### 1. **Mở Chatbot**
- Click vào nút tròn màu tím ở góc phải dưới
- Hoặc click vào chat preview khi minimize

### 2. **Gửi Tin Nhắn**
- Nhập text vào ô input
- Nhấn Enter hoặc nút gửi
- Hoặc click nút 🎤 để nói

### 3. **Quick Replies**
- Click vào các gợi ý nhanh
- Chips động thay đổi theo ngữ cảnh

### 4. **Thêm Giỏ Hàng**
- Xem sản phẩm trong chat
- Click "Thêm giỏ" trực tiếp
- Hoặc nói: "Thêm sản phẩm [ID]"

## 💬 Các Lệnh Chatbot

### **Chào Hỏi**
```
- "Xin chào"
- "Hi"
- "Hello"
```
→ Chatbot chào và giới thiệu khả năng

### **Xem Thực Đơn**
```
- "Có món gì?"
- "Thực đơn"
- "Menu"
- "Có gì ngon?"
```
→ Hiển thị danh sách danh mục

### **Gợi Ý Món**
```
- "Gợi ý món ngon"
- "Nên ăn gì?"
- "Giới thiệu món"
- "Đề xuất"
```
→ AI gợi ý dựa trên sở thích (nếu đã đăng nhập) hoặc món hot

### **Tìm Theo Danh Mục**
```
- "Pizza"
- "Burger"
- "Gà rán"
- "Cơm"
```
→ Hiển thị sản phẩm trong danh mục đó

### **Món Hot/Phổ Biến**
```
- "Món hot"
- "Bán chạy"
- "Phổ biến"
- "Trending"
```
→ Hiển thị top món bán chạy

### **Tìm Sản Phẩm**
```
- "Tìm pizza hawaii"
- "Có burger phô mai không?"
- "Search combo"
```
→ Tìm kiếm sản phẩm theo tên

### **Thêm Vào Giỏ**
```
- "Thêm sản phẩm 5"
- "Mua món 12"
- "Đặt [số ID]"
```
→ Thêm sản phẩm vào giỏ nhanh (cần đăng nhập)

### **Tra Đơn Hàng**
```
- "Đơn hàng của tôi"
- "Theo dõi đơn"
- "Kiểm tra đơn"
- "Order"
```
→ Xem 3 đơn hàng gần nhất (cần đăng nhập)

### **Khuyến Mãi**
```
- "Khuyến mãi"
- "Giảm giá"
- "Ưu đãi"
- "Voucher"
- "Sale"
```
→ Xem chương trình khuyến mãi đang diễn ra

### **Giao Hàng**
```
- "Giao hàng"
- "Ship"
- "Phí ship"
- "Vận chuyển"
```
→ Thông tin về giao hàng và phí ship

### **Thanh Toán**
```
- "Thanh toán"
- "Payment"
- "Trả tiền"
- "MoMo"
```
→ Các phương thức thanh toán

### **Giá Cả**
```
- "Giá bao nhiêu?"
- "Mức giá"
- "Price"
- "Rẻ"
```
→ Thông tin về khoảng giá

### **Giờ Mở Cửa**
```
- "Mở cửa khi nào?"
- "Giờ làm việc"
- "Hours"
```
→ Thông tin giờ hoạt động

### **Liên Hệ**
```
- "Liên hệ"
- "Hotline"
- "Số điện thoại"
- "Support"
```
→ Thông tin liên hệ

## 🔧 Tính Năng Kỹ Thuật

### **Context Memory**
```javascript
chatContext = [
  {
    role: 'user',
    message: 'Tìm pizza',
    timestamp: '2025-11-06T10:30:00'
  },
  {
    role: 'bot',
    message: 'Đây là các món pizza...',
    type: 'products',
    timestamp: '2025-11-06T10:30:02'
  }
]
```
- Lưu 5 tin nhắn gần nhất
- Gửi kèm context trong mỗi request
- AI phân tích để đưa ra câu trả lời phù hợp

### **Smart Recommendations Algorithm**
1. Kiểm tra user đã đăng nhập chưa
2. Nếu có → lấy lịch sử đơn hàng
3. Phân tích danh mục yêu thích
4. Gợi ý sản phẩm từ danh mục đó
5. Nếu không → hiển thị món trending

### **Voice Recognition**
```javascript
// Web Speech API
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
recognition = new SpeechRecognition();
recognition.lang = 'vi-VN';
```
- Sử dụng Web Speech API
- Hỗ trợ tiếng Việt
- Chuyển đổi realtime

### **Session Management**
- Mỗi session có ID duy nhất
- Lưu context trong session
- Reset khi clear history

## 🎨 Customization

### **Thay Đổi Màu Chủ Đạo**
```css
/* File: chatbot-v2.blade.php */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Thay bằng màu của bạn */
background: linear-gradient(135deg, #2E7D32 0%, #388E3C 100%);
```

### **Thay Đổi Avatar**
```html
<!-- Thay icon robot bằng ảnh -->
<img src="{{ asset('images/bot-avatar.png') }}" alt="Bot">
```

### **Thêm Lệnh Mới**
```php
// File: ChatbotControllerV2.php
if ($this->containsWords($message, ['từ khóa mới'])) {
    return [
        'type' => 'text',
        'message' => 'Câu trả lời của bạn'
    ];
}
```

## 📊 Analytics

### **Tracking Events**
```javascript
// Gửi event khi user tương tác
gtag('event', 'chatbot_interaction', {
  'event_category': 'Chatbot',
  'event_label': message,
  'value': 1
});
```

### **Metrics Quan Tâm**
- Số lượng tin nhắn
- Tỷ lệ chuyển đổi (chat → đơn hàng)
- Từ khóa phổ biến
- Thời gian phản hồi trung bình

## 🔐 Bảo Mật

### **CSRF Protection**
```javascript
headers: {
    'X-CSRF-TOKEN': '{{ csrf_token() }}'
}
```

### **Rate Limiting**
Nên thêm middleware throttle:
```php
Route::post('/chatbot/message', ...)
    ->middleware('throttle:60,1'); // 60 requests/phút
```

### **Input Validation**
```php
$request->validate([
    'message' => 'required|string|max:500',
    'context' => 'array|max:5',
    'sessionId' => 'required|string'
]);
```

## 🚀 Performance

### **Optimization Tips**
1. **Cache Categories**: Cache danh mục 1 giờ
2. **Lazy Load Products**: Chỉ load 6 sản phẩm mỗi lần
3. **Compress Images**: Tối ưu ảnh sản phẩm
4. **Debounce Typing**: Delay 1s trước khi hiển thị typing
5. **Virtual Scrolling**: Với chat history dài

### **Caching Example**
```php
$categories = Cache::remember('chatbot_categories', 3600, function() {
    return DanhMuc::all();
});
```

## 🐛 Troubleshooting

### **Chatbot Không Hiển Thị**
- Kiểm tra file `chatbot-v2.blade.php` đã include vào layout chưa
- Clear cache: `php artisan view:clear`

### **Voice Input Không Hoạt Động**
- Chỉ hoạt động trên HTTPS
- Kiểm tra quyền microphone trong browser
- Chỉ hỗ trợ Chrome/Edge

### **Quick Add to Cart Lỗi**
- Kiểm tra user đã đăng nhập
- Kiểm tra product ID có tồn tại
- Xem log: `storage/logs/laravel.log`

### **Context Không Lưu**
- Kiểm tra session configuration
- Clear browser cache

## 📱 Mobile Support

### **Responsive Design**
- Full-screen trên mobile
- Touch-friendly buttons
- Swipe gestures support
- Auto-hide keyboard khi gửi

### **PWA Ready**
Có thể thêm vào manifest.json:
```json
{
  "name": "WowBox AI Chat",
  "short_name": "WowBox Chat",
  "start_url": "/?chatbot=true"
}
```

## 🌟 Best Practices

### **Cho Người Dùng**
1. Hỏi câu hỏi ngắn gọn
2. Sử dụng quick replies
3. Dùng voice input khi di chuyển
4. Clear history định kỳ

### **Cho Developer**
1. Thường xuyên update keyword patterns
2. Monitor chatbot analytics
3. A/B testing câu trả lời
4. Collect user feedback

## 📈 Future Enhancements

### **Version 3.0 Ideas**
- [ ] OpenAI GPT Integration
- [ ] Multi-language support
- [ ] Image recognition (gửi ảnh món ăn)
- [ ] Booking table feature
- [ ] Loyalty points integration
- [ ] Share chat to social
- [ ] Export chat history
- [ ] Chatbot personality customization

## 🤝 Support

Nếu gặp vấn đề:
- Email: support@wowbox.com
- Hotline: 028.6685.9055
- GitHub Issues: [Repository Link]

## 📄 License

Copyright © 2025 WowBox Shop. All rights reserved.

---

**Version:** 2.0.0  
**Last Updated:** November 6, 2025  
**Author:** WowBox Development Team
