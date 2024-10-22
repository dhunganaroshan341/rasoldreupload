<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // some comment made by me
    public function index(){
        return view('component.dashboard');
    }
    public function setup(){
        return view('component.setup');
    }
    public function invoice(){
        return view('component.invoice');
    }
}
