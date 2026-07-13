<?php

use Illuminate\Support\Facades\Route;
//precisa de alteracao
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
