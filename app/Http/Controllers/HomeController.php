<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Slide;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
  
    public function index()
    {
         if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.index');
    }
        
   $slides = Slide::where('status', 1)->take(3)->get();
   $categories = Category::orderBy('name')->get();
   $sproducts = Product::with('variants')->whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->take(8)->get();
   $fproducts = Product::with('variants')->where('featured', 1)->take(8)->get();
   return view('index', compact('slides', 'categories', 'sproducts','fproducts'));
    }
    public function contact() 
    {
    return view('contact');
}

public function contact_store(Request $request) {
    $request->validate([
        'name' => 'required|max:100',
        'email' => 'required|email',
        'phone' => 'required|numeric|digits:10',
        'comment' => 'required'
    ]);

    $contact = new Contact();
    $contact->name = $request->name;
    $contact->email = $request->email;
    $contact->phone = $request->phone;
    $contact->comment = $request->comment;
    $contact->save();

    return redirect()->back()->with('success', 'Tin nhắn của bạn đã được gửi thành công!');
}

public function search(Request $request)
{
    $query = trim($request->input('query', ''));

    if ($query === '') {
        return response()->json([]);
    }

    $results = Product::select('id', 'name', 'slug', 'SKU', 'image')
        ->where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('SKU', 'LIKE', "%{$query}%")
              ->orWhere('slug', 'LIKE', "%{$query}%");
        })
        ->take(8)
        ->get();

    return response()->json($results);
}
public function blog_detail($slug)
{
    $blog = Blog::where('slug', $slug)->where('status', 1)->firstOrFail();
    $related = Blog::where('status', 1)
                   ->where('id', '!=', $blog->id)
                   ->where('category', $blog->category)
                   ->take(3)->get();
    return view('blog_detail', compact('blog', 'related'));
}

public function blogs(Request $request)
{
    $query = Blog::where('status', 1)->orderBy('created_at', 'desc');

    if ($request->category) {
        $query->where('category', $request->category);
    }

    $blogs = $query->paginate(9)->withQueryString();
    return view('blogs', compact('blogs'));
}
    
}

