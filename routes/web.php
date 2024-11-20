<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-job', [ExampleController::class, 'triggerJob']);


