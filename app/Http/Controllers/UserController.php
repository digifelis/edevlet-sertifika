<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return view('user');
    }
}
