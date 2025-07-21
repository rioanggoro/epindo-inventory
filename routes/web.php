<?php

use App\Http\Controllers\IncomingController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StockController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('incomings', IncomingController::class);
Route::resource('produksis', ProduksiController::class);
Route::resource('invoices', InvoiceController::class);
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'printPdf'])->name('invoices.printPdf');
Route::resource('stocks', StockController::class);