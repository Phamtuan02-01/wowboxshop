<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Exception;

class MoMoPaymentService
{
    private $accessKey;
    private $secretKey;
    private $partnerCode;
    private $endpoint;
    private $returnUrl;
    private $notifyUrl;

    public function __construct()
    {
        // MoMo Test Environment Configuration
        $this->accessKey = config('services.momo.access_key', 'F8BBA842ECF85');
        $this->secretKey = config('services.momo.secret_key', 'K951B6PE1waDMi640xX08PD3vg6EkVlz');
        $this->partnerCode = config('services.momo.partner_code', 'MOMO');
        $this->endpoint = config('services.momo.endpoint', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $this->returnUrl = config('services.momo.return_url', url('/thanh-toan/momo-return'));
        $this->notifyUrl = config('services.momo.notify_url', url('/thanh-toan/momo-notify'));
    }

    /**
     * Tạo payment request cho MoMo
     */
    public function createPayment($orderId, $amount, $orderInfo, $extraData = '')
    {
        try {
            $requestId = $orderId . '_' . time();
            $requestType = "captureWallet";
            $lang = 'vi';
            
            // Tạo raw signature string
            $rawHash = "accessKey=" . $this->accessKey . 
                      "&amount=" . $amount . 
                      "&extraData=" . $extraData . 
                      "&ipnUrl=" . $this->notifyUrl . 
                      "&orderId=" . $orderId . 
                      "&orderInfo=" . $orderInfo . 
                      "&partnerCode=" . $this->partnerCode . 
                      "&redirectUrl=" . $this->returnUrl . 
                      "&requestId=" . $requestId . 
                      "&requestType=" . $requestType;

            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'partnerName' => "WOW Box Shop",
                'storeId' => "WowBoxShop",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $this->returnUrl,
                'ipnUrl' => $this->notifyUrl,
                'lang' => $lang,
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];

            $response = $this->sendRequest($this->endpoint, $data);

            if ($response && $response['resultCode'] == 0) {
                return [
                    'success' => true,
                    'payUrl' => $response['payUrl'],
                    'requestId' => $requestId,
                    'orderId' => $orderId,
                    'signature' => $signature
                ];
            } else {
                Log::error('MoMo Payment Error', [
                    'response' => $response,
                    'data' => $data
                ]);
                return [
                    'success' => false,
                    'message' => $response['message'] ?? 'Có lỗi xảy ra khi tạo thanh toán MoMo',
                    'resultCode' => $response['resultCode'] ?? null
                ];
            }

        } catch (Exception $e) {
            Log::error('MoMo Payment Exception', [
                'error' => $e->getMessage(),
                'orderId' => $orderId
            ]);
            
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến MoMo. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Xác thực callback từ MoMo
     */
    public function verifyCallback($data)
    {
        try {
            $rawHash = "accessKey=" . $this->accessKey . 
                      "&amount=" . $data['amount'] . 
                      "&extraData=" . ($data['extraData'] ?? '') . 
                      "&message=" . $data['message'] . 
                      "&orderId=" . $data['orderId'] . 
                      "&orderInfo=" . $data['orderInfo'] . 
                      "&orderType=" . $data['orderType'] . 
                      "&partnerCode=" . $data['partnerCode'] . 
                      "&payType=" . $data['payType'] . 
                      "&requestId=" . $data['requestId'] . 
                      "&responseTime=" . $data['responseTime'] . 
                      "&resultCode=" . $data['resultCode'] . 
                      "&transId=" . $data['transId'];

            $expectedSignature = hash_hmac("sha256", $rawHash, $this->secretKey);

            if ($expectedSignature !== $data['signature']) {
                Log::warning('MoMo Signature Verification Failed', [
                    'expected' => $expectedSignature,
                    'received' => $data['signature'],
                    'data' => $data
                ]);
                return false;
            }

            return true;

        } catch (Exception $e) {
            Log::error('MoMo Callback Verification Error', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function checkPaymentStatus($orderId, $requestId)
    {
        try {
            $rawHash = "accessKey=" . $this->accessKey . 
                      "&orderId=" . $orderId . 
                      "&partnerCode=" . $this->partnerCode . 
                      "&requestId=" . $requestId;

            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'requestId' => $requestId,
                'orderId' => $orderId,
                'lang' => 'vi',
                'signature' => $signature
            ];

            $endpoint = str_replace('/create', '/query', $this->endpoint);
            $response = $this->sendRequest($endpoint, $data);

            return $response;

        } catch (Exception $e) {
            Log::error('MoMo Status Check Error', [
                'error' => $e->getMessage(),
                'orderId' => $orderId
            ]);
            
            return [
                'resultCode' => -1,
                'message' => 'Không thể kiểm tra trạng thái thanh toán'
            ];
        }
    }

    /**
     * Gửi HTTP request
     */
    private function sendRequest($url, $data)
    {
        try {
            $client = new Client([
                'timeout' => 30,
                'verify' => false // Tắt SSL verification cho test environment
            ]);

            $response = $client->post($url, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $body = $response->getBody()->getContents();
            $result = json_decode($body, true);

            Log::info('MoMo API Response', [
                'url' => $url,
                'data' => $data,
                'response' => $result
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('MoMo HTTP Request Error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            throw $e;
        }
    }

    /**
     * Format số tiền cho MoMo (không có dấu phẩy, chấm)
     */
    public static function formatAmount($amount)
    {
        return (int) str_replace([',', '.', ' '], '', $amount);
    }

    /**
     * Tạo order info cho MoMo
     */
    public static function createOrderInfo($orderCode, $customerName = '')
    {
        $info = "Thanh toán đơn hàng #" . $orderCode;
        if ($customerName) {
            $info .= " - " . $customerName;
        }
        return $info;
    }

    /**
     * Parse callback data
     */
    public function parseCallbackData($request)
    {
        return [
            'partnerCode' => $request->get('partnerCode'),
            'orderId' => $request->get('orderId'),
            'requestId' => $request->get('requestId'),
            'amount' => $request->get('amount'),
            'orderInfo' => $request->get('orderInfo'),
            'orderType' => $request->get('orderType'),
            'transId' => $request->get('transId'),
            'resultCode' => $request->get('resultCode'),
            'message' => $request->get('message'),
            'payType' => $request->get('payType'),
            'responseTime' => $request->get('responseTime'),
            'extraData' => $request->get('extraData', ''),
            'signature' => $request->get('signature')
        ];
    }
}