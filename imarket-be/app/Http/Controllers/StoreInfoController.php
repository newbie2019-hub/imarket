<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

    public function show(Request $request, $id){
        $store = Store::where('status', 'Approved')->where('id', $id)->first();
        
        if($store){
            $products = Product::where('store_id', $id)->whereRelation('store', 'status', 'Approved')->withCount('rating')->with(['product_info:id,name,category_id,price,image', 'store'])->withAvg('rating', 'rating')->latest()->get();
            $data = [
                'store' => $store,
                'products' => $products
            ];
        }
        else {
            return $this->error('Store you\'ve selected isn\'t approved or non-existent');
        }

        return $this->success('Store retrieved successfully!', $data);
    }

    public function update(Request $request, $id){
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'week_days_opening' => $request->week_days_opening,
            'week_days_closing' => $request->week_days_closing,
            'week_end_opening' => $request->week_end_opening,
            'week_end_closing' => $request->week_end_closing,
        ];

        if($request->image){
            $data['banner_image'] = $request->image;
        }

        Store::where('id', $id)->first()->update($data);

        return $this->success('Store information has been updated successfully!');
    }

    
    public function updateBannerImg(Request $request){
        if($request->banner){
            $fileName = time().'.'.$request->banner->extension();
            $request->banner->move(public_path('images/banners/'), $fileName);

            $userinfo = Store::where('user_id', auth()->user()->id)->first();

            if($userinfo->banner_image) {
                $this->deleteImage($userinfo->banner_image);
            }

            $userinfo->update([
                'banner_image' => $fileName
            ]);

            UserLog::create([
                'name' => 'Store Banner image has been updated',
                'event' => 'update',
                'user_id' => auth('api')->user()->id,
                'description' => 'You\'ve updated your store\'s banner image.'
            ]);

            return $this->success('Store Banner image updated successfully!');
        }
    }

    
    public function deleteImage($img){
        $file = File::delete(public_path("images/profiles/".$img));
        return $file;
    }
}
