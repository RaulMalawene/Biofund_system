<?php

use Illuminate\Support\Facades\Route;

Route::get('/submeterReclamacao', function () {
    return view('submeterReclamacao');
});


Route::get('/visualizarReclamacao', function () {
    return view('visualizarReclamacao');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
