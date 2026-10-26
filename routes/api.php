<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaghiperController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Webhooks\PagBankWebhookController;
use App\Http\Controllers\Webhooks\PagHiperWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('webhooks/paghiper', PagHiperWebhookController::class)->name('webhooks.paghiper');
Route::post('webhooks/paghiper-pedidos', [PaghiperController::class, 'request'])->name('webhooks.paghiper-pedidos');
Route::post('webhooks/pagbank', PagBankWebhookController::class)->name('webhooks.pagbank');

Route::middleware('api.token')->group(function () {
    Route::get('/companies/{uuid}/invoices', [InvoiceController::class, 'index']);
});

Route::post('/posts', [PostController::class, 'store']);
