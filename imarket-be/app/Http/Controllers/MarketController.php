<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'latestProducts']]);
    }

    public function latestProducts(){
        $latest = Product::with(['product_info', 'user:id,user_info_id', 'user.info:id,last_name'])->latest()->take(8)->get();
     
        return response()->json($latest);
    }

    public function index(Request $request){
        $products = Product::whereHas('product_info', function($query){
            $query->orderBy('price', 'DESC');
        })->with(['product_info', 'user:id,user_info_id', 'user.info:id,last_name'])->latest()->paginate(10);
        
        return response()->json($products);
    }
}
