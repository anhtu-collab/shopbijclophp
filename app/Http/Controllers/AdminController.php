<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Slide;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Size;
use App\Models\Address;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
 
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);
        $dashboardDatas = DB::select("Select sum(total) AS TotalAmount,
                                    sum(if(status IN ('pending','confirmed','processing','shipping'), total, 0)) AS TotalOrderedAmount,
                                    sum(if(status='delivered', total, 0)) AS TotalDeliveredAmount,
                                    sum(if(status='canceled', total, 0)) AS TotalCanceledAmount,
                                    Count(*) AS Total,
                                    sum(if(status IN ('pending','confirmed','processing','shipping'), 1, 0)) AS TotalOrdered,
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
                         sum(if(status IN ('pending','confirmed','processing','shipping'),total,0)) As TotalOrderedAmount,
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
public function order_tracking(Request $request)
{
    $search = $request->search;
    $latestId = session('last_updated_order_id') ?? $request->highlight;

    $orders = Order::where('status', 'pending')
        ->when($search, function($q) use ($search) {
            $q->where(function($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                      ->orWhere('customer_name', 'LIKE', "%{$search}%")
                      ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                      ->orWhere('customer_address', 'LIKE', "%{$search}%");
            });
        })
        ->orderByRaw("CASE WHEN id = ? THEN 0 ELSE 1 END", [$latestId])
        ->orderByDesc('id')
        ->paginate(10);

    return view('admin.order-tracking', compact('orders'));
}
    
     
    public function brands(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $brands = Brand::where('name', 'LIKE', "%{$search}%")
                         ->orderBy('id','DESC')
                         ->paginate(10);
        } else {
            $brands = Brand::orderBy('id','DESC')->paginate(10);
        }

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
    public function categories(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $categories = Category::where('name', 'LIKE', "%{$search}%")
                               ->orderBy('id','DESC')
                               ->paginate(10);
        } else {
            $categories = Category::orderBy('id','DESC')->paginate(10);
        }

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
    public function products(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $products = Product::with(['variants.size', 'variants.color'])
                ->where('name', 'LIKE', "%{$search}%")
                ->orWhere('sku', 'LIKE', "%{$search}%")
                ->orWhere('short_description', 'LIKE', "%{$search}%")
                ->orderBy('created_at','DESC')
                ->paginate(10);
        } else {
            $products = Product::with(['variants.size', 'variants.color'])
                ->orderBy('created_at','DESC')
                ->paginate(10);
        }

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
            
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'mimes:png,jpg,jpeg|max:2048',
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
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        
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
$inputSizes  = json_decode($request->input('sizes'), true) ?? [];   
$inputColors = json_decode($request->input('colors'), true) ?? [];  

$sizeIds = [];
foreach ($inputSizes as $sItem) {
    $sizeName = trim(strtoupper($sItem['size'])); 
    if (!empty($sizeName)) {
        
        $sizeModel = \App\Models\Size::firstOrCreate(['name' => $sizeName]); 
        $sizeIds[] = [
            'id'       => $sizeModel->id,
            'quantity' => intval($sItem['quantity']) ?? 0
        ];
    }
}


$colorIds = [];
foreach ($inputColors as $colorName) {
    $colorName = trim(mb_convert_case($colorName, MB_CASE_TITLE, "UTF-8")); 
    if (!empty($colorName)) {
        
        $colorModel = \App\Models\Color::firstOrCreate(['name' => $colorName]);
        $colorIds[] = $colorModel->id;
    }
}

if (count($sizeIds) > 0 && count($colorIds) > 0) {
    foreach ($sizeIds as $sIdData) {
        foreach ($colorIds as $cId) {
            \App\Models\ProductVariant::create([
                'product_id' => $product->id,
                'size_id'    => $sIdData['id'],       
                'color_id'   => $cId,                 
                'quantity'   => $sIdData['quantity'],
            ]);
        }
    }
} elseif (count($sizeIds) > 0) {
    foreach ($sizeIds as $sIdData) {
        \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'size_id'    => $sIdData['id'],
            'color_id'   => null,
            'quantity'   => $sIdData['quantity'],
        ]);
    }
} elseif (count($colorIds) > 0) {
    foreach ($colorIds as $cId) {
        \App\Models\ProductVariant::create([
            'product_id' => $product->id,
            'size_id'    => null,
            'color_id'   => $cId,
            'quantity'   => intval($request->input('quantity')) ?? 0,
        ]);
    }
}

        return redirect()->route('admin.products')->with('status', 'Đã Thêm Thành Công!');
    }


        public function GenerateProductThumbnailsImage($image, $imageName)
{
    $destinationPathThumbnail = public_path('uploads/products/thumbnails');
    $destinationPath = public_path('uploads/products');

    if (!File::exists($destinationPathThumbnail)) {
        File::makeDirectory($destinationPathThumbnail, 0755, true);
    }

    $img = Image::read($image->path());

    
    $imgOriginal = clone $img;
    $imgOriginal->cover(540, 689, "top")
                ->save($destinationPath . '/' . $imageName);

    
    $imgThumb = clone $img;
    $imgThumb->cover(104, 104, "top")
             ->save($destinationPathThumbnail . '/' . $imageName);
}

public function product_edit($id)
{
    
    $product = Product::with(['variants.size', 'variants.color'])->findOrFail($id);
    $categories = Category::select('id', 'name')->orderBy('name')->get();
    $brands = Brand::select('id', 'name')->orderBy('name')->get();

    
    $oldSizes = $product->variants->whereNotNull('size_id')->map(function($v) {
        return [
            'size' => $v->size ? $v->size->name : 'N/A',
            'quantity' => intval($v->quantity)
        ];
    })->unique('size')->values()->toArray();

    
    $oldColors = $product->variants->whereNotNull('color_id')->map(function($v) {
        return $v->color ? $v->color->name : 'N/A';
    })->unique()->values()->toArray();

    return view('admin.product-edit', compact('product', 'categories', 'brands', 'oldSizes', 'oldColors'));
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
        'image' => 'mimes:png,jpg,jpeg|max:2048',
        'images' => 'nullable|array',
        'images.*' => 'mimes:png,jpg,jpeg|max:2048',
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
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;
    $current_timestamp = Carbon::now()->timestamp;

    if ($request->hasFile('image')) {
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

    $removedImages = json_decode($request->input('removed_images', '[]'), true) ?? [];
    $existingImages = !empty($product->images) ? explode(',', $product->images) : [];

    foreach ($removedImages as $rimg) {
        $rimg = trim($rimg);
        if (File::exists(public_path('uploads/products') . '/' . $rimg)) {
            File::delete(public_path('uploads/products') . '/' . $rimg);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $rimg)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $rimg);
        }
    }

    $keepImages = array_filter($existingImages, function($img) use ($removedImages) {
        return !in_array(trim($img), $removedImages);
    });

    $gallery_arr = array_values($keepImages);
    $counter = 1;

    if ($request->hasFile('images')) {
        $files = $request->file('images');
        foreach ($files as $file) {
            $gextension = $file->getClientOriginalExtension();
            $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
            $this->GenerateProductThumbnailsImage($file, $gfilename);
            array_push($gallery_arr, $gfilename);
            $counter++;
        }
    }

    $product->images = implode(',', $gallery_arr);

    $product->save();
$inputSizes = json_decode($request->sizes, true) ?? [];
$inputColors = json_decode($request->colors, true) ?? [];
    
$product->variants()->delete();

$sizeIds = [];
    foreach ($inputSizes as $sItem) {
        if (empty($sItem['size'])) continue;
        $sizeName = trim(strtoupper($sItem['size']));
        
        $sizeModel = \App\Models\Size::firstOrCreate(['name' => $sizeName]);
        $sizeIds[] = [
            'id'       => $sizeModel->id,
            'quantity' => intval($sItem['quantity']) ?? 0
        ];
    }

    $colorIds = [];
    foreach ($inputColors as $colorName) {
        if (empty($colorName)) continue;
        $colorName = trim(mb_convert_case($colorName, MB_CASE_TITLE, "UTF-8"));
        
        $colorModel = \App\Models\Color::firstOrCreate(['name' => $colorName]);
        $colorIds[] = $colorModel->id;
    }

    if (count($sizeIds) > 0 && count($colorIds) > 0) {
        foreach ($sizeIds as $sIdData) {
            foreach ($colorIds as $cId) {
                \App\Models\ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id'    => $sIdData['id'],
                    'color_id'   => $cId,
                    'quantity'   => $sIdData['quantity'],
                ]);
            }
        }
    } elseif (count($sizeIds) > 0) {
        foreach ($sizeIds as $sIdData) {
            \App\Models\ProductVariant::create([
                'product_id' => $product->id,
                'size_id'    => $sIdData['id'],
                'color_id'   => null,
                'quantity'   => $sIdData['quantity'],
            ]);
        }
    } elseif (count($colorIds) > 0) {
        foreach ($colorIds as $cId) {
            \App\Models\ProductVariant::create([
                'product_id' => $product->id,
                'size_id'    => null,
                'color_id'   => $cId,
                'quantity'   => 0, 
            ]);
        }
    }

    return redirect()->route('admin.products')->with('status', 'Đã Cập Nhật Sản Phẩm Thành Công!');
}


public function product_delete($id)
{
    $product = Product::find($id);

    if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
        File::delete(public_path('uploads/products') . '/' . $product->image);
    }
    if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
        File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
    }

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

    $product->delete();
    
    return redirect()->route('admin.products')->with('status', 'Đã Xóa Thành Công!');
}
public function variant_store(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'size_id' => 'required',
        'color_id' => 'required',
        'quantity' => 'required|numeric',
    ]);

    ProductVariant::create([
        'product_id' => $request->product_id,
        'size_id'    => $request->size_id,
        'color_id'   => $request->color_id,
        'quantity'   => $request->quantity,
    ]);

    return back()->with('status', 'OK');
}
public function coupons(Request $request)
{
    $search = $request->search;

    if ($search) {
        $coupons = Coupon::where('code', 'LIKE', "%{$search}%")
                         ->orWhere('type', 'LIKE', "%{$search}%")
                         ->orWhere('value', 'LIKE', "%{$search}%")
                         ->orderBy('expiry_date', 'DESC')
                         ->paginate(12);
    } else {
        $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(12);
    }

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

public function coupon_update(Request $request)
{
    $request->validate([
        'code'=> 'required',
        'type'=> 'required',
        'value'=> 'required',
        'cart_value'=> 'required|numeric',
        'expiry_date'=> 'required|date'
    ]);

    $coupon = Coupon::find($request->id);
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

    $coupon->save();

    return redirect()->route('admin.coupons')->with('status', 'Đã Cập Nhật Thành Công!');
}
public function coupon_details($id)
{
    $coupon = \App\Models\Coupon::findOrFail($id);

    $orderQuery = \App\Models\Order::where('coupon_id', $id)
        ->with(['user', 'coupon']);

    $totalOrders = $orderQuery->count();
    $totalDiscount = $orderQuery->sum('discount');

    $orders = $orderQuery
        ->latest()
        ->paginate(12);

    return view('admin.coupons-details', compact(
        'coupon',
        'orders',
        'totalOrders',
        'totalDiscount'
    ));
}


public function coupon_delete($id)
{
    $coupon = Coupon::find($id);
    
    $coupon->delete();
    
    return redirect()->route('admin.coupons')->with('status', 'Đã Xóa Thành Công!');
}
    
public function orders(Request $request)
{
    $latestId = session('last_updated_order_id') ?? $request->highlight;
    $search = $request->search;

    $query = Order::where('status', '!=', 'pending');
    

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('id', 'LIKE', "%{$search}%")
              ->orWhere('name', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%")
              ->orWhere('total', 'LIKE', "%{$search}%");
        });
    }

    $orders = $query
        ->orderByRaw("
            CASE
                WHEN id = ? THEN 0
                ELSE 1
            END
        ", [$latestId])
        ->orderByDesc('id')
        ->paginate(12);

    session()->forget('last_updated_order_id');

    return view('admin.orders', compact('orders'));
}
public function order_details($order_id) {
    $order = Order::find($order_id);
    $orderItems = OrderItem::where('order_id', $order_id)
                    ->orderBy('id')
                    ->paginate(12);
    $transaction = Transaction::where('order_id', $order_id)->first();
    $addresses = Address::where('user_id', $order->user_id)->get();
    
    return view('admin.order-details', compact('order', 'orderItems', 'transaction','addresses'));
}
    

public function update_order_status(Request $request)
{
    $order = Order::find($request->order_id);

    if (!$order) {
        return back()->with('error', 'Không tìm thấy đơn hàng!');
    }

    $oldStatus = $order->status;
    $newStatus = $request->order_status;

    $order->status = $newStatus;
    switch ($newStatus) {
        case 'confirmed':
            $order->confirmed_date = Carbon::now();
            break;
        case 'processing':
            $order->processing_date = Carbon::now();
            break;
        case 'shipping':
            $order->shipping_date = Carbon::now();
            break;
        case 'delivered':
            $order->delivered_date = Carbon::now();
            break;
        case 'completed':
            $order->completed_date = Carbon::now();
            break;
        case 'canceled':
            $order->canceled_date = Carbon::now();
            break;
        case 'returned':
            $order->returned_date = Carbon::now();
            break;
    }


    $orderItems = OrderItem::where('order_id', $order->id)->get();
                if ($newStatus == 'canceled' && $oldStatus != 'canceled') {

                    foreach ($orderItems as $item) {
                        $product = Product::find($item->product_id);
            $variant = ProductVariant::where('product_id', $item->product_id)
               ->where('size_id', $item->size_id)
               ->where('color_id', $item->color_id)
                ->first();

            if ($variant) {
                $variant->quantity += $item->quantity;
                $variant->save();
            }
        }
    }

    $order->save();

    $transaction = Transaction::where('order_id', $order->id)->first();

    if ($transaction) {
    if ($newStatus == 'delivered') {
        $transaction->status = 'approved';
    } 
    elseif ($newStatus == 'canceled') {
        $transaction->status = 'declined';
    }
    elseif ($newStatus == 'returned') {
        $transaction->status = 'refunded';
    }

    $transaction->save();
}

    session(['last_updated_order_id' => $order->id]);
    return redirect()->route('admin.orders', ['highlight' => $order->id])
        ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
}

public function order_invoice($order_id)
{
    $order = Order::with('orderItems.product')->findOrFail($order_id);
    $transaction = Transaction::where('order_id', $order->id)->first();

    $isPaid = false;

    if ($transaction) {
        if ($transaction->mode == 'vnpay' && $transaction->status == 'approved') {
            $isPaid = true;
        }

        if ($transaction->mode == 'cod' && $transaction->status == 'approved') {
            $isPaid = true;
        }
    }
    return view('admin.invoice', compact('order', 'transaction', 'isPaid'));
}
public function slides(Request $request) {
    $search = $request->search;

    if ($search) {
        $slides = Slide::where('title', 'LIKE', "%{$search}%")
                      ->orWhere('tagline', 'LIKE', "%{$search}%")
                      ->orWhere('subtitle', 'LIKE', "%{$search}%")
                      ->orWhere('link', 'LIKE', "%{$search}%")
                      ->orderBy('id', 'DESC')
                      ->paginate(10);
    } else {
        $slides = Slide::orderBy('id', 'DESC')->paginate(10);
    }

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
public function contacts(Request $request) {
    $search = $request->search;

    if ($search) {
        $contacts = Contact::where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('subject', 'LIKE', "%{$search}%")
              ->orWhere('message', 'LIKE', "%{$search}%");
        })
        ->orderBy('created_at', 'DESC')
        ->paginate(10);
    } else {
        $contacts = Contact::orderBy('created_at', 'DESC')->paginate(10);
    }

    return view('admin.contacts', compact('contacts'));
}

public function contact_delete($id) {
    $contact = Contact::find($id);
    $contact->delete();
    return redirect()->back()->with('status', 'Đã xóa thành công!');
}
public function search(Request $request)
{
    $query = trim($request->input('query'));
    $context = $request->input('context', 'products');

    if ($query === '') {
        return response()->json([]);
    }

    if ($context === 'users') {
        $results = User::where(function ($q) use ($query) {
            $q->whereRaw('name LIKE ? COLLATE utf8mb4_unicode_ci', ["%{$query}%"])
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('mobile', 'LIKE', "%{$query}%");
        })->take(8)->get(['id', 'name', 'email']);
    } else {
        $results = Product::where(function ($q) use ($query) {
            $q->whereRaw('name LIKE ? COLLATE utf8mb4_unicode_ci', ["%{$query}%"])
              ->orWhereRaw('SKU LIKE ? COLLATE utf8mb4_unicode_ci', ["%{$query}%"])
              ->orWhereRaw('slug LIKE ? COLLATE utf8mb4_unicode_ci', ["%{$query}%"]);
        })->take(8)->get();
    }

    return response()->json($results);
}
 public function users(Request $request) {
        $search = $request->search;

        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('mobile', 'LIKE', "%{$search}%")
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
        } else {
            $users = User::orderBy('id', 'DESC')->paginate(10);
        }

        return view('admin.users', compact('users'));
    }

public function users_add()
{
    return view('admin.users-add');
}

public function users_store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'mobile'   => 'nullable|string|max:20',
        'password' => 'required|min:6',
        'utype'    => 'required|in:ADM,USR',
    ]);

    if ($request->is_root == 1) {
        return back()->with('error', 'Chỉ có 1 admin gốc!');
    }

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'mobile'   => $request->mobile,
        'password' => Hash::make($request->password),
        'utype'    => $request->utype,
        'is_root'  => 0
    ]);

    return redirect()->route('admin.users')
                     ->with('status', 'Thêm người dùng thành công!');
}

public function users_edit($id)
{
    $user = User::findOrFail($id);

    if ($user->is_root) {
        return redirect()->route('admin.users')
            ->with('error', 'Không thể chỉnh sửa admin này');
    }

    return view('admin.users-edit', compact('user'));
}

public function users_update(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email,'.$request->id,
        'mobile'   => 'nullable|string|max:20|unique:users,mobile,'.$request->id,
        'password' => 'nullable|min:6',
        'utype'    => 'required|in:ADM,USR',
    ]);

    $user = User::findOrFail($request->id);
    if ($user->is_root) {
        return back()->with('error', 'Không thể sửa admin này');
    }
    $user->name   = $request->name;
    $user->email  = $request->email;
    $user->mobile = $request->mobile;
    $user->utype  = $request->utype;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.users')
                     ->with('status', 'Cập nhật người dùng thành công!');
}


public function users_delete($id) {
    $user = User::findOrFail($id);


    if ($user->is_root) {
        return back()->with('error', 'Không thể xoá admin này');
    }


    if (auth()->id() == $user->id) {
        return back()->with('error', 'Không thể tự xoá tài khoản này');
    }


    if ($user->image && File::exists(public_path('uploads/users/'.$user->image))) {
        File::delete(public_path('uploads/users/'.$user->image));
    }


    $user->delete();

    return redirect()->route('admin.users')->with("status", "Xóa thành công!");
}
public function user_detail($id)
{
    $user = User::with([
        'addresses',
        'orders.items.product.category',
        'orders.items.product.brand'
    ])->findOrFail($id);

       if ($user->is_root) {
        return redirect()->route('admin.users')
            ->with('error', 'Không thể xem thông tin admin này!');
    }

    $orders = Order::where('user_id', $id)->latest()->paginate(10);

    $totalSpent = Order::where('user_id', $id)
        ->where('status', 'delivered')
        ->sum('total');

    $totalOrders = Order::where('user_id', $id)->count();

  $totalProducts = OrderItem::whereHas('order', function ($q) use ($id) {
    $q->where('user_id', $id)
      ->whereIn('status', ['delivered', 'completed']);
})->sum('quantity');

    $lastOrder = Order::where('user_id', $id)->latest()->first();

    $defaultAddress = $user->addresses->where('is_default', 1)->first();
    $otherAddresses = $user->addresses->where('is_default', 0);

    $avgOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

   $lastOrderDays = null;

if ($lastOrder) {
    $created = $lastOrder->created_at;

    $lastOrderDays = $created->isFuture()
        ? 0
        : $created->diffInDays(now());
}

    $customerType = match (true) {
        $totalSpent > 50000000 => 'VIP ',
        $totalOrders > 100 => 'VÀNG',
        $totalOrders > 30 => 'BẠC',
        default => 'NEW',
    };

    return view('admin.user-detail', compact(
        'user',
        'orders',
        'totalSpent',
        'totalOrders',
        'totalProducts',
        'avgOrderValue',
        'lastOrder',
        'lastOrderDays',
        'customerType',
        'defaultAddress',
        'otherAddresses'
    ));
}

public function blogs(Request $request)
{
    $search = $request->search;

    if ($search) {
        $blogs = Blog::where('title', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%")
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
    } else {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(10);
    }

    return view('admin.blogs', compact('blogs'));
}

public function blog_add()
{
    return view('admin.blog-add');
}
    
public function blog_store(Request $request)
{
    $request->validate([
        'title'     => 'required|string|max:255',
        'excerpt'   => 'nullable|string|max:500',
        'content'   => 'required',
        'category'  => 'nullable|string|max:100',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'status'    => 'required|in:0,1',
    ]);

    $blog = new Blog();
    $blog->title    = $request->title;
    $blog->slug     = Blog::generateSlug($request->title);
    $blog->excerpt  = $request->excerpt;
    $blog->content  = $request->content;
    $blog->category = $request->category;
    $blog->status   = $request->status;

    if ($request->hasFile('thumbnail')) {
        $file = time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
        $request->file('thumbnail')->move(public_path('uploads/blogs'), $file);
        $blog->thumbnail = $file;
    }

    $blog->save();

    return redirect()->route('admin.blogs')
                     ->with('status', 'Thêm bài viết thành công!');
}


public function blog_edit($id)
{
    $blog = Blog::findOrFail($id);
    return view('admin.blog-edit', compact('blog'));
}


public function blog_update(Request $request)
{
    $request->validate([
        'title'     => 'required|string|max:255',
        'excerpt'   => 'nullable|string|max:500',
        'content'   => 'required',
        'category'  => 'nullable|string|max:100',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'status'    => 'required|in:0,1',
    ]);

    $blog = Blog::findOrFail($request->id);
    $blog->title    = $request->title;
    $blog->excerpt  = $request->excerpt;
    $blog->category = $request->category;
    $blog->status   = $request->status;

    if ($request->hasFile('thumbnail')) {
        if ($blog->thumbnail) {
            @unlink(public_path('uploads/blogs/' . $blog->thumbnail));
        }
        $file = time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
        $request->file('thumbnail')->move(public_path('uploads/blogs'), $file);
        $blog->thumbnail = $file;
    }

    $blog->save();

    return redirect()->route('admin.blogs')
                     ->with('status', 'Cập nhật bài viết thành công!');
}

public function blog_delete($id)
{
    $blog = Blog::findOrFail($id);
    if ($blog->thumbnail) {
        @unlink(public_path('uploads/blogs/' . $blog->thumbnail));
    }
    $blog->delete();

    return redirect()->route('admin.blogs')
                     ->with('status', 'Xóa bài viết thành công!');
}


        public function trades(Request $request) {
            $search = $request->search;

            if ($search) {
                $trades = Trade::where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->orderBy('id', 'DESC')
                ->paginate(10);
            } else {
                $trades = Trade::orderBy('id', 'DESC')->paginate(10);
            }

            return view('admin.trades', compact('trades'));
        }
        public function reviews(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $reviews = Review::with('product')
                        ->where(function($q) use ($search) {
                            $q->where('customer_name', 'LIKE', "%{$search}%")
                              ->orWhere('customer_email', 'LIKE', "%{$search}%")
                              ->orWhere('review', 'LIKE', "%{$search}%")
                              ->orWhereHas('product', function($pq) use ($search) {
                                  $pq->where('name', 'LIKE', "%{$search}%");
                              });
                        })
                        ->latest()
                        ->paginate(10);
        } else {
            $reviews = Review::with('product')
                        ->latest()
                        ->paginate(10);
        }

        return view('admin.reviews', compact('reviews'));
    }
    public function review_update_status(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $review->status = $request->status;
        $review->save();

        return back()->with('status', 'Đã cập nhật đánh giá!');
    }

    public function review_approve_all()
    {
        Review::query()->update(['status' => 'approved']);

        return back()->with('status', 'Đã duyệt tất cả đánh giá.');
    }

    public function review_delete($id)
    {
        Review::findOrFail($id)->delete();
        return back();
    }
     public function product_stock(Request $request)
    {
        $query = Product::with(['Category', 'brand']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products', compact('products', 'categories', 'brands'));
    }

    public function trade()
    {

        return view('admin.trade');
    }


    public function stock(Request $request)
    {
        $search = $request->get('search');

        $sizes = Size::orderBy('name')->get();

        $products = Product::with(['variants.size', 'variants.color'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('SKU', 'like', '%' . $search . '%');
            })
            ->orderBy('SKU')
            ->get();

        $rows = [];

        foreach ($products as $product) {
            $byColor = $product->variants->groupBy('color_id');

            foreach ($byColor as $colorId => $variantGroup) {
                $colorName = $variantGroup->first()->color?->name ?? '—';
                $colorHex = $variantGroup->first()->color?->hex ?? null;

                $sizeQty = [];
                foreach ($variantGroup as $v) {
                    $sizeQty[$v->size_id] = $v->quantity;
                }

                $rows[] = [
                    'sku' => $product->SKU,
                    'name' => $product->name,
                    'color_name' => $colorName,
                    'color_hex' => $colorHex,
                    'size_qty' => $sizeQty,
                    'total' => $variantGroup->sum('quantity'),
                ];
            }
        }

        $colTotals = [];
        foreach ($sizes as $size) {
            $colTotals[$size->id] = collect($rows)->sum(fn($r) => $r['size_qty'][$size->id] ?? 0);
        }
        $grandTotal = collect($rows)->sum('total');

        return view('admin.stock', compact('sizes', 'rows', 'colTotals', 'grandTotal', 'search'));
    }


    public function transaction(Request $request)
    {
        $phone = $request->get('phone');
        $orders = collect();
        $customer = null;

        if ($phone) {
            $orders = Order::with(['orderItems.product', 'transaction'])
                ->where('phone', 'like', '%' . trim($phone) . '%')
                ->orderBy('created_at', 'desc')
                ->get();

            
            if ($orders->isNotEmpty()) {
                $first = $orders->first();
                $customer = [
                    'name' => $first->name,
                    'phone' => $first->phone,
                ];
            }
        }

        return view('admin.transaction', compact('orders', 'customer', 'phone'));
    }
        

}

