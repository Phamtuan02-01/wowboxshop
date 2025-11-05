<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\DiaChi;
use App\Services\MoMoPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ThanhToanController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('dangnhap')->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
        }

        // Lấy giỏ hàng của người dùng
        $gioHang = GioHang::where('ma_tai_khoan', Auth::id())
                          ->with(['chiTietGioHangs.sanPham', 'chiTietGioHangs.bienThe'])
                          ->first();

        if (!$gioHang || $gioHang->chiTietGioHangs->isEmpty()) {
            return redirect()->route('dat-mon.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Tính tổng tiền với khuyến mãi
        $tongTien = 0;
        $tongTienGoc = 0;
        $tongTietKiem = 0;
        
        foreach ($gioHang->chiTietGioHangs as $chiTiet) {
            $giaGoc = $chiTiet->bienThe->gia;
            $tongTienGoc += $giaGoc * $chiTiet->so_luong;
            
            // Áp dụng khuyến mãi nếu có
            if ($chiTiet->sanPham) {
                $giaKhuyenMai = $chiTiet->sanPham->getDiscountedPriceForVariant($giaGoc);
                $tongTien += $giaKhuyenMai * $chiTiet->so_luong;
                $tongTietKiem += ($giaGoc - $giaKhuyenMai) * $chiTiet->so_luong;
            } else {
                $tongTien += $giaGoc * $chiTiet->so_luong;
            }
        }

        // Phí giao hàng
        $phiGiaoHang = 10000; // 10k cố định

        // Lấy danh sách địa chỉ đã lưu của người dùng
        $diaChiDaLuu = DiaChi::where('ma_tai_khoan', Auth::id())->get();

        // Danh sách cửa hàng
        $cuaHangs = [
            [
                'ten' => 'WOW Box - Quận 1',
                'dia_chi' => '654 Lương Hữu Khánh, Phường Phạm Ngũ Lão, Quận 1, TP.HCM',
                'lat' => 10.7658,
                'lng' => 106.6901,
                'sdt' => '028.6685.9055'
            ],
            [
                'ten' => 'WOW Box - Quận 3',
                'dia_chi' => '123 Võ Văn Tần, Phường 6, Quận 3, TP.HCM',
                'lat' => 10.7756,
                'lng' => 106.6898,
                'sdt' => '028.6685.9056'
            ],
            [
                'ten' => 'WOW Box - Quận 7',
                'dia_chi' => '456 Nguyễn Thị Thập, Phường Tân Phú, Quận 7, TP.HCM', 
                'lat' => 10.7411,
                'lng' => 106.7198,
                'sdt' => '028.6685.9057'
            ]
        ];

        return view('thanh-toan.index', compact('gioHang', 'tongTien', 'tongTienGoc', 'tongTietKiem', 'phiGiaoHang', 'diaChiDaLuu', 'cuaHangs'));
    }

    /**
     * Tìm cửa hàng gần nhất
     */
    public function timCuaHangGanNhat(Request $request)
    {
        $request->validate([
            'dia_chi' => 'required|string|max:255'
        ]);

        $diaChiKhach = $request->dia_chi;
        
        // Danh sách cửa hàng với tọa độ
        $cuaHangs = [
            [
                'ten' => 'WOW Box - Quận 1',
                'dia_chi' => '654 Lương Hữu Khánh, Phường Phạm Ngũ Lão, Quận 1, TP.HCM',
                'lat' => 10.7658,
                'lng' => 106.6901,
                'sdt' => '028.6685.9055',
                'gio_mo' => '8:00',
                'gio_dong' => '22:00'
            ],
            [
                'ten' => 'WOW Box - Quận 3', 
                'dia_chi' => '123 Võ Văn Tần, Phường 6, Quận 3, TP.HCM',
                'lat' => 10.7756,
                'lng' => 106.6898,
                'sdt' => '028.6685.9056',
                'gio_mo' => '8:00',
                'gio_dong' => '22:00'
            ],
            [
                'ten' => 'WOW Box - Quận 7',
                'dia_chi' => '456 Nguyễn Thị Thập, Phường Tân Phú, Quận 7, TP.HCM',
                'lat' => 10.7411,
                'lng' => 106.7198,
                'sdt' => '028.6685.9057',
                'gio_mo' => '8:30',
                'gio_dong' => '21:30'
            ]
        ];

        // Giả lập việc lấy tọa độ từ địa chỉ khách hàng
        // Trong thực tế, bạn có thể sử dụng Google Geocoding API
        $toaDoKhach = $this->layToaDoDiaChiGiaLap($diaChiKhach);
        
        if (!$toaDoKhach) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xác định vị trí của địa chỉ bạn nhập. Vui lòng thử lại.'
            ]);
        }

        // Tính khoảng cách đến các cửa hàng
        $cuaHangGanNhat = null;
        $khoangCachNganNhat = PHP_FLOAT_MAX;

        foreach ($cuaHangs as $cuaHang) {
            $khoangCach = $this->tinhKhoangCach(
                $toaDoKhach['lat'], 
                $toaDoKhach['lng'], 
                $cuaHang['lat'], 
                $cuaHang['lng']
            );
            
            $cuaHang['khoang_cach'] = round($khoangCach, 1);
            
            if ($khoangCach < $khoangCachNganNhat) {
                $khoangCachNganNhat = $khoangCach;
                $cuaHangGanNhat = $cuaHang;
            }
        }

        // Kiểm tra khoảng cách có dưới 20km không
        if ($khoangCachNganNhat <= 20) {
            return response()->json([
                'success' => true,
                'cua_hang' => $cuaHangGanNhat,
                'danh_sach_cua_hangs' => array_filter($cuaHangs, function($ch) {
                    return $ch['khoang_cach'] <= 20;
                })
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không có cửa hàng nào trong bán kính 20km từ địa chỉ của bạn. Vui lòng gọi hotline 1900-xxxx để được hỗ trợ.',
                'cua_hang_gan_nhat' => $cuaHangGanNhat
            ]);
        }
    }

    /**
     * Xử lý đặt hàng
     */
    public function datHang(Request $request)
    {
        $rules = [
            'phuong_thuc_giao_hang' => 'required|in:giao_hang,nhan_tai_cua_hang',
            'phuong_thuc_thanh_toan' => 'required|in:momo,cod,demo',
            'ghi_chu' => 'nullable|string|max:500',
            'ma_khuyen_mai' => 'nullable|exists:khuyen_mai,ma_khuyen_mai',
            'giam_gia_khuyen_mai' => 'nullable|numeric|min:0'
        ];

        if ($request->phuong_thuc_giao_hang == 'giao_hang') {
            $rules = array_merge($rules, [
                'ho_ten' => 'required|string|max:100',
                'so_dien_thoai' => 'required|string|max:15',
                'dia_chi' => 'required|string|max:255',
                'tinh_thanh_pho' => 'required|string|max:100'
            ]);
        } else {
            $rules['cua_hang_chon'] = 'required|string';
        }

        $request->validate($rules);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để tiếp tục.'
            ]);
        }

        // Lấy giỏ hàng
        $gioHang = GioHang::where('ma_tai_khoan', Auth::id())
                          ->with(['chiTietGioHangs.sanPham', 'chiTietGioHangs.bienThe'])
                          ->first();

        if (!$gioHang || $gioHang->chiTietGioHangs->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng của bạn đang trống.'
            ]);
        }

        try {
            DB::beginTransaction();

            // Tính tổng tiền với khuyến mãi
            $tongTien = 0;
            foreach ($gioHang->chiTietGioHangs as $chiTiet) {
                $giaGoc = $chiTiet->bienThe->gia;
                
                // Áp dụng khuyến mãi nếu có
                if ($chiTiet->sanPham) {
                    $giaKhuyenMai = $chiTiet->sanPham->getDiscountedPriceForVariant($giaGoc);
                    $tongTien += $giaKhuyenMai * $chiTiet->so_luong;
                } else {
                    $tongTien += $giaGoc * $chiTiet->so_luong;
                }
            }

            // Phí giao hàng
            $phiGiaoHang = $request->phuong_thuc_giao_hang == 'giao_hang' ? 10000 : 0;
            
            // Xử lý mã khuyến mãi (loại fixed - giảm giá cố định cho hóa đơn)
            $giamGiaKhuyenMai = 0;
            $maKhuyenMai = null;
            
            if ($request->filled('ma_khuyen_mai') && $request->filled('giam_gia_khuyen_mai')) {
                $khuyenMai = \App\Models\KhuyenMai::find($request->ma_khuyen_mai);
                
                // Kiểm tra khuyến mãi còn hợp lệ và đủ điều kiện
                if ($khuyenMai && $khuyenMai->canUse(Auth::id())) {
                    // Kiểm tra điều kiện đơn hàng tối thiểu
                    if (!$khuyenMai->don_hang_toi_thieu || $tongTien >= $khuyenMai->don_hang_toi_thieu) {
                        $giamGiaKhuyenMai = $request->giam_gia_khuyen_mai;
                        
                        // Đảm bảo giảm giá không vượt quá tổng tiền
                        if ($giamGiaKhuyenMai > $tongTien) {
                            $giamGiaKhuyenMai = $tongTien;
                        }
                        
                        $maKhuyenMai = $khuyenMai->ma_khuyen_mai;
                    }
                }
            }
            
            $tongThanhToan = $tongTien - $giamGiaKhuyenMai + $phiGiaoHang;

            // Tạo đơn hàng
            $donHang = DonHang::create([
                'ma_tai_khoan' => Auth::id(),
                'ngay_dat_hang' => now(),
                'tong_tien' => $tongThanhToan, // Tổng tiền sau khi trừ khuyến mãi và cộng phí giao hàng
                'phuong_thuc_giao_hang' => $request->phuong_thuc_giao_hang,
                'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
                'trang_thai' => 'cho_xac_nhan',
                'ghi_chu' => $request->ghi_chu,
                'ho_ten' => $request->ho_ten,
                'so_dien_thoai' => $request->so_dien_thoai,
                'dia_chi' => $request->dia_chi,
                'tinh_thanh_pho' => $request->tinh_thanh_pho,
                'cua_hang_nhan' => $request->cua_hang_chon,
                'ma_khuyen_mai' => $maKhuyenMai,
                'giam_gia_khuyen_mai' => $giamGiaKhuyenMai
            ]);

            // Thông tin đã được lưu trong create() ở trên

            // Tạo chi tiết đơn hàng
            foreach ($gioHang->chiTietGioHangs as $chiTiet) {
                ChiTietDonHang::create([
                    'ma_don_hang' => $donHang->ma_don_hang,
                    'ma_san_pham' => $chiTiet->ma_san_pham,
                    'ma_bien_the' => $chiTiet->ma_bien_the,
                    'so_luong' => $chiTiet->so_luong,
                    'gia_tai_thoi_diem_mua' => $chiTiet->bienThe->gia
                ]);

                // Giảm số lượng tồn kho
                $chiTiet->bienThe->decrement('so_luong_ton', $chiTiet->so_luong);
            }

            // Lưu lịch sử sử dụng khuyến mãi nếu có
            if ($maKhuyenMai && $giamGiaKhuyenMai > 0) {
                \App\Models\LichSuKhuyenMai::create([
                    'ma_khuyen_mai' => $maKhuyenMai,
                    'ma_tai_khoan' => Auth::id(),
                    'ma_don_hang' => $donHang->ma_don_hang,
                    'gia_tri_giam' => $giamGiaKhuyenMai,
                    'ngay_su_dung' => now()
                ]);
                
                // Tăng số lượng sử dụng của khuyến mãi
                $khuyenMai->increment('so_luong_su_dung');
            }
            
            // Xóa giỏ hàng
            $gioHang->chiTietGioHangs()->delete();
            $gioHang->delete();

            DB::commit();

            // Xử lý theo phương thức thanh toán
            if ($request->phuong_thuc_thanh_toan == 'momo') {
                // Xử lý thanh toán MoMo thật
                $momoService = new MoMoPaymentService();
                $amount = MoMoPaymentService::formatAmount($donHang->tong_tien);
                $orderInfo = MoMoPaymentService::createOrderInfo($donHang->ma_don_hang, $donHang->ho_ten);
                
                $paymentResult = $momoService->createPayment(
                    $donHang->ma_don_hang,
                    $amount,
                    $orderInfo
                );

                if (!$paymentResult['success']) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => $paymentResult['message']
                    ]);
                }

                // Lưu thông tin thanh toán
                $donHang->update([
                    'ma_thanh_toan' => $paymentResult['requestId'],
                    'url_thanh_toan' => $paymentResult['payUrl']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công! Đang chuyển hướng đến trang thanh toán MoMo...',
                    'ma_don_hang' => $donHang->ma_don_hang,
                    'payment_url' => $paymentResult['payUrl']
                ]);
            
            } elseif ($request->phuong_thuc_thanh_toan == 'cod') {
                // Thanh toán COD - cập nhật trạng thái chờ xác nhận
                $donHang->update([
                    'trang_thai' => 'cho_xac_nhan'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.',
                    'ma_don_hang' => $donHang->ma_don_hang,
                    'redirect_url' => route('thanh-toan.thanh-cong', $donHang->ma_don_hang)
                ]);
            
            } elseif ($request->phuong_thuc_thanh_toan == 'demo') {
                // Thanh toán demo - chuyển đến trang demo
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công! Chuyển đến trang thanh toán demo...',
                    'ma_don_hang' => $donHang->ma_don_hang,
                    'demo_url' => route('thanh-toan.demo', $donHang->ma_don_hang)
                ]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Giả lập lấy tọa độ từ địa chỉ
     */
    private function layToaDoDiaChiGiaLap($diaChi)
    {
        // Giả lập một số địa chỉ phổ biến ở TP.HCM
        $diaChiMau = [
            'quan 1' => ['lat' => 10.7769, 'lng' => 106.7009],
            'quan 2' => ['lat' => 10.7545, 'lng' => 106.7297],
            'quan 3' => ['lat' => 10.7756, 'lng' => 106.6898],
            'quan 4' => ['lat' => 10.7576, 'lng' => 106.7036],
            'quan 5' => ['lat' => 10.7598, 'lng' => 106.6758],
            'quan 6' => ['lat' => 10.7468, 'lng' => 106.6345],
            'quan 7' => ['lat' => 10.7411, 'lng' => 106.7198],
            'quan 8' => ['lat' => 10.7327, 'lng' => 106.6762],
            'quan 9' => ['lat' => 10.8006, 'lng' => 106.7583],
            'quan 10' => ['lat' => 10.7742, 'lng' => 106.6680],
            'quan 11' => ['lat' => 10.7626, 'lng' => 106.6519],
            'quan 12' => ['lat' => 10.8580, 'lng' => 106.6702],
            'binh thanh' => ['lat' => 10.8012, 'lng' => 106.7102],
            'go vap' => ['lat' => 10.8376, 'lng' => 106.6669],
            'phu nhuan' => ['lat' => 10.7980, 'lng' => 106.6834],
            'tan binh' => ['lat' => 10.8006, 'lng' => 106.6534],
            'tan phu' => ['lat' => 10.7881, 'lng' => 106.6256],
            'thu duc' => ['lat' => 10.8516, 'lng' => 106.7570]
        ];

        $diaChiLower = mb_strtolower($diaChi, 'UTF-8');
        
        foreach ($diaChiMau as $key => $toaDo) {
            if (strpos($diaChiLower, $key) !== false) {
                return $toaDo;
            }
        }

        // Mặc định trả về tọa độ trung tâm TP.HCM
        return ['lat' => 10.7769, 'lng' => 106.7009];
    }

    /**
     * Tính khoảng cách giữa 2 điểm (km)
     */
    private function tinhKhoangCach($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Bán kính Trái Đất tính bằng km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Xử lý callback return từ MoMo (khi user hoàn thành thanh toán)
     */
    public function momoReturn(Request $request)
    {
        try {
            $momoService = new MoMoPaymentService();
            $callbackData = $momoService->parseCallbackData($request);
            
            Log::info('MoMo Return Callback', $callbackData);
            
            // Verify signature
            if (!$momoService->verifyCallback($callbackData)) {
                Log::error('MoMo Return: Invalid signature');
                return redirect()->route('dat-mon.index')->with('error', 'Thanh toán không hợp lệ.');
            }
            
            $donHang = DonHang::where('ma_don_hang', $callbackData['orderId'])->first();
            
            if (!$donHang) {
                Log::error('MoMo Return: Order not found', ['orderId' => $callbackData['orderId']]);
                return redirect()->route('dat-mon.index')->with('error', 'Không tìm thấy đơn hàng.');
            }
            
            // Cập nhật trạng thái đơn hàng
            if ($callbackData['resultCode'] == 0) {
                // Thanh toán thành công
                $donHang->update([
                    'trang_thai' => 'da_thanh_toan'
                ]);
                
                return redirect()->route('thanh-toan.thanh-cong', $donHang->ma_don_hang)
                                ->with('payment_success', true)
                                ->with('message', 'Thanh toán thành công!');
            } else {
                // Thanh toán thất bại
                $donHang->update([
                    'trang_thai' => 'da_huy'
                ]);
                
                return redirect()->route('dat-mon.index')
                                ->with('error', 'Thanh toán thất bại: ' . $callbackData['message']);
            }
            
        } catch (\Exception $e) {
            Log::error('MoMo Return Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return redirect()->route('dat-mon.index')->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán.');
        }
    }
    
    /**
     * Xử lý IPN (Instant Payment Notification) từ MoMo
     */
    public function momoNotify(Request $request)
    {
        try {
            $momoService = new MoMoPaymentService();
            $callbackData = $momoService->parseCallbackData($request);
            
            Log::info('MoMo IPN Callback', $callbackData);
            
            // Verify signature
            if (!$momoService->verifyCallback($callbackData)) {
                Log::error('MoMo IPN: Invalid signature');
                return response()->json(['message' => 'Invalid signature'], 400);
            }
            
            $donHang = DonHang::where('ma_don_hang', $callbackData['orderId'])->first();
            
            if (!$donHang) {
                Log::error('MoMo IPN: Order not found', ['orderId' => $callbackData['orderId']]);
                return response()->json(['message' => 'Order not found'], 404);
            }
            
            // Cập nhật trạng thái đơn hàng
            if ($callbackData['resultCode'] == 0) {
                // Thanh toán thành công
                $donHang->update([
                    'trang_thai' => 'da_thanh_toan'
                ]);
                
                Log::info('Order payment completed', ['orderId' => $callbackData['orderId']]);
            } else {
                // Thanh toán thất bại
                $donHang->update([
                    'trang_thai' => 'da_huy'
                ]);
                
                Log::warning('Order payment failed', [
                    'orderId' => $callbackData['orderId'],
                    'resultCode' => $callbackData['resultCode'],
                    'message' => $callbackData['message']
                ]);
            }
            
            return response()->json(['message' => 'Success']);
            
        } catch (\Exception $e) {
            Log::error('MoMo IPN Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Trang thanh toán demo
     */
    public function demo($maDonHang)
    {
        $donHang = DonHang::where('ma_don_hang', $maDonHang)
                          ->where('ma_tai_khoan', Auth::id())
                          ->with(['chiTietDonHangs.sanPham', 'chiTietDonHangs.bienThe'])
                          ->first();

        if (!$donHang) {
            return redirect()->route('dat-mon.index')->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('thanh-toan.demo', compact('donHang'));
    }

    /**
     * Xử lý thanh toán demo hoàn thành
     */
    public function demoComplete(Request $request, $maDonHang)
    {
        $donHang = DonHang::where('ma_don_hang', $maDonHang)
                          ->where('ma_tai_khoan', Auth::id())
                          ->first();

        if (!$donHang) {
            return redirect()->route('dat-mon.index')->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Cập nhật trạng thái đơn hàng
        $donHang->update([
            'trang_thai' => 'da_thanh_toan'
        ]);

        return redirect()->route('thanh-toan.thanh-cong', $maDonHang)
                        ->with('payment_success', true)
                        ->with('message', 'Thanh toán demo thành công!');
    }

    /**
     * Trang thành công
     */
    public function success($maDonHang)
    {
        $donHang = DonHang::where('ma_don_hang', $maDonHang)
                          ->where('ma_tai_khoan', Auth::id())
                          ->with(['chiTietDonHangs.sanPham', 'chiTietDonHangs.bienThe'])
                          ->first();

        if (!$donHang) {
            return redirect()->route('dat-mon.index')->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('thanh-toan.success', compact('donHang'));
    }
}