<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Store;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PartnershipController extends Controller
{
    public function store(Request $request){
        $user = $this->createAccount($request->all());
        $partner = Partner::create([
            'user_id' => $user->id,
            'request_date' => Carbon::now(),
        ]);

        $store = Store::create([
            'partner_id' => $partner->id,
            'user_id' => $user->id,
            'name' => $request->store_name,
            'description' => $request->store_description,
            'address' => $request->store_address,
            'week_days_opening' => $request->week_days_time_am,
            'week_days_closing' => $request->week_days_time_pm,
            'week_end_opening' => $request->week_ends_time_am,
            'week_end_closing' => $request->week_ends_time_pm,
            'status' => 'For Approval'
        ]);

        return $this->success('Registration successful! Please wait for the approval of your account.');
    }

    public function createAccount($data){
        $userinfo = UserInfo::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'contact_number' => $data['contact_number'],
            'birthday' => $data['birthday'],
        ]);


        $userAddress = UserAddress::create([
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'street_number' => $data['street_number'],
            'route' => $data['route'],
            'adminstrative_area_level_2' => $data['adminstrative_area_level_2'],
            'adminstrative_area_level_1' => $data['adminstrative_area_level_1'],
            'locality' => $data['locality'],
            'country' => $data['country'],
            'formatted_address' => $data['address'],
        ]);

        $user = User::create([
            'user_info_id' => $userinfo->id,
            'user_address_id' => $userAddress->id,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('Seller');
        
        return $user;
    }
}
