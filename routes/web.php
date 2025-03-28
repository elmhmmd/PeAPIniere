<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/swagger', function () {
    return view('swagger');
});

Route::get('/swagger/swagger.json', function () {
    return response()->file(public_path('swagger/swagger.json'));
});
