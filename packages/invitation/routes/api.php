<?php

use Illuminate\Support\Facades\Route;
use RedJasmine\Invitation\UI\Http\Api\Controllers\InvitationCodeController;

/*
|--------------------------------------------------------------------------
| Invitation API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api/invitation')->group(function () {
    
    // 邀请码管理
    Route::prefix('codes')->group(function () {
        Route::get('/', [InvitationCodeController::class, 'index'])->name('invitation.codes.index');
        Route::post('/', [InvitationCodeController::class, 'store'])->name('invitation.codes.store');
        Route::get('/{code}', [InvitationCodeController::class, 'show'])->name('invitation.codes.show');
        Route::post('/{code}/use', [InvitationCodeController::class, 'use'])->name('invitation.codes.use');
        Route::post('/{code}/link', [InvitationCodeController::class, 'generateLink'])->name('invitation.codes.link');
        Route::patch('/{id}/disable', [InvitationCodeController::class, 'disable'])->name('invitation.codes.disable');
        Route::patch('/{id}/enable', [InvitationCodeController::class, 'enable'])->name('invitation.codes.enable');
    });
    
}); 