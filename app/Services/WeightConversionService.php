<?php

namespace App\Services;

class WeightConversionService
{
    public function ConvertFromPau($pau)
    {

        $dharni = $pau * 12;
        $bisauli = $dharni * 2;

        return ['pau' => $pau, 'kilo' => $kilo];
    }

    public function ConvertFromDharni($dharni)
    {
        $pau = 12 * $dharni;
        $kilo = 0.2 * $pau;

        return ['pau' => $pau, 'kilo' => $kilo];
    }
}
