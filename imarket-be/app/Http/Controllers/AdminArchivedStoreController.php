<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class AdminArchivedStoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $stores = Store::onlyTrashed()->with(['user.info:id,first_name,last_name'])->latest()->get();
        return $this->success('Store has been retrieved successfully!', $stores);
    }

    public function update(Request $request, $id){
        $store = Store::withTrashed()->where('id', $id)->first();
        $store->restore();
        $store->load(['user.info:id,first_name,last_name', 'product']);
        $store->product()->restore();
        return $this->success('Store and its data has been restored successfully', $store);
    }

    public function destroy($id){
        Store::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('Store has been deleted permanently');
    }
}
