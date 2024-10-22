<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
class DemoRoutesController extends Controller
{
    //
    public function show($name){
        if (View::exists('components.' . $name)) {
            return view('demo_show', ['name' => $name]);

        }
        abort(404);

        // If the view doesn't exist, return a 404 error

}}
