<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Monday\MondayController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/form/submit', [MondayController::class,'getQuestionsList'])->name('form.submit');