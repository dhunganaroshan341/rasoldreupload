<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientService;

class GeneralApiController extends Controller
{
    //
    public function showTransactionsByClientService(ClientService $clientService)
    {
        try {
            //code...
            $transactions = $clientService->ledgers->get();

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
