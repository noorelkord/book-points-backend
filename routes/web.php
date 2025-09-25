<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
     return response()->json(['message' => 'Book Points API is running', 'hint' => 'Try /api/ping'], 200);
});

