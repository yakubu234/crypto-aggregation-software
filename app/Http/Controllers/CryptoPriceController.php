<?php

namespace App\Http\Controllers;

use App\Services\CryptoPriceService;

class CryptoPriceController extends Controller
{

    /**
     * @var CryptoPriceService $cryptoPriceService
    */
    protected CryptoPriceService $cryptoPriceService;

    public function __construct(CryptoPriceService $cryptoPriceService)
    {
        $this->cryptoPriceService = $cryptoPriceService;
    }
    
    public function index()
    {
        return $this->cryptoPriceService->getCryptoPrices();
    }
}
