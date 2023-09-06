<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SuperadminController extends Controller
{
    //

    public function index()
    {
        dd(Auth::user()->userType);
        return view('superadmin');
    }
}
