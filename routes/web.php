<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParseController as Parse;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(Parse::class)
    ->prefix('parse')
    ->group(function () {
        Route::get('/all', 'all');
        Route::get('/offers', 'offers');
    });
