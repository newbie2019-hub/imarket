<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    public function index(){
        return response()->json(Store::with(['user.info:id,first_name,last_name'])->get());
    }

    public function destroy($id){
        Store::destroy($id);
        $store = Store::onlyTrashed()->where('id', $id)->first();
        $store->load(['user.info:id,first_name,last_name']);
        return $this->success('Store has been archived', $store);
    }
}
