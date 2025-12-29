-- WowBoxShop Database Seed Data
-- Generated for Laravel migration date: 2025-10-09

SET FOREIGN_KEY_CHECKS=0;

-- Clean existing data
TRUNCATE TABLE `lich_su_khuyen_mai`;
TRUNCATE TABLE `khuyen_mai_san_pham`;
TRUNCATE TABLE `khuyen_mai_danh_muc`;
TRUNCATE TABLE `chi_tiet_don_hang`;
TRUNCATE TABLE `don_hang`;
TRUNCATE TABLE `chi_tiet_gio_hang`;
TRUNCATE TABLE `gio_hang`;
TRUNCATE TABLE `khuyen_mai`;
TRUNCATE TABLE `bien_the_san_pham`;
TRUNCATE TABLE `san_pham`;
TRUNCATE TABLE `danh_gia`;
TRUNCATE TABLE `dia_chi`;
TRUNCATE TABLE `tai_khoan`;
TRUNCATE TABLE `danh_muc`;
TRUNCATE TABLE `vai_tro`;

-- Vai tro
INSERT INTO `vai_tro` (`ma_vai_tro`, `ten_vai_tro`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(2, 'Khach hang', '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(3, 'Quan ly', '2025-10-09 00:00:00', '2025-10-09 00:00:00');

-- Danh muc
INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `ma_danh_muc_cha`, `created_at`, `updated_at`) VALUES
(5, 'Salad Truyền Thống', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(6, 'Ngũ cốc', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(7, 'Salad hàng ngày', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(8, 'Salad đặc trưng', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(9, 'Salad cao cấp', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(10, 'Món ăn thêm', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(11, 'Tinh Bột', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(12, 'Phô Mai', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(13, 'Chất Đạm', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(14, 'Rau củ', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(15, 'Nước sốt', NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00');

-- Tai khoan
INSERT INTO `tai_khoan` (`ma_tai_khoan`, `ten_dang_nhap`, `email`, `ho_ten`, `so_dien_thoai`, `mat_khau_hash`, `ma_vai_tro`, `ma_dia_chi_mac_dinh`, `ngay_tao`, `ngay_cap_nhat`, `deleted_at`) VALUES
(1, 'admin', 'admin@wowboxshop.com', 'Administrator', '0123456789', 0x243279243130245a38524d697753792f2f36766141385153366e565165416230506d5557746669386e787958316e6d375630424855796f2f38655532, 1, NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00', NULL),
(2, 'quanly1', 'quanly@wowboxshop.com', 'Quản Lý Hệ Thống', '0987654321', 0x24327924313024576f504f647263714c6a654f4a37386d4148364e6b2e6a53534d675542617674474c6a626e564371714c4a763366344c622f357775, 3, NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00', NULL),
(3, 'khachhang1', 'nguyenvana@gmail.com', 'Nguyễn Văn A', '0901234567', 0x24327924313024784a6c426c794c4969595738564e744e6f375333427555546a6b30527542592f476e32506b633932512e7854736876766550494a43, 2, NULL, '2025-10-09 00:00:00', '2025-10-09 00:00:00', NULL),
(4, 'khachhang2', 'tranthib@gmail.com', 'Trần Thị B', '0912345678', 0x243279243130247354646c58396f6261514a694563413339445964544f73462e2f44562f4f51765878694963703138646d6d574b6c384b3368443857, 2, NULL, '2025-10-09 00:00:00', '2025-11-14 08:43:45', '2025-11-14 08:43:45'),
(5, 'khachhang3', 'lethic@gmail.com', 'Lê Thị C', '0923456789', 0x243279243130246149484c664344446858323639645a714648343064753979424b5867645271525967344f4c444b35436364587061454151416e732e, 2, NULL, '2025-10-09 00:00:00', '2025-11-14 08:44:05', '2025-11-14 08:44:05'),
(6, 'tuan', 'tuan@gmail.com', 'Phạm Minh Tuấn', '09127253739', 0x2432792431302450354d38357646795a4f506b6d73446d4e527456352e6c54636b33596a4a71333479346e784b637361386d6c7169686c5935765679, 2, NULL, '2025-11-07 08:13:15', '2025-11-07 08:13:58', NULL),
(7, 'phongngo123', 'phongha@gmail.com', 'Hà Phong', '09834833333', 0x24327924313024694538346c742f336c466f4b6932457076554746786552744358576c6e734932797858485350436e50413677355167565035414f4b, 2, NULL, '2025-11-08 04:46:24', '2025-11-08 04:46:24', NULL);

-- San pham (products and ingredients)
INSERT INTO `san_pham` (`ma_san_pham`, `ten_san_pham`, `ma_danh_muc`, `loai_san_pham`, `mo_ta`, `gia`, `gia_khuyen_mai`, `trang_thai`, `hinh_anh`, `hinh_anh_phu`, `thuong_hieu`, `xuat_xu`, `luot_xem`, `diem_danh_gia_trung_binh`, `so_luot_danh_gia`, `hinh_anh_url`, `la_noi_bat`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(9, 'Salad bò tẩm tiêu', 5, 'product', 'Xà lách trộn\r\nỚt chuông\r\nDưa leo\r\nRau mầm cải ngọt\r\nCà chua bi\r\nCủ cải đỏ\r\nÔliu đen\r\nBò tẩm tiêu', 92000.00, NULL, 1, '1762269966_690a1b0ea7c9b.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 1, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(10, 'Đậu chickpea', 6, 'ingredient', NULL, 20000.00, NULL, 1, '1762278602_690a3cca6a819.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(11, 'Salad  Phô Mai feta', 5, 'product', 'Xà lách trộn\r\nDưa leo\r\nHành tây\r\nCà chua bi\r\nCà rốt\r\nÔliu đen\r\nBơ quả\r\nPhô mai Feta', 89000.00, NULL, 1, '1762325813_690af53504216.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(12, 'salad ức gà áp chảo', 5, 'product', 'Xà lách Romaine\r\nBánh mì giòn\r\nÔliu đen\r\nTrứng\r\nPhô mai parmesan\r\nỨc gà áp chảo', 92000.00, NULL, 1, '1762325904_690af5906efb0.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(13, 'Salad Cá Ngừ Ngâm Dầu', 5, 'product', 'Xà lách trộn\r\nĐậu que\r\nKhoai tây nướng\r\nTrứng\r\nCà chua bi\r\nỚt chuông\r\nÔliu đen\r\nCá ngừ ngâm dầu', 82000.00, NULL, 1, '1762325973_690af5d59d95c.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(14, 'Salad Ức Gà  Áp Chảo (cobb)', 5, 'product', 'xà lách romaine\r\ndưa leo\r\ncà chua bi\r\ntrứng\r\nBơ quả\r\nBa rọi xông khói\r\nỨc gà áp chảo', 96000.00, NULL, 1, '1762326183_690af6a7a3822.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(15, 'Salad Gà Xé', 7, 'product', 'Xà lách trộn\r\nCà chua bi\r\nKhoai tây\r\nDưa leo\r\nGà Xé', 53000.00, NULL, 1, '1762326309_690af7255522b.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(16, 'Salad Basa Áp Chảo', 7, 'product', 'Xà lách trộn\r\nỚt chuông\r\nRau mầm cải ngọt\r\nCơm giấm mè Nhật\r\nBasa Áp Chảo', 51000.00, NULL, 1, '1762326378_690af76a31895.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(17, 'Salad Nấm Đông Cô  (món Chay)', 7, 'product', 'Xà lách trộn\r\nDưa leo\r\nCơm mè\r\nCà rốt\r\nSườn Chay\r\nNấm Đùi Gà', 51000.00, NULL, 1, '1762326491_690af7db310b6.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(18, 'Salad Thăn Heo Đút Lò', 7, 'product', 'Xà lách trộn\r\nKhoai tây luộc\r\nCà chua\r\nHành tây\r\nThăn Heo Đút Lò', 55000.00, NULL, 1, '1762326544_690af8109a2d1.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(19, 'SALAD Cá Ngừ Tẩm Mè', 8, 'product', 'Xà lách romaine\r\nRau hoả tiễn\r\nỚt chuông\r\nCủ dền\r\nBắp ngọt\r\nBánh mì giòn\r\nCá ngừ tẩm mè', 92000.00, NULL, 1, '1762326691_690af8a3875d8.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(20, 'SALAD Bò Tẩm Tiêu', 8, 'product', 'Xà lách trộn\r\nCần tây\r\nCà chua bi\r\nỚt chuông\r\nĐậu que\r\nCơm tẩm mè\r\nBò tẩm tiêu', 89000.00, NULL, 1, '1762326787_690af90395754.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(21, 'SALAD Cá Hồi Xông Khói', 8, 'product', 'Xà lách trộn\r\nDưa leo\r\nRau mầm \r\nCủ dền\r\nÔliu đen\r\nNui xoăn\r\nMăng tây\r\nCá hồi xông khói', 99000.00, NULL, 1, '1762326854_690af9467c945.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(22, 'SALAD Tôm Tẩm Cay', 8, 'product', 'Xà lách trộn\r\nĐậu cúc\r\nRau mầm\r\nCà chua bi\r\nĐậu đỏ\r\nBí đỏ\r\nHành tây\r\nÔliu đen\r\nTôm tẩm cay', 105000.00, NULL, 1, '1762326913_690af98127d92.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 1, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(23, 'Salad Gà Ướp Quế', 9, 'product', 'Cải xoăn\r\nDưa hấu nướng\r\nHành tây\r\nÔliu đen\r\nHạnh nhân\r\nPhô mai Feta\r\nGà ướp quế', 116000.00, NULL, 1, '1762327096_690afa383d60f.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(24, 'salad Bò, Tôm và Nấm', 9, 'product', 'Cải xoăn\r\nKhoai tây luộc\r\nDứa nướng\r\nBò tẩm tiêu\r\nTôm paprika\r\nNấm đùi gà\r\nBơ quả', 169000.00, NULL, 1, '1762327151_690afa6fa05c4.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(25, 'Salad Ức Vịt Xông Khói', 9, 'product', 'Xà lách trộn\r\nCà rốt\r\nHành tây\r\nBắp ngọt\r\nỨc vịt xông khói\r\nHạt diêm mạch \r\nBơ quả\r\nHành caramen', 138000.00, NULL, 1, '1762327226_690afaba858a8.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(26, 'Salad Cá Hồi Phi Lê', 9, 'product', 'Xà lách trộn\r\nRau mầm\r\nĐậu que\r\nBông cải xanh\r\nCá hồi phi lê\r\nHạt diêm mạch\r\nRau bó xôi baby', 185000.00, NULL, 1, '1762327278_690afaee6a09f.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(27, 'Cánh Gà BBQ', 10, 'product', 'Ngọt dịu, thoang thoảng mùi khói, dính trên đầu ngón tay, ngon đến miếng cuối cùng.\r\nDùng kèm:\r\nSốt BBQ', 55000.00, NULL, 1, '1762327396_690afb64b7f57.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(28, 'Phô Mai Mozzarella Chiên Xù', 10, 'product', 'Bên trong lớp vỏ vàng ruộm là nhân phô mai Mozzarella nóng chảy, thơm mềm, không thể bỏ qua.', 55000.00, NULL, 1, '1762327589_690afc25dbf0b.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(29, 'Khoai Tây Cajun', 10, 'product', 'Khoai tây lắc cajun giòn cay, dùng kèm với tương cà.\r\nNguyên Liệu: khoai tây Bột cajun', 48000.00, NULL, 1, '1762327657_690afc69a7c35.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(30, 'Bánh Diêm Mạch', 10, 'product', 'Ngọt dịu, thoang thoảng mùi khói, dính trên đầu ngón tay, ngon đến miếng cuối cùng.', 60000.00, NULL, 1, '1762327700_690afc94a24a3.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(31, 'Đậu đỏ', 6, 'ingredient', NULL, 5000.00, NULL, 1, '1762601503_690f2a1f7c5f4.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(32, 'Bắp ngọt', 6, 'ingredient', NULL, 5000.00, NULL, 1, '1762601530_690f2a3a9863a.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(33, 'Bánh diêm mạch', 6, 'ingredient', NULL, 20000.00, NULL, 1, '1762601571_690f2a6341913.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(34, 'Hạnh nhân', 6, 'ingredient', NULL, 22000.00, NULL, 1, '1762601611_690f2a8b0a582.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(35, 'Diêm mạch (quinoa)', 6, 'ingredient', NULL, 24000.00, NULL, 1, '1762601639_690f2aa77a5ee.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(36, 'Khoai tây  luộc', 11, 'ingredient', NULL, 30000.00, NULL, 1, '1762601681_690f2ad10c3fe.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(37, 'Bánh mì giòn', 11, 'ingredient', NULL, 18000.00, NULL, 1, '1762601711_690f2aefbde20.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(38, 'Bánh mì bơ tỏi', 11, 'ingredient', NULL, 40000.00, NULL, 1, '1762601753_690f2b194b7be.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(39, 'Nui', 11, 'ingredient', NULL, 15000.00, NULL, 1, '1762601777_690f2b31603dc.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(40, 'Khoai tây chiên', 11, 'ingredient', NULL, 25000.00, NULL, 1, '1762601803_690f2b4b8de17.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(41, 'Cơm mè', 11, 'ingredient', NULL, 10000.00, NULL, 1, '1762601828_690f2b649986c.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(42, 'Phô mai feta', 12, 'ingredient', NULL, 36000.00, NULL, 1, '1762601880_690f2b98deaf6.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(43, 'Phô mai Parmesan', 12, 'ingredient', NULL, 70000.00, NULL, 1, '1762601920_690f2bc025912.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(44, 'trứng', 13, 'ingredient', NULL, 7000.00, NULL, 1, '1763205193_691860494df3a.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(45, 'Đậu hũ', 13, 'ingredient', NULL, 15000.00, NULL, 1, '1763205223_691860675f229.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(46, 'cá hồi phi lê', 13, 'ingredient', NULL, 62000.00, NULL, 1, '1763205257_69186089d9320.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(47, 'tôm paprika', 13, 'ingredient', NULL, 65000.00, NULL, 1, '1763205303_691860b75890e.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(48, 'bò tẩm tiêu', 13, 'ingredient', NULL, 80000.00, NULL, 1, '1763205353_691860e959492.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(49, 'cá ngừ tẩm mè', 13, 'ingredient', NULL, 50000.00, NULL, 1, '1763205389_6918610df01f2.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(50, 'Ức vịt  xông khói', 13, 'ingredient', NULL, 50000.00, NULL, 1, '1763205428_691861349a9b7.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(51, 'cá hồi xông khói', 13, 'ingredient', NULL, 90000.00, NULL, 1, '1763205471_6918615f88633.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(52, 'ba rọi xông khói', 13, 'ingredient', NULL, 98000.00, NULL, 1, '1763205530_6918619a0839b.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(53, 'thanh cua', 13, 'ingredient', NULL, 6000.00, NULL, 1, '1763205554_691861b2d83f8.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(54, 'cá ngừ ngâm dầu', 13, 'ingredient', NULL, 65000.00, NULL, 1, '1763205583_691861cfbccad.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(55, 'thịt nguội', 13, 'ingredient', NULL, 45000.00, NULL, 1, '1763205628_691861fcaba3c.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(56, 'ức gà áp chảo', 13, 'ingredient', NULL, 10000.00, NULL, 1, '1763205672_69186228239dd.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(57, 'ức gà tẩm quế', 13, 'ingredient', NULL, 15000.00, NULL, 1, '1763205708_6918624cdf625.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(58, 'xà lách  trộn', 14, 'ingredient', NULL, 5000.00, NULL, 1, '1763205869_691862ed1fbd7.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(59, 'romaine', 14, 'ingredient', NULL, 5000.00, NULL, 1, '1763205926_69186326a6bb8.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(60, 'củ dền', 14, 'ingredient', NULL, 3000.00, NULL, 1, '1763205950_6918633e15eef.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(61, 'bông cải xanh', 14, 'ingredient', NULL, 6000.00, NULL, 1, '1763205973_6918635539174.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(62, 'ớt chuông', 14, 'ingredient', NULL, 9000.00, NULL, 1, '1763205997_6918636d7b4be.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(63, 'cà rốt', 14, 'ingredient', NULL, 5000.00, NULL, 1, '1763206026_6918638a4c92d.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(64, 'cà chua bi', 14, 'ingredient', NULL, 9000.00, NULL, 1, '1763206052_691863a48a09f.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(65, 'dưa leo', 14, 'ingredient', NULL, 2000.00, NULL, 1, '1763206081_691863c1cb443.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00'),
(66, 'đậu que', 14, 'ingredient', NULL, 4000.00, NULL, 1, '1763206116_691863e4e50cf.png', NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, '2025-10-09 00:00:00', '2025-10-09 00:00:00');

SET FOREIGN_KEY_CHECKS=1;
-- Bien the san pham
INSERT INTO `bien_the_san_pham` (`ma_bien_the`, `ma_san_pham`, `kich_thuoc`, `gia`, `calo`, `ngay_tao`, `ngay_cap_nhat`, `trang_thai`, `so_luong_ton`, `created_at`, `updated_at`) VALUES
(17, 9, 'S', 92000.00, 232, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 87, NULL, NULL),
(18, 9, 'M', 129000.00, 345, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(19, 11, 'S', 89000.00, 362, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(20, 11, 'M', 125000.00, 536, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(21, 12, 'S', 92000.00, 431, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(22, 12, 'M', 129000.00, 638, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(23, 13, 'S', 82000.00, 390, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(24, 13, 'M', 117000.00, 614, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(25, 14, 'S', 96000.00, 463, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 98, NULL, NULL),
(26, 14, 'M', 139000.00, 714, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(27, 15, 'S', 53000.00, 189, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(28, 15, 'M', 139000.00, 345, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(29, 16, 'S', 51000.00, 174, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(30, 16, 'M', 139000.00, 683, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(31, 17, 'S', 51000.00, 187, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(32, 17, 'M', 127000.00, 536, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(33, 18, 'S', 55000.00, 153, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(34, 18, 'M', 139000.00, 714, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(35, 19, 'S', 92000.00, 405, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(36, 19, 'M', 129000.00, 599, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(37, 20, 'S', 89000.00, 224, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(38, 21, 'S', 99000.00, 255, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(39, 21, 'M', 141000.00, 331, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(40, 22, 'S', 105000.00, 239, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 99, NULL, NULL),
(41, 22, 'M', 137000.00, 348, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(42, 23, 'S', 116000.00, 579, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(43, 23, 'M', 159000.00, 792, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(44, 24, 'S', 169000.00, 635, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(45, 24, 'M', 245000.00, 924, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(46, 25, 'S', 138000.00, 540, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(47, 26, 'S', 185000.00, 851, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(48, 27, 'S', 55000.00, 536, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(49, 28, 'S', 55000.00, 307, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(50, 29, 'S', 48000.00, 133, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 96, NULL, NULL),
(51, 30, 'S', 60000.00, 133, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(52, 31, 'S', 5000.00, 329, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(53, 32, 'S', 5000.00, 86, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(54, 33, 'S', 20000.00, 222, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(55, 34, 'S', 22000.00, 576, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(56, 35, 'S', 24000.00, 222, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(57, 36, 'S', 30000.00, 77, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(58, 37, 'S', 18000.00, 125, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(59, 38, 'S', 40000.00, 256, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(60, 39, 'S', 15000.00, 131, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(61, 40, 'S', 25000.00, 312, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(62, 41, 'S', 10000.00, 130, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(63, 42, 'S', 36000.00, 264, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL),
(64, 43, 'S', 70000.00, 431, '2025-10-09 00:00:00', '2025-10-09 00:00:00', 1, 100, NULL, NULL);
