<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminArchivedProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $products = Product::onlyTrashed()->with(['product_info', 'store:id,name', 'product_info.category'])->latest()->get();
        return $this->success('Product has been retrieved successfully!', $products);
    }

    public function update(Request $request, $id){
        $products = Product::withTrashed()->where('id', $id)->first();
        $products->restore();
        $products->info()->restore();
        $products->load(['product_info','product_info.category']);
        return $this->success('Product has been restored successfully', $products);
    }

    public function destroy($id){
        Product::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('Product has been deleted permanently!');
    }
}
