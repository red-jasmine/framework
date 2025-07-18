<?php

namespace RedJasmine\Wallet\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Wallet\UI\Http\User\Api\Controllers\WalletController;
use RedJasmine\Wallet\UI\Http\User\Api\Controllers\WalletRechargeController;
use RedJasmine\Wallet\UI\Http\User\Api\Controllers\WalletTransactionController;
use RedJasmine\Wallet\UI\Http\User\Api\Controllers\WalletWithdrawalController;

class WalletUserRoute
{
    public static function api() : void
    {
        Route::group(['prefix' => 'wallet'], function () {
            // 钱包相关路由
            Route::get('wallets/{type}', [WalletController::class, 'wallet']);
            // 钱包交易记录路由
            Route::get('transactions', [WalletTransactionController::class, 'index']);
            Route::get('transactions/{id}', [WalletTransactionController::class, 'show']);
            // 钱包提现路由
            Route::get('withdrawals', [WalletWithdrawalController::class, 'index']);
            Route::get('withdrawals/{id}', [WalletWithdrawalController::class, 'show']);
            Route::post('withdrawals', [WalletWithdrawalController::class, 'store']);
            // 钱包充值路由
            Route::get('recharges', [WalletRechargeController::class, 'index']);
            Route::get('recharges/{id}', [WalletRechargeController::class, 'show']);
            Route::post('recharges', [WalletRechargeController::class, 'store']);
        });
    }

    public static function web() : void
    {
        Route::group(['prefix' => 'wallet'], function () {
            // Web端路由（如果需要）
            Route::get('wallets', WalletController::class, 'index');
            Route::get('wallets/{type}', WalletController::class, 'showByType');
        });
    }
} 