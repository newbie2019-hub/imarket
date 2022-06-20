<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInfo;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    public function index(){
        $product = Product::whereRelation('store.partner', 'user_id', auth()->user()->id)->with(['tags:id,product_id,tag', 'product_info:id,name,category_id,price,image,quantity,description', 'product_info.category:id,category'])->latest()->get();
        return response()->json($product);
    }

    public function show($id){
        $product = Product::with([
            'store',
            'product_info:id,description,name,category_id,price,image,quantity', 
            'product_info.category:id,category', 
            'tags:id,product_id,tag',
            'rating.user_info'])
            ->where('id', $id)
            ->withCount('rating')
            ->withAvg('rating', 'rating')
            ->first(['id', 'store_id', 'product_info_id']);

        $relatedCategory = Product::where('id', '<>', $product->id)
        ->with([
            'store',
            'product_info:id,description,name,category_id,price,image', 
            'product_info.category:id,category', 'tags:id,product_id,tag'])
            ->whereRelation('store', 'status', 'Approved')
            ->whereRelation('product_info.category', 'id', $product->product_info->category_id)
            ->latest()->take(8)->get();

        $moreProducts = Product::where('id', '<>', $product->id)->with(['store','product_info:id,description,name,category_id,price,image', 'product_info.category:id,category', 'tags:id,product_id,tag'])->whereRelation('store', 'status', 'Approved')->whereRelation('store', 'id', $product->store->id)->latest()->take(8)->get();
        return response()->json(['product' => $product, 'relatedProduct' => $relatedCategory, 'moreProducts' => $moreProducts]);
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

    public function updateQuantity(Request $request, $id){
        
    }

    public function uploadImage(Request $request){
        if($request->image){
            $fileName = 'image'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $fileName);

            return response()->json($fileName);
        }

        if($request->banner){
            $fileName = 'image'.time().'.'.$request->banner->extension();
            $request->banner->move(public_path('images/banners'), $fileName);

            return response()->json($fileName);
        }
    }

    public function destroy($id){
        Product::destroy($id);
        return $this->success('Product has been removed successfully!');
    }

}
