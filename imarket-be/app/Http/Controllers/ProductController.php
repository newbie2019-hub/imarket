<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInfo;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $product = Product::whereRelation('store.partner', 'user_id', auth()->user()->id)->with(['product_info', 'product_info.category:id,category'])->latest()->get();
        return response()->json($product);
    }

    public function store(Request $request){
        $productInfo = ProductInfo::create([
            'image' => $request->image,
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category_id' => $request->category
        ]);

        $product = Product::create([
            'store_id' => auth()->user()->store->id,
            'product_info_id' => $productInfo->id
        ]);

        $product->load(['product_info', 'product_info.category']);

        return $this->success('Product saved successfully!', $product);
    }

    public function update(Request $request, $id){
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category_id' => $request->category
        ];

        if($request->image){
            $data['image'] = $request->image;
        }

        ProductInfo::where('id', $id)->first()->update($data);

        return $this->success('Product updated successfully!');
    }
    public function uploadImage(Request $request){
        if($request->image){
            $fileName = 'image'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $fileName);

            return response()->json($fileName);
        }
    }

    public function destroy($id){
        Product::destroy($id);
        return $this->success('Product has been removed successfully!');
    }

}
