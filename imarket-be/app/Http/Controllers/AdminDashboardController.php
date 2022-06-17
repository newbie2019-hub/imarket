<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $latestProducts = Product::with(['store:id,name', 'product_info:id,quantity,name,price', 'product_info.category:id,category'])->withSum('orders', 'quantity')->latest()->take(15)->get();
        $storeCount = Store::count();
        $productCount = Product::count();
        $categCount = Category::count();
        $todaysOrder = Order::whereDate('created_at', now())->count();
        $userCount = User::where('id', '<>', auth()->user()->id)->count();
        $latestTransaction = Order::with(['content.product.product_info', 'buyer:id,first_name,last_name'])->whereDate('created_at', '<=', now()->addDays(3))->get();
        return response()->json([
            'latestProducts' => $latestProducts,
            'productsCount' => $productCount,
            'storeCount' => $storeCount,
            'categCount' => $categCount,
            'todaysOrderCount' => $todaysOrder,
            'userCount' => $userCount,
            'latestTransactions' => $latestTransaction
        ]);
    }
}
