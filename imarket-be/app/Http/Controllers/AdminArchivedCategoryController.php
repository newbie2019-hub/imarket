<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AdminArchivedCategoryController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $category = Category::onlyTrashed()->withCount(['products'])->get();
        return $this->success('User accounts has been retrieved successfully!', $category);
    }

    public function update(Request $request, $id){
        $category = Category::withTrashed()->withCount(['products'])->where('id', $id)->first();
        $category->restore();
        // $category->product_info->restore();
        // $user->load(['info']);
        return $this->success('User account has been restored successfully', $category);
    }

    public function destroy($id){
        Category::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('Category has been deleted permanently');
    }
}
