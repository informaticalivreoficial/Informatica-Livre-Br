<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaghiperController extends Controller
{
    public function request(Request $request)
    {
        return $request->all();
    }
}
