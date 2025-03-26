<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/form/submit', [TestController::class,'submitForm'])->name('form.submit');