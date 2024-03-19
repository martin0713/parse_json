<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParseController as Parse;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'parse'], function () {
    Route::get('/all', [Parse::class, 'all']);
});
