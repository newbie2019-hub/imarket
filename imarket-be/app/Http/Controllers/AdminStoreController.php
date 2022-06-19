<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    public function index(){
        return response()->json(Store::with(['user.info:id,first_name,last_name'])->withCount(['products'])->get());
    }

    public function destroy($id){
        Store::destroy($id);
        $store = Store::onlyTrashed()->where('id', $id)->withCount(['products'])->first();
        $store->load(['user.info:id,first_name,last_name']);
        return $this->success('Store has been archived', $store);
    }

    public function approveStore($id){
        $store = Store::where('id', $id)->first();
        if($store){
            $store->update([
                'status' => 'Approved'
            ]);
        }
        else {
            return $this->error('Something went wrong!');
        }

        return $this->success('Store has been approved!');
    }
}
