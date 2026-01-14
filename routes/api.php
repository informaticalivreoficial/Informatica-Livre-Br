<?php

//use App\Http\Controllers\Api\PaghiperController;

use App\Http\Controllers\Webhooks\PagHiperWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::post('/request-notification', [PaghiperController::class, 'request'])->name('request-notification');

Route::post('webhooks/paghiper', PagHiperWebhookController::class)->name('webhooks.paghiper');
