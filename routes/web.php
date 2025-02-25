<?php

use App\Http\Controllers\CryptoPriceController;
use App\Livewire\CryptoPrices;
use Illuminate\Support\Facades\Route;

Route::get('/', CryptoPrices::class);