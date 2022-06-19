<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        return response()->json(Category::withCount('products')->get());
    }

    public function update(Request $request, $id){
        $category = Category::where('id', $id)->first();
        
        $category->update([
            'category' => $request->category
        ]);

        return $this->success('Category updated successfully!');
    }

    public function destroy($id){
        Category::destroy($id);
        $category = Category::onlyTrashed()->where('id', $id)->withCount(['products'])->first();
        return $this->success('Category and its product has been archived', $category);
    }
}
