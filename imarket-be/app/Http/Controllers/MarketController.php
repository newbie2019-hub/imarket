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
        $latest = Product::withCount('rating')->with(['product_info', 'user:id,user_info_id', 'user.info:id,last_name'])->withAvg('rating', 'rating')->latest()->take(8)->get();
        return response()->json($latest);
    }

    public function index(Request $request){
        $products = Product::withCount('rating')->with(['product_info', 'user:id,user_info_id', 'user.info:id,last_name'])->withAvg('rating', 'rating')->latest()->paginate(8);
        
        return response()->json($products);
    }

    public function searchProduct(Request $request){
        $products = Product::whereRelation('product_info', 'name', '%'.$request->search.'%')->withCount('rating')->with(['product_info', 'user:id,user_info_id', 'user.info:id,last_name'])->withAvg('rating', 'rating')->latest()->paginate(8);
        return response()->json($products);
    }
}
