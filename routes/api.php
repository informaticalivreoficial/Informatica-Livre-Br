<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Webhooks\PagHiperWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('webhooks/paghiper', PagHiperWebhookController::class)->name('webhooks.paghiper');

Route::middleware('api.token')->group(function () {
    Route::get('/companies/{uuid}/invoices', [InvoiceController::class, 'index']);
});

Route::post('/posts', [PostController::class, 'store']);
