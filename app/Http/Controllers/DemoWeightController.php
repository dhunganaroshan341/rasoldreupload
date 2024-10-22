<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeightService;

class DemoWeightController extends Controller
{
    //
    public $weightService;
    public function __construct(WeightService $weightService){
        $this->weightService = $weightService;


    }
    public function get($input){
        $result =$input;
        $gram = $this->weightService->kgToGram($result);
        return response()->json($gram);

    }

}
