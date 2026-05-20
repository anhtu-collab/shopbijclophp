<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart; 
use App\Models\OrderItem;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
    {
        // Lấy total từ Session (an toàn hơn là lấy từ form)
        $total = Session::get('checkout')['total'] ?? 0;

        if ($total <= 0) {
            return redirect()->route('cart.checkout')
                ->with('error', 'Không tìm thấy thông tin đơn hàng');
        }

        $vnp_TmnCode    = env('VNPAY_TMN_CODE', 'I3LIZ4ZD');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'O9G26RW5B6OCE21YZJG06WFOS4IAFEJH');
        $vnp_Url        = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl  = route('payment.vnpay.return'); // route xử lý callback

        // Lấy order_id từ session (đã lưu trong CartController)
        $orderId        = Session::get('order_id', time());

        $inputData = [
            "vnp_Version"   => "2.1.0",
            "vnp_TmnCode"   => $vnp_TmnCode,
            "vnp_Amount"    => $total * 100,        // VNPAY tính theo đơn vị nhỏ nhất
            "vnp_Command"   => "pay",
            "vnp_CreateDate"=> date('YmdHis'),
            "vnp_CurrCode"  => "VND",
            "vnp_IpAddr"    => $request->ip(),      // Dùng helper Laravel
            "vnp_Locale"    => "vn",                // ✅ Phải là chữ thường
            "vnp_OrderInfo" => "Thanh toan don hang #" . $orderId,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef"    => $orderId,            // ✅ Dùng order_id thực, không phải string
        ];

        // Sắp xếp và tạo hash
        ksort($inputData);

        $hashdata = "";
        $query    = "";
        $i        = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $paymentUrl    = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        // ✅ Dùng redirect() của Laravel thay vì header()
        return redirect($paymentUrl);
    }

    // public function vnpay_return(Request $request)
    // {
    //     $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'O9G26RW5B6OCE21YZJG06WFOS4IAFEJH');

    //     // Lấy secure hash từ VNPAY trả về
    //     $vnp_SecureHash = $request->input('vnp_SecureHash');

    //     // Loại bỏ hash khỏi data để verify
    //     $inputData = $request->except(['vnp_SecureHash', 'vnp_SecureHashType']);

    //     ksort($inputData);
    //     $hashdata = urldecode(http_build_query($inputData));
    //     $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

    //     $order_id   = Session::get('order_id');
    //     $transaction = Transaction::where('order_id', $order_id)->first();

    //     // Xác minh chữ ký hợp lệ
    //     if ($secureHash !== $vnp_SecureHash) {
    //         if ($transaction) {
    //             $transaction->status = 'pending';
    //             $transaction->save();
    //             Cart::instance('cart')->destroy();
    //             Session::forget('checkout');
    //             Session::forget('coupon');
    //             Session::forget('discounts');        
    //         }
    //         return redirect()->route('cart.order.confirmation')
    //             ->with('success', 'Thanh toán hông thành công');
    //     }

    //     // Kiểm tra mã phản hồi từ VNPAY
    //     if ($request->input('vnp_ResponseCode') === '00') {
    //         if ($transaction) {
    //             $transaction->status = 'pending';
    //             $transaction->save();
    //         }
    //         return redirect()->route('cart.order.confirmation')
    //             ->with('success', 'Thanh toán hông thành công');
    //     }

    //     // Thanh toán thất bại
    //     if ($transaction) {
    //         $transaction->status = 'pending';
    //         $transaction->save();
    //     }

    //     return redirect()->route('cart.index')
    //         ->with('error', 'Thanh toán thất bại. Vui lòng thử lại');
    // }

    public function vnpay_return(Request $request)
{
    $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'O9G26RW5B6OCE21YZJG06WFOS4IAFEJH');

    $vnp_SecureHash = $request->input('vnp_SecureHash');
    $inputData = $request->except(['vnp_SecureHash', 'vnp_SecureHashType']);

    ksort($inputData);

    // ✅ FIX HASH (giống bên payment)
    $hashdata = "";
    $i = 0;
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
    }

    $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

    // ✅ FIX order_id
    $order_id = $request->input('vnp_TxnRef');
    $transaction = Transaction::where('order_id', $order_id)->first();

    // ❌ HASH SAI
    if ($secureHash !== $vnp_SecureHash) {
        if ($transaction) {
            $transaction->status = 'declined';
            $transaction->save();
        }

        return redirect()->route('cart.index')
            ->with('error', 'Chữ ký không hợp lệ');
    }

    // ✅ THANH TOÁN THÀNH CÔNG
    if ($request->input('vnp_ResponseCode') === '00') {
        if ($transaction) {
            $transaction->status = 'approved';
            $transaction->save();
        }

        Cart::instance('cart')->destroy();

        return redirect()->route('cart.order.confirmation')
            ->with('success', 'Thanh toán thành công');
    }

    // ❌ FAIL / HỦY
    if ($transaction) {
        $transaction->status = 'declined';
        $transaction->save();
    }

    return redirect()->route('cart.index')
        ->with('error', 'Thanh toán thất bại hoặc đã hủy');
}

}