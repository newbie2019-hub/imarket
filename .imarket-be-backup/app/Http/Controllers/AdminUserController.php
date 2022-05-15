<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(){
        return response()->json(User::where('id', '<>', auth()->user()->id)->with(['info', 'roles'])->get());
    }

    public function destroy($id){
        User::destroy($id);
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->load(['info', 'roles']);
        return $this->success('User account has been archived', $user);
    }
}
