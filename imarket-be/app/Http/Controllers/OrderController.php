<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\ProductInfo;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['content'])->where('user_id', auth()->user()->id)->latest()->paginate(10);
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $cart = Cart::with(['cart_info', 'cart_info.product', 'cart_info.product.product_info'])->where('user_id', auth()->user()->id)->withSum('cart_info', 'subtotal')->first();

        $order = Order::create([
            'transaction_id' => $request->uuid,
            'user_id' => $request->user_id,
            'subtotal' => $request->cart_info_sum_subtotal,
            'delivery_fee' => 100,
            'total' => $request->cart_info_sum_subtotal + 100,
            'status' => 'Pending'
        ]);

        foreach($cart->cart_info as $cartItems){
            $orderInfo = OrderInfo::create([
                'order_id' => $order->id,
                'product_id' => $cartItems->product_id,
                'subtotal' => $cartItems->subtotal,
                'quantity' => $cartItems->quantity
            ]);

            $product = ProductInfo::find($cartItems->product_id);

            $product->update([
                'quantity' => $product->quantity - $cartItems->quantity
            ]);
        }

        $cart->delete();

        return $this->success('Checkout success! A delivery rider will accept your order in a moment');
       
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
