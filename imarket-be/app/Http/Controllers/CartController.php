<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartContent;
use App\Models\ProductInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index() {
        $cart = Cart::with(['cart_info', 'cart_info.product', 'cart_info.product.product_info'])->where('user_id', auth()->user()->id)->withSum('cart_info', 'subtotal')->first();
        return response()->json($cart);
    }

    public function cartCount() {
        $cart = Cart::withCount(['cart_info'])->where('user_id', auth()->user()->id)->first();
        return response()->json($cart);
    }

    public function store(Request $request){

        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $createdCart = '';
        if(empty($cart)){
            $createdCart = Cart::create([
                'uuid' => Str::uuid(),
                'user_id' => auth()->user()->id
            ]);
        }

        $productExist = Cart::with(['cart_info'])->whereRelation('cart_info', 'product_id', $request->id)->where('user_id', auth()->user()->id)->first();
        
        if(empty($productExist)){
            if($cart){
                CartContent::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->id,
                    'subtotal' => $request->product_info['price'] * ($request->qty ? $request->qty : 1),
                    'quantity' => $request->qty ? $request->qty : 1
                ]);
            }
            else {
                CartContent::create([
                    'cart_id' => $createdCart->id,
                    'product_id' => $request->id,
                    'subtotal' => $request->product_info['price'] * 1,
                    'quantity' => $request->qty ? $request->qty : 1
                    
                ]);
            }
        } 
        else {
            $product = CartContent::where('product_id', $request->id)->first();
            $product->update([
                'quantity' => $product->quantity + 1,
                'subtotal' => $request->product_info['price'] * ($product->quantity + 1)
            ]);

        }

        $cart = Cart::with(['cart_info', 'cart_info.product', 'cart_info.product.product_info'])->where('user_id', auth()->user()->id)->first();

        return response()->json($cart);
    }

    public function updateProductQuantity(Request $request, $cartID, $productID){
        $cartProduct = CartContent::where('cart_id', $cartID)->where('product_id', $productID)->first();
        $productInfo = ProductInfo::where('id', $productID)->first();

        $cartProduct->update([
            'quantity' => $request->quantity,
            'subtotal' => $productInfo->price * $request->quantity
        ]);

        return $this->success('Quantity updated successfully!');
    }

    public function destroyCartItem($id) {
        CartContent::destroy($id);
        return $this->success('Item has been removed from your cart');
    }

    public function destroy($id) {
        CartContent::where('cart_id', $id)->delete();
        return $this->success('All cart items was removed successfully!');
    }
}
