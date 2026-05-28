<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Support\Facades\Hash;
class ManaController extends Controller
{
        protected $fillable = ['name', 'email', 'mobile', 'password', 'utype'];
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

        return view('mana.index', compact(
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
}
