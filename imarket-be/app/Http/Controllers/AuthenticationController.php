<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAccountRequest;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserInfo;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
    }

    public function userLogs(){
        return response()->json(UserLog::where('user_id', auth()->user()->id)->latest()->get());
    }
    public function store(UserAccountRequest $request){

        $userinfo = UserInfo::create([
            'first_name' => $request->validated(['first_name']),
            'last_name' => $request->validated(['last_name']),
            'gender' => $request->validated(['gender']),
            'contact_number' => $request->validated(['contact_number']),
            'birthday' => $request->validated(['birthday']),
        ]);

        $userAddress = UserAddress::create([
            'lat' => $request->lat,
            'lng' => $request->lng,
            'street_number' => $request->street_number,
            'route' => $request->route,
            'adminstrative_area_level_2' => $request->adminstrative_area_level_2,
            'adminstrative_area_level_1' => $request->adminstrative_area_level_1,
            'locality' => $request->locality,
            'country' => $request->country,
            'formatted_address' => $request->address,
        ]);

        $user = User::create([
            'user_info_id' => $userinfo->id,
            'user_address_id' => $userAddress->id,
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);


        $user->assignRole('User');
        
        return $this->success('Account created successfully!');
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or Password is incorrect!'], 401);
        }

        UserLog::create([
            'name' => 'Account Login',
            'event' => 'login',
            'user_id' => auth('api')->user()->id,
            'description' => 'You\'ve logged in your account'
        ]);

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = User::with(['info', 'roles', 'store', 'address'])->where('id', auth()->guard('api')->user()->id)->first();
        return response()->json($user);
    }

    public function logout()
    {
        UserLog::create([
            'name' => 'Account Logout',
            'event' => 'logout',
            'user_id' => auth('api')->id(),
            'description' => 'You\'ve logged out your account'
        ]);
        
        auth('api')->logout(true);
        return $this->success('Account logged out successfully!');
    }

    protected function respondWithToken($token)
    {
        $user =  User::with(['info'])->where('id', auth()->guard('api')->user()->id)->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
        ]);
    }

    
    public function update(UserAccountRequest $request){
        if (! Hash::check($request->current_password, $request->user()->password)) {
            return $this->error('Password entered is incorrect');
        }

        $userinfo = UserInfo::where('id', auth()->user()->id)->first();
        $userAddress = UserAddress::where('id', auth()->user()->id)->first();
        $userinfo->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
        ]);

        $userAddress->update([
            'lat' => $request->lat,
            'lng' => $request->lng,
            'street_number' => $request->street_number,
            'route' => $request->route,
            'adminstrative_area_level_2' => $request->adminstrative_area_level_2,
            'adminstrative_area_level_1' => $request->adminstrative_area_level_1,
            'locality' => $request->locality,
            'country' => $request->country,
            'formatted_address' => $request->address,
        ]);


        UserLog::create([
            'name' => 'Account Information Updated',
            'event' => 'update',
            'user_id' => auth('api')->user()->id,
            'description' => 'You\'ve updated your account information.'
        ]);

        return $this->success('Account updated successfully!');
    }

    public function deleteProfileImg(){
        $userinfo = UserInfo::where('id', auth()->user()->id)->first();

        if($userinfo->profile_img) {
            $this->deleteImage($userinfo->profile_img);

            $userinfo->update([
                'profile_img' => null
            ]);
        }

        UserLog::create([
            'name' => 'Account Image Deleted',
            'event' => 'delete',
            'user_id' => auth('api')->user()->id,
            'description' => 'You\'ve deleted your account profile image.'
        ]);

        return $this->success('Profile image has been removed!');
    }
    
    public function updateProfileImg(Request $request){
        if($request->img){
            $fileName = time().'.'.$request->img->extension();
            $request->img->move(public_path('images/profiles'), $fileName);

            $userinfo = UserInfo::where('id', auth()->user()->id)->first();

            if($userinfo->profile_img) {
                $this->deleteImage($userinfo->profile_img);
            }

            $userinfo->update([
                'profile_img' => $fileName
            ]);

            UserLog::create([
                'name' => 'Account Image Updated',
                'event' => 'update',
                'user_id' => auth('api')->user()->id,
                'description' => 'You\'ve updated your account profile image.'
            ]);

            return $this->success('Profile image updated successfully!');
        }
    }

    public function updateAddress(Request $request){
        $userAddress = UserAddress::where('id', auth()->user()->id)->first();

        $userAddress->update([
            'eta' => $request->eta,
            'total_distance' => $request->total_distance,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'street_number' => $request->street_number,
            'route' => $request->route,
            'adminstrative_area_level_2' => $request->adminstrative_area_level_2,
            'adminstrative_area_level_1' => $request->adminstrative_area_level_1,
            'locality' => $request->locality,
            'country' => $request->country,
            'formatted_address' => $request->address,
        ]);

        return $this->success('User Address has been updated!');
    }
    
    public function updatePassword(Request $request){
        if (! Hash::check($request->current_password, $request->user()->password)) {
            return $this->error('Password entered is incorrect');
        }

        $user = User::where('id', auth()->user()->id)->first();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return $this->success('Password updated successfully!');
    }

    
    public function deleteImage($img){
        $file = File::delete(public_path("images/profiles/".$img));
        return $file;
    }


}
