<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['code' => 0, 'message' => 'ok']);
});


