<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
