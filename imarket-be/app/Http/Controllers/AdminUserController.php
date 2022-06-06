<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(){
        $users = User::where('id', '<>', auth()->user()->id)->with(['info', 'roles', 'address'])->get();
        $new_users = User::whereDate('created_at', now())->count();
        return response()->json(['users' => $users, 'new_users' => $new_users]);
    }

    public function destroy($id){
        User::destroy($id);
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->load(['info', 'roles', 'address']);
        return $this->success('User account has been archived', $user);
    }
}
