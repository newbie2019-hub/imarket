<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'latestProducts', 'searchProduct']]);
    }

    public function latestProducts(){
        $latest = Product::whereRelation('store', 'status', 'Approved')->withCount('rating')->with(['tags:id,product_id,tag','product_info:id,name,category_id,price,image', 'store'])->withAvg('rating', 'rating')->latest()->take(8)->get();
        return response()->json($latest);
    }

    public function index(Request $request){
        $products = Product::whereRelation('store', 'status', 'Approved')->withCount('rating')->with(['tags:id,product_id,tag','product_info:id,name,category_id,price,image', 'store'])->withAvg('rating', 'rating')->latest()->paginate(8);
        return response()->json($products);
    }

    public function searchProduct(Request $request){
        $products = Product::whereRelation('store', 'status', 'Approved')->whereRelation('product_info', 'name', 'like', '%'.$request->product.'%')->orWhereRelation('tags', 'tag', 'like', '%'.$request->product.'%')->withCount('rating')->with(['product_info:id,name,category_id,price,image', 'store', 'tags:id,product_id,tag'])->withAvg('rating', 'rating')->latest()->paginate(12);
        return response()->json($products);
    }
}
