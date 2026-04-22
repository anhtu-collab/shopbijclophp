<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart; 
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index() {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }


    public function add_to_cart(Request $request) {
        Cart::instance('cart')->add(
            $request->id, 
            $request->name, 
            $request->quantity, 
            $request->price,
            [
            'size' => $request->size,
            'color' => $request->color,
            ]

        )->associate('App\Models\Product'); // Liên kết với Model Product để lấy ảnh
        
        return redirect()->back();
    }
    // Hàm tăng số lượng
public function increase_cart_quantity($rowId) {
    // Lấy thông tin sản phẩm từ giỏ hàng bằng rowId [00:01:29]
    $product = Cart::instance('cart')->get($rowId);
    $qty = $product->qty + 1; // Tăng lên 1
    
    // Cập nhật lại giỏ hàng [00:01:54]
    Cart::instance('cart')->update($rowId, $qty);
    return redirect()->back();
}

// Hàm giảm số lượng
public function decrease_cart_quantity($rowId) {
    $product = Cart::instance('cart')->get($rowId);
    $qty = $product->qty - 1; // Giảm đi 1
    
    Cart::instance('cart')->update($rowId, $qty);
    return redirect()->back();
}
// Hàm xóa 1 sản phẩm cụ thể
public function remove_item($rowId) {
    // Sử dụng phương thức remove() với rowId [00:01:11]
    Cart::instance('cart')->remove($rowId);
    return redirect()->back();
}

// Hàm xóa sạch giỏ hàng
public function empty_cart() {
    // Sử dụng phương thức destroy() để xóa toàn bộ [00:07:26]
    Cart::instance('cart')->destroy();
    return redirect()->back();
}
public function apply_coupon_code(Request $request)
{
    $coupon_code = strtoupper(trim($request->coupon_code));
   if (!empty($coupon_code)) {

                $subtotal = (float) str_replace(['.', ',', ' '], '', Cart::instance('cart')->subtotal());

                $coupon = Coupon::where('code', $coupon_code)
                    ->where('expiry_date', '>=', Carbon::today())
                    ->where('cart_value', '<=', $subtotal)
                    ->first();

                if (!$coupon) {
                    return back()->with('error', 'Invalid coupon code');
                }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ]);

        $this->calculateDiscount();

        return back()->with('success', 'Coupon has been applied');
    } 
    else {
        return back()->with('error', 'Invalid coupon code');
    }
}

public function calculateDiscount()
{
            $discount = 0;
            if (Session::has('coupon')) 
            {
            $subtotal = floatval(str_replace(',', '', Cart::instance('cart')->subtotal()));

            if (Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = ($subtotal * Session::get('coupon')['value']) / 100;
            }

        $discount = min($discount, $subtotal);
        $subtotalAfterDiscount = $subtotal - $discount;
        $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
        $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

       Session::put('discounts', [
           'discount' => floatval($discount),
           'subtotal' => floatval($subtotalAfterDiscount),
           'tax'      => floatval($taxAfterDiscount),
           'total'    => floatval($totalAfterDiscount)
       ]);
        
            
    }
}
public function remove_coupon_code()
{
    Session::forget('coupon');
    Session::forget('discounts');
   return back()->with('success', 'Coupon has been removed');
}
public function checkout()
{
    if (!Auth::check()) 
        {
        return redirect()->route('login');
    }

    $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
    return view('checkout', compact('address'));
}
public function place_an_order(Request $request) {
    $user_id = Auth::user()->id;
  
    $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

    // Nếu chưa có địa chỉ mặc định, yêu cầu nhập và lưu mới
    if (!$address) {
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:10',
            'zip' => 'required|numeric|digits:6',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'locality' => 'required',
            'landmark' => 'required',
        ]);

        $address = new Address();
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->zip = $request->zip;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->address = $request->address;
        $address->locality = $request->locality;
        $address->landmark = $request->landmark;
        $address->country = 'Vietnam'; // Hoặc lấy từ input
        $address->user_id = $user_id;
        $address->isdefault = true;
        $address->save();
    }

    // 2. Thiết lập số tiền thanh toán (lấy từ Session hoặc Cart)
    $this->setAmountforCheckout();

    // 3. Tạo đơn hàng mới (Order)
    $order = new Order();
    $order->user_id = $user_id;
    $order->subtotal = Session::get('checkout')['subtotal'];
    $order->discount = Session::get('checkout')['discount'];
    $order->tax = Session::get('checkout')['tax'];
    $order->total = Session::get('checkout')['total'];
    $order->name = $address->name;
    $order->phone = $address->phone;
    $order->locality = $address->locality;
    $order->address = $address->address;
    $order->city = $address->city;
    $order->state = $address->state;
    $order->country = $address->country;
    $order->landmark = $address->landmark;
    $order->zip = $address->zip;
    $order->save();

    // 4. Lưu chi tiết sản phẩm (Order Items)
    foreach (Cart::instance('cart')->content() as $item) 
    {
        $orderItem = new OrderItem();
        $orderItem->product_id = $item->id;
        $orderItem->order_id = $order->id;
        $orderItem->price = $item->price;
        $orderItem->quantity = $item->qty;
       $orderItem->options = [
    'size' => $item->options['size'] ?? null,
    'color' => $item->options['color'] ?? null,
];
        $orderItem->save();
    }

    //5. Lưu giao dịch (Transaction) - Ở đây xử lý COD
     if ($request->mode == 'card') 
    {
        //
    }
    // elseif($request->mode == 'vnpay') 
    // {
    // // Trả về view chứa form auto-submit
    //     $transaction = new Transaction();
    //     $transaction->user_id = $user_id;
    //     $transaction->order_id = $order->id;
    //     $transaction->mode = 'vnpay';
    //     $transaction->status = 'pending'; // Đang chờ xử lý
    //     $transaction->save();
    //     return redirect()->route('payment.vnpay');
    //     // return redirect($response['payment.vnpay']);
    // }
    elseif($request->mode == 'vnpay') 
{
    $transaction = new Transaction();
    $transaction->user_id = $user_id;
    $transaction->order_id = $order->id;
    $transaction->mode = 'vnpay';
    $transaction->status = 'pending';
    $transaction->save();

    // 🔥 LƯU order để dùng khi return
    Session::put('order_id', $order->id);
  

    return redirect()->route('payment.vnpay', ['redirect' => 1]);
         
}
    elseif ($request->mode == 'cod') 
    {
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->mode = 'cod';
        $transaction->status = 'pending'; // Đang chờ xử lý
        $transaction->save();
    }



    //6. Xóa giỏ hàng và Session
    Cart::instance('cart')->destroy();
    Session::forget('checkout');
    Session::forget('coupon');
    Session::forget('discounts');
    Session::put('order_id', $order->id);
    // return redirect()->route('cart.order.confirmation',compact('order'));
    return redirect()->route('cart.order.confirmation');

    
}

// Hàm phụ để tính toán số tiền
public function setAmountforCheckout() {
    if (Cart::instance('cart')->content()->count() <= 0)
        {
        Session::forget('checkout');
        return;
    }

    if (Session::has('coupon')) {
        Session::put('checkout', [
            'discount' => Session::get('discounts')['discount'] ?? 0,
            'subtotal' => Session::get('discounts')['subtotal'] ?? Cart::instance('cart')->subtotal(),
            'tax'      => Session::get('discounts')['tax'] ?? Cart::instance('cart')->tax(),
            'total'    => Session::get('discounts')['total'] ?? Cart::instance('cart')->total(),
                ]);
    } else {
        Session::put('checkout', [
            'discount' => 0,
            'subtotal' => floatval(str_replace(',', '', Cart::instance('cart')->subtotal())),
            'tax' => floatval(str_replace(',', '', Cart::instance('cart')->tax())),
            'total' => floatval(str_replace(',', '', Cart::instance('cart')->total()))
                    ]);
            
    }
}
 public function order_confirmation()
 {
    if (Session::has('order_id')) 
    {
        $order = Order::find(Session::get('order_id'));
        return view('order-confirmation', compact('order'));
    }

    return redirect()->route('cart.index');
 }


public function vnpayReturn(Request $request)
{
    $order_id = Session::get('order_id');
    $transaction = Transaction::where('order_id', $order_id)->first();

    // xử lý trạng thái VNPay: success / failed
}
}