<?php

use App\Http\Controllers\Api\PaghiperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/request-notification', [PaghiperController::class, 'request'])->name('request-notification');
