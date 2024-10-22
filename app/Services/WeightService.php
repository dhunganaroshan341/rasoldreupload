<?php
namespace App\Services;
class WeightService{
    public function kgToGram(float $input){
        return $input * 1000;
    }


}
