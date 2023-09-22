<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    /**
     * index
     *
     * @return void
     */
    public function index(){
        return view('admin');
    }
}
