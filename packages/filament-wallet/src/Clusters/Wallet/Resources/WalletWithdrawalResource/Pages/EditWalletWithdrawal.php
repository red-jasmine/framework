<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletWithdrawalResource\Pages;

use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletWithdrawalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWalletWithdrawal extends EditRecord
{
    protected static string $resource = WalletWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
