<?php

use App\Jobs\FetchCryptoPricesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$interval = (int)config('services.crypto_fetch_interval') ?? 60; // Default: 60 seconds; 
match ($interval) {
    1 => Schedule::job(new FetchCryptoPricesJob())->everySecond()->withoutOverlapping(),
    5 => Schedule::job(new FetchCryptoPricesJob())->everyFiveSeconds()->withoutOverlapping(),
    10 => Schedule::job(new FetchCryptoPricesJob())->everyTenSeconds()->withoutOverlapping(),
    30 => Schedule::job(new FetchCryptoPricesJob())->everyThirtySeconds()->withoutOverlapping(),
    60 => Schedule::job(new FetchCryptoPricesJob())->everyMinute()->withoutOverlapping(),
    default => Schedule::job(new FetchCryptoPricesJob())->everyFiveSeconds()->withoutOverlapping(), // Default
};