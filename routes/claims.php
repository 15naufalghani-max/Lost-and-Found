<?php

use App\Http\Controllers\ClaimController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('/claims/{claim}', [ClaimController::class, 'cancel'])->name('claims.cancel');
    Route::get('/claims/{claim}/proof', [ClaimController::class, 'proof'])->name('claims.proof');
});