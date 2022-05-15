<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index(){
        return response()->json(Product::with(['store:id,name', 'product_info', 'product_info.category'])->get());
    }

    public function destroy($id){
        Product::destroy($id);
        $user = Product::onlyTrashed()->where('id', $id)->first();
        $user->load(['store:id,name', 'product_info', 'product_info.category']);
        return $this->success('Product has been archived successfully!', $user);
    }
}
