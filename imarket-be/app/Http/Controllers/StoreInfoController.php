<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $store = Store::where('user_id', auth()->user()->id)->first();
        return response()->json($store);
    }

    public function update(Request $request, $id){
        Store::where('id', $id)->first()->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'week_days_opening' => $request->week_days_opening,
            'week_days_closing' => $request->week_days_closing,
            'week_end_opening' => $request->week_end_opening,
            'week_end_closing' => $request->week_end_closing,
        ]);

        return $this->success('Store information has been updated successfully!');
    }
}
