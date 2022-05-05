<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAccountRequest;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
    }

    public function store(UserAccountRequest $request){

        $userinfo = UserInfo::create([
            'first_name' => $request->validated(['first_name']),
            'last_name' => $request->validated(['last_name']),
            'address' => $request->validated(['address']),
            'gender' => $request->validated(['gender']),
            'contact_number' => $request->validated(['contact_number']),
            'birthday' => $request->validated(['birthday']),
        ]);

        $user = User::create([
            'user_info_id' => $userinfo->id,
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

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = User::with(['info'])->where('id', auth()->guard('api')->user()->id)->first();
        return response()->json($user);
    }

    public function logout()
    {
        auth()->logout();
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

}