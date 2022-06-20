<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){

        $areaChart = Order::where('created_at', '>=', now()->subDays(7))
        ->select(DB::raw('SUM(total) as total'), DB::raw('DATE(created_at) as date'))
        ->groupBy('date')
        ->get();
       
        return response()->json([
            'latestProducts' => Product::with(['store:id,name', 'product_info:id,quantity,name,price,image', 'product_info.category:id,category'])->withSum('orders', 'quantity')->latest()->take(15)->get(),
            'productsCount' => Product::count(),
            'storeCount' => Store::count(),
            'categCount' => Category::count(),
            'todaysOrderCount' => Order::whereDate('created_at', now())->count(),
            'userCount' => User::where('id', '<>', auth()->user()->id)->count(),
            'ridersCount' => User::role('Rider')->where('id', '<>', auth()->user()->id)->count(),
            'latestTransactions' => Order::with(['content.product.product_info', 'buyer:id,first_name,last_name'])->whereDate('created_at', '<=', now()->addDays(3))->get(),
            'areaChart' => $areaChart
        ]);
    }
}
