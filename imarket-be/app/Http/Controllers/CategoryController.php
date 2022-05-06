<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index(){
        $categories = Category::get(['id', 'category', 'created_at', 'deleted_at']);
        return response()->json($categories);
    }
}
