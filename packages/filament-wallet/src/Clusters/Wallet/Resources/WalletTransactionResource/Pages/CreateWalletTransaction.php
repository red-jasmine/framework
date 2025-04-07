<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages;

use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWalletTransaction extends CreateRecord
{
    protected static string $resource = WalletTransactionResource::class;
}
