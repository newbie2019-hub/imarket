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
        $latestProducts = Product::with(['store:id,name', 'product_info:id,quantity,name,price', 'product_info.category:id,category'])->latest()->take(15)->get();
        $storeCount = Store::count();
        $productCount = Product::count();
        $categCount = Category::count();
        $todaysOrder = Order::where('created_at', now())->count();
        $userCount = User::where('id', '<>', auth()->user()->id)->count();
        
        return response()->json([
            'latestProducts' => $latestProducts,
            'productsCount' => $productCount,
            'storeCount' => $storeCount,
            'categCount' => $categCount,
            'todaysOrderCount' => $todaysOrder,
            'userCount' => $userCount
        ]);
    }
}
