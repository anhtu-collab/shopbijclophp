<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

     public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'DESC') ->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)
                      ->where('id', $order_id)
                      ->first();

        if ($order) {
            $orderItems = OrderItem::where('order_id', $order->id)
                                  ->orderBy('id')
                                  ->paginate(12);
            $transaction = Transaction::where('order_id', $order->id)->first();
            
            return view('user.order-details', compact('order', 'orderItems', 'transaction'));
        } else {
            return redirect()->route('login');
        }
    }
    public function place_order(Request $request)
{
    $user = Auth::user();

    // 1. lấy địa chỉ
    if ($request->address_id) {
        $address = Address::where('id', $request->address_id)
                    ->where('user_id', $user->id)
                    ->first();
    } else {
        $address = Address::where('user_id', $user->id)
                    ->where('is_default', 1)
                    ->first();
    }

    if (!$address) {
        return back()->with('error', 'Chưa có địa chỉ giao hàng!');
    }

    // 2. tạo order
    $order = new Order();

    $order->user_id = $user->id;

    $order->name = $address->name;
    $order->phone = $address->phone;
    $order->address = $address->address;
    $order->city = $address->city;
    $order->state = $address->state;
    $order->country = $address->country;
    $order->zip = $address->zip;
    $order->locality = $address->locality;
    $order->landmark = $address->landmark;

    $order->address_id = $address->id;
    $order->address_type = $address->is_default ? 'default' : 'custom';

    // 3. tính cart (GIẢ SỬ BẠN DÙNG SESSION CART)
    $cart = session()->get('cart', []);

    $subtotal = 0;

    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $order->subtotal = $subtotal;
    $order->discount = 0;
    $order->tax = 0;
    $order->total = $subtotal;

    $order->status = 'ordered';
    $order->save();

    // 4. tạo order items
    foreach ($cart as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    // 5. clear cart
    session()->forget('cart');

    return redirect()->route('user.orders')
        ->with('success', 'Đặt hàng thành công!');
}
//     public function order_cancel(Request $request)
// {
//     $order = Order::find($request->order_id);

//     if (!$order) {
//         return back()->with('error', 'Không tìm thấy đơn hàng!');
//     }

//     if ($order->user_id != Auth::id()) {
//         return back()->with('error', 'Không có quyền hủy đơn này!');
//     }
//     if ($order->status != 'pending') {
//     return back()->with('error', 'Không thể hủy đơn này!');
// }

//     $order->status = "canceled";
//     $order->canceled_date = Carbon::now();
//     $order->save();

//     return back()->with('status', 'Đã hủy thành công!');
// }
public function order_cancel(Request $request)
{
    $order = Order::where('id', $request->order_id)
                  ->where('user_id', Auth::id())
                  ->first();

    if (!$order) {
        return back()->with('error', 'Không tìm thấy đơn hàng!');
    }

    if ($order->status !== 'pending') {
        return back()->with('error', 'Không thể hủy đơn này!');
    }

    $order->update([
        'status' => 'canceled',
        'canceled_date' => Carbon::now()
        
    ]);

    return back()->with('status', 'Đã hủy thành công!');
}
public function address()
{
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('user.address', compact('addresses'));
}
public function details()
{
    return view('user.details');
}
public function add_address()
{
    return view('user.address-add');
}
public function edit_address($id)
{
    
    
    $address = Address::where('id', $id)
                      ->where('user_id', auth()->id())
                      ->first();
    if (!$address) {
    return redirect()->route('user.address')->with('error', 'Không tìm thấy địa chỉ!');
}

    return view('user.address-edit', compact('address'));
}
public function update_address(Request $request, $id)
{
    if ($request->has('is_default')) {
    Address::where('user_id', Auth::id())->update(['is_default' => 0]);
}
    $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'zip' => 'required',
        'state' => 'required',
        'city' => 'required',
        'address' => 'required',
        'locality' => 'required',
        'landmark' => 'required',
    ]);

    $address = Address::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->first();

    if (!$address) {
        return back()->with('error', 'Không tìm thấy địa chỉ!');
    }

    $address->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'zip' => $request->zip,
        'state' => $request->state,
        'city' => $request->city,
        'address' => $request->address,
        'locality' => $request->locality,
        'landmark' => $request->landmark,
        'is_default' => $request->has('is_default') ? 1 : 0,
    ]);

    return redirect()->route('user.address')
        ->with('success', 'Cập nhật địa chỉ thành công!');
}
public function store_address(Request $request)
{
    if ($request->has('is_default')) {
    Address::where('user_id', Auth::id())->update(['is_default' => 0]);
}
    $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'zip' => 'required',
        'state' => 'required',
        'city' => 'required',
        'address' => 'required',
        'locality' => 'required',
        'landmark' => 'required',
    ]);

    Address::create([
        'user_id' => Auth::id(),
        'name' => $request->name,
        'phone' => $request->phone,
        'zip' => $request->zip,
        'state' => $request->state,
        'city' => $request->city,
        'country' => 'Vietnam', 
        'address' => $request->address,
        'locality' => $request->locality,
        'landmark' => $request->landmark,
        'is_default' => $request->has('is_default') ? 1 : 0,
    ]);

    return redirect()->route('user.address')
        ->with('success', 'Thêm địa chỉ thành công!');
}
public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'mobile' => 'required',
        'new_password' => 'nullable|confirmed|min:6'
    ]);

  
    $user->name = $request->name;
    $user->email = $request->email;
    $user->mobile = $request->mobile;

    // nếu có đổi password
    if ($request->filled('new_password')) {

        // check password cũ
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Mật khẩu cũ không đúng!');
        }

        $user->password = Hash::make($request->new_password);
    }

    $user->save();

    return back()->with('success', 'Cập nhật thành công!');
}

}

