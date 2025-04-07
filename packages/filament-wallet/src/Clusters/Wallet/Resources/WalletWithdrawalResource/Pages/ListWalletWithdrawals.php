<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletWithdrawalResource\Pages;

use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletWithdrawalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWalletWithdrawals extends ListRecords
{
    protected static string $resource = WalletWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
