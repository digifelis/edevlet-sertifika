<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SuperadminController extends Controller
{
    //

    public function index()
    {
        return view('superadmin.superadmin');
    }
}
