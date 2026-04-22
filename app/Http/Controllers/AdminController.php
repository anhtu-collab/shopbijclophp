<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Slide;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);
        $dashboardDatas = DB::select("Select sum(total) AS TotalAmount,
                                    sum(if(status='ordered', total, 0)) AS TotalOrderedAmount,
                                    sum(if(status='delivered', total, 0)) AS TotalDeliveredAmount,
                                    sum(if(status='canceled', total, 0)) AS TotalCanceledAmount,
                                    Count(*) AS Total,
                                    sum(if(status='ordered', 1, 0)) AS TotalOrdered,
                                    sum(if(status='delivered', 1, 0)) AS TotalDelivered,
                                    sum(if(status='canceled', 1, 0)) AS TotalCanceled
                                    From Orders
                                    ");

    $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,                         
                         IFNULL(D.TotalAmount,0) As TotalAmount,
                         IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
                         IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
                         IFNULL(D.TotalCanceledAmount,0) As TotalCanceledAmount FROM month_names M
                         LEFT JOIN (Select DATE_FORMAT(created_at, '%b') As MonthName,
                         MONTH(created_at) As MonthNo,
                         sum(total) As TotalAmount,
                         sum(if(status='ordered',total,0)) As TotalOrderedAmount,
                         sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
                         sum(if(status='canceled',total,0)) As TotalCanceledAmount
                         From Orders 
                        WHERE YEAR(created_at)=YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
                        Order By MONTH(created_at)) D On D.MonthNo=M.id");

        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

         $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
         $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
         $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
         $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

return view('admin.index', compact(
    'orders',
    'dashboardDatas',
    'AmountM',
    'OrderedAmountM',
    'DeliveredAmountM',
    'CanceledAmountM',
    'TotalAmount',
    'TotalOrderedAmount',
    'TotalDeliveredAmount',
    'TotalCanceledAmount'
    
));
    }
    public function order_tracking()
{
   $orders = Order::where('status', 'ordered')->orderBy('id', 'desc')->paginate(10);

    return view('admin.order-tracking', compact('orders'));
}
    
     
    public function brands()
    {
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }
    public function add_brand()
    {
        return view('admin.brand-add');
    }
    public function brand_store(Request $request){
        $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:brands,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
         $brand = new Brand();
         $brand->name = $request->name;
         $brand->slug = Str::slug($request->name);
         $image = $request->file('image');
         $file_extention = $request->file('image')->extension();
         $file_name = Carbon::now()->timestamp.'.'.$file_extention;
         $this->GenerateBrandThumbailsImage($image,$file_name);
         $brand ->image = $file_name;
         $brand ->save();
         return Redirect() ->route('admin.brands')->with('status','Đã Thêm Thành Công !');

    }

    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));
    }
    public function brand_update(Request $request)
    {
          $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:brands,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = Brand::find($request->id);
         $brand->name = $request->name;
         $brand->slug = Str::slug($request->name);
         if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image))
                {
                    File::delete(public_path('uploads/brands').'/'.$brand->image);
                }
                 $image = $request->file('image');
                 $file_extention = $request->file('image')->extension();
                 $file_name = Carbon::now()->timestamp.'.'.$file_extention;
                $this->GenerateBrandThumbailsImage($image,$file_name);
         $brand ->image = $file_name;
         }
        
         $brand ->save();
         return Redirect() ->route('admin.brands')->with('status','Đã Cập Nhật Thành Công!');
    }
    public function GenerateBrandThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();

        })->save($destinationPath.'/'.$imageName);
    }
    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $brand->delete();
            return redirect()->route('admin.brands')->with('status','Đã Xóa Thành Công!');
    }
    public function categories()
    {
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }
    public function category_add()
    {
        return view('admin.category-add');
    }
    public function category_store(Request $request)
    {
          $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:categories,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
         $category = new Category();
         $category->name = $request->name;
         $category->slug = Str::slug($request->name);
         $image = $request->file('image');
         $file_extention = $request->file('image')->extension();
         $file_name = Carbon::now()->timestamp.'.'.$file_extention;
         $this->GenerateCategoryThumbailsImage($image,$file_name);
         $category ->image = $file_name;
         $category ->save();
         return Redirect() ->route('admin.categories')->with('status',' Đã Thêm Thành Công !');
    }
      public function GenerateCategoryThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();

        })->save($destinationPath.'/'.$imageName);
    }
    
    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }
    public function category_update(Request $request)
    {
          $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:categories,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
        $category = Category::find($request->id);
         $category->name = $request->name;
         $category->slug = Str::slug($request->name);
         if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image))
                {
                    File::delete(public_path('uploads/categories').'/'.$category->image);
                }
                 $image = $request->file('image');
                 $file_extention = $request->file('image')->extension();
                 $file_name = Carbon::now()->timestamp.'.'.$file_extention;
                $this->GenerateCategoryThumbailsImage($image,$file_name);
         $category ->image = $file_name;
         }
        
         $category ->save();
         return Redirect() ->route('admin.categories')->with('status','Đã Cập Nhật Thành Công');
    }
    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image))
            {
                File::delete(public_path('uploads/categories').'/'.$category->image);

            }
            $category->delete();
            return redirect()->route('admin.categories')->with('status','Đã Xóa Thành Công!');

    }
    public function products()
    {
        $products = Product::orderBy('created_at','DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }
    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = (int) str_replace('.', '', $request->regular_price);
        $product->sale_price = (int) str_replace('.', '', $request->sale_price);
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->sizes = json_encode(array_values(array_unique(json_decode($request->sizes, true) ?? [])));
        $product->colors = json_encode(array_values(array_unique(json_decode($request->colors, true) ?? [])));
        $current_timestamp = Carbon::now()->timestamp;

        // Xử lý ảnh chính (Main Image)
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        // Xử lý Gallery ảnh
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')) {
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $check = in_array($gextension, $allowedfileExtension);
                if($check) {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailsImage($file, $gfilename);
                    array_push($gallery_arr, $gfilename);
                    $counter++;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();

        return redirect()->route('admin.products')->with('status', 'Đã Thêm Thành Công!');
    }

    public function GenerateProductThumbnailsImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        
        // Tạo thư mục nếu chưa có
        if (!File::exists($destinationPathThumbnail)) {
            File::makeDirectory($destinationPathThumbnail, 0755, true);
        }

        $img = Image::read($image->path());

        // Ảnh gốc được resize theo kích thước chuẩn e-commerce (540x689)
        $img->cover(540, 689, "top");
        $img->save($destinationPath . '/' . $imageName);

        // Ảnh Thumbnail nhỏ (104x104)
        $img->resize(104, 104, function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail . '/' . $imageName);
    }
    // Thêm vào AdminController.php

public function product_edit($id)
{
    $product = Product::find($id);
    $categories = Category::select('id', 'name')->orderBy('name')->get();
    $brands = Brand::select('id', 'name')->orderBy('name')->get();
    return view('admin.product-edit', compact('product', 'categories', 'brands'));
}

public function product_update(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug,' . $request->id . ',id',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'image' => 'mimes:png,jpg,jpeg|max:2048',
        'category_id' => 'required',
        'brand_id' => 'required',
        'sizes' => 'nullable|string',
        'colors' => 'nullable|string',

    ]);

    $product = Product::find($request->id);
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = (int) str_replace('.', '', $request->regular_price);
    $product->sale_price = (int) str_replace('.', '', $request->sale_price);
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;
    $product->sizes = json_encode(array_values(array_unique(json_decode($request->sizes, true) ?? [])));
    $product->colors = json_encode( array_values(array_unique(json_decode($request->colors, true) ?? [])));

    $current_timestamp = Carbon::now()->timestamp;

    // Xử lý ảnh chính khi có thay đổi
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu tồn tại
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        $image = $request->file('image');
        $imageName = $current_timestamp . '.' . $image->extension();
        $this->GenerateProductThumbnailsImage($image, $imageName);
        $product->image = $imageName;
    }

    // Xử lý Gallery ảnh khi có thay đổi
    if ($request->hasFile('images')) {
        // Xóa tất cả ảnh gallery cũ
        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }

        $gallery_arr = array();
        $counter = 1;
        $files = $request->file('images');
        foreach ($files as $file) {
            $gextension = $file->getClientOriginalExtension();
            $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
            $this->GenerateProductThumbnailsImage($file, $gfilename);
            array_push($gallery_arr, $gfilename);
            $counter++;
        }
        $product->images = implode(',', $gallery_arr);
    }

    $product->save();
    return redirect()->route('admin.products')->with('status', 'Đã Cập Nhật Thành Công!');
}
// Thêm vào AdminController.php

public function product_delete($id)
{
    $product = Product::find($id);

    // 1. Xóa ảnh chính và ảnh thumbnail chính
    if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
        File::delete(public_path('uploads/products') . '/' . $product->image);
    }
    if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
        File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
    }

    // 2. Xóa các ảnh trong gallery (bao gồm cả thumbnail của gallery)
    if (!empty($product->images)) {
        foreach (explode(',', $product->images) as $gfile) {
            $gfile = trim($gfile);
            if (File::exists(public_path('uploads/products') . '/' . $gfile)) {
                File::delete(public_path('uploads/products') . '/' . $gfile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $gfile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $gfile);
            }
        }
    }

    // 3. Xóa bản ghi trong Database
    $product->delete();
    
    return redirect()->route('admin.products')->with('status', 'Đã Xóa Thành Công!');
}
public function variant_store(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'color' => 'required',
        'size' => 'required',
        'stock' => 'required|numeric',
        'price' => 'required|numeric',
    ]);

    // check trùng
    $exists = ProductVariant::where('product_id', $request->product_id)
        ->where('color', $request->color)
        ->where('size', $request->size)
        ->first();

    if ($exists) {
        return back()->with('error', 'Biến thể đã tồn tại!');
    }

    ProductVariant::create([
        'product_id' => $request->product_id,
        'color' => $request->color,
        'size' => $request->size,
        'stock' => $request->stock,
        'price' => $request->price,
    ]);

    return back()->with('status', 'Thêm màu + size thành công!');
}
public function coupons()
{
    // Lấy danh sách coupon, sắp xếp theo ngày hết hạn mới nhất và phân trang
    $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(12);
    return view('admin.coupons', compact('coupons'));
}
public function coupon_add() {
    return view('admin.coupon-add');
}

public function coupon_store(Request $request) {
    $request->validate([
        'code' => 'required|unique:coupons,code',
        'type' => 'required',
        'value' => 'required',
        'cart_value' => 'required|numeric',
        'expiry_date' => 'required|date',
        ], [
    'code.unique' => 'Mã giảm giá này đã tồn tại rồi!',    
    ]);
    $coupon = new Coupon();
    $coupon->code = $request->code;
    $coupon->type = $request->type;
    $value = preg_replace('/[^\d]/', '', $request->value);
    $value = (int) $value;
    if ($request->type == 'percent' && $value > 100) {
    return back()->with('error', 'Phần trăm không được > 100');
}

$coupon->value = $value;
    $coupon->cart_value = $request->cart_value;
    $coupon->expiry_date = $request->expiry_date;
    $coupon->save();
    return redirect()->route('admin.coupons')->with('status', 'Đã Thêm Thành Công!');
}

public function coupon_edit($id) {
    $coupon = Coupon::find($id); 
    return view('admin.coupon-edit', compact('coupon'));
}

// 2. Hàm lưu dữ liệu mới
public function coupon_update(Request $request)
{
    // Bước 1: Validate dữ liệu đầu vào từ form
    $request->validate([
        'code'=> 'required',
        'type'=> 'required',
        'value'=> 'required',
        'cart_value'=> 'required|numeric',
        'expiry_date'=> 'required|date'
    ]);

    // Bước 2: Tìm Coupon dựa trên ID truyền lên
    $coupon = Coupon::find($request->id);

    // Bước 3: Gán giá trị mới cho các cột trong Database
    $coupon->code        = $request->code;
    $coupon->type        = $request->type;
    $value = preg_replace('/[^\d]/', '', $request->value);
    $value = (int) $value;

    if ($request->type == 'percent' && $value > 100) {
        return back()->with('error', 'Phần trăm không được > 100');
}

$coupon->value = $value;
    $coupon->cart_value  = $request->cart_value;
    $coupon->expiry_date = $request->expiry_date;

    // Bước 4: Lưu thay đổi
    $coupon->save();

    // Bước 5: Chuyển hướng về trang danh sách kèm thông báo
    return redirect()->route('admin.coupons')->with('status', 'Đã Cập Nhật Thành Công!');
}


public function coupon_delete($id)
{
    // Tìm coupon theo ID
    $coupon = Coupon::find($id);
    
    // Thực hiện xóa
    $coupon->delete();
    
    // Quay lại trang danh sách với thông báo thành công
    return redirect()->route('admin.coupons')->with('status', 'Đã Xóa Thành Công!');
}
 public function orders()
{
    // Lấy danh sách đơn hàng, sắp xếp mới nhất trước và phân trang 12 mục
$latestId = session('last_updated_order_id');

    $orders = Order::orderByRaw("
        CASE 
            WHEN id = ? THEN 0
            ELSE 1
        END
    ", [$latestId])
    ->orderByDesc('id')
    ->paginate(12);

    // 🔥 xoá session sau khi dùng
    session()->forget('last_updated_order_id');

    return view('admin.orders', compact('orders'));
}
public function order_details($order_id) {
    $order = Order::find($order_id);
    $orderItems = OrderItem::where('order_id', $order_id)
                    ->orderBy('id')
                    ->paginate(12);
    $transaction = Transaction::where('order_id', $order_id)->first();
    
    return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
}
// app/Http/Controllers/AdminController.php

public function update_order_status(Request $request)
{
    $order = Order::find($request->order_id);
    $order->status = $request->order_status;

    if ($request->order_status == 'delivered') 
    {
        $order->delivered_date = Carbon::now();
    } 
    elseif ($request->order_status == 'canceled') 
    {
        $order->canceled_date = Carbon::now();
    }
    $order->save();

    $transaction = Transaction::where('order_id', $request->order_id)->first();

    if ($transaction) {
        if ($request->order_status == 'delivered') {
            $transaction->status = 'approved';
        } 
        elseif ($request->order_status == 'canceled') {
            $transaction->status = 'declined'; // 👈 QUAN TRỌNG
        }
        $transaction->save();
        session(['last_updated_order_id' => $order->id]);

    }
    return back()->with('status', 'Cập Nhật Thành Công!'); 
}
public function order_invoice($order_id)
{
    $order = Order::with('orderItems.product')->findOrFail($order_id);
    return view('admin.invoice', compact('order'));
}
public function slides() {
    $slides = Slide::orderBy('id', 'DESC')->paginate(10);
    return view('admin.slides', compact('slides'));
}
public function slide_add() {

    return view('admin.slide-add');
}

public function slide_store(Request $request) {
$request->validate([
    'tagline' => 'required',
    'title'   => 'required',
    'subtitle' => 'required',
    'link'    => 'required',
    'status'  => 'required',
    'image'   => 'required|mimes:png,jpg,jpeg|max:2048'
]);

$slide = new Slide();
$slide->tagline = $request->tagline;
$slide->title = $request->title;
$slide->subtitle = $request->subtitle;
$slide->link = $request->link;
$slide->status = $request->status;
$image = $request->file('image');
$file_extention = $request->file('image')->extension();
$file_name = Carbon::now()->timestamp . '.' . $file_extention;
$this->GenerateSlideThumbnailsImage($image, $file_name);
$slide->image = $file_name;
$slide->save();
return redirect()->route('admin.slides')->with("status", "Đã Thêm Thành Công!");

}
public function GenerateSlideThumbnailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/slides');
    $img = Image::read($image->path());
    $img->cover(400, 690, "top");
    $img->resize(400, 690, function($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath . '/' . $imageName);
}
public function slide_edit($id)
{
    $slide = Slide::find($id);
    return view('admin.slide-edit', compact('slide'));
}
public function slide_update(Request $request)
{
    $request->validate([
        'tagline' => 'required',
        'title' => 'required',
        'subtitle' => 'required',
        'link' => 'required',
        'status' => 'required',
        'image' => 'mimes:png,jpg,jpeg|max:2048'
    ]);

    $slide = Slide::find($request->id);
    $slide->tagline = $request->tagline;
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->link = $request->link;
    $slide->status = $request->status;

    if($request->hasFile('image'))
    {
        if(File::exists(public_path('uploads/slides').'/'.$slide->image))
        {
            File::delete(public_path('uploads/slides').'/'.$slide->image);
        }

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        $this->GenerateSlideThumbnailsImage($image, $file_name);
        $slide->image = $file_name;
    }

    $slide->save();
    return redirect()->route('admin.slides')->with("status", "Đã Cập Nhật Thành Công!");
}
public function slide_delete($id)
{
    $slide = Slide::find($id);
    
    if(File::exists(public_path('uploads/slides').'/'.$slide->image))
    {
        File::delete(public_path('uploads/slides').'/'.$slide->image);
    }

    $slide->delete();
    return redirect()->route('admin.slides')->with("status", "Đã Xóa Thành Công!");
}
public function contacts() {
    $contacts = Contact::orderBy('created_at', 'DESC')->paginate(10);
    return view('admin.contacts', compact('contacts'));
}

public function contact_delete($id) {
    $contact = Contact::find($id);
    $contact->delete();
    return redirect()->back()->with('status', 'Đã xóa thành công!');
}
public function search(Request $request)
{
    $query = $request->input('query');
    $results = Product::where('name', 'LIKE', "%{$query}%")
                ->take(8) 
                ->get();

    return response()->json($results);
}
}
