<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
Route::get('/', function () {
    return view('welcome');
});
