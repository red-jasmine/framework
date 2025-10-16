<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages;

use Filament\Actions\DeleteAction;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWalletTransaction extends EditRecord
{
    protected static string $resource = WalletTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
