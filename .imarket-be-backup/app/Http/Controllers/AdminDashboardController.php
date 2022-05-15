<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $latestProducts = Product::with(['store:id,name', 'product_info:id,quantity,name,price', 'product_info.category:id,category'])->latest()->take(15)->get();
        return response()->json(['latestProducts' => $latestProducts]);
    }
}
