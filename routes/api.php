<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CryptoPriceController;

Route::get('/crypto-prices', [CryptoPriceController::class, 'index']);
