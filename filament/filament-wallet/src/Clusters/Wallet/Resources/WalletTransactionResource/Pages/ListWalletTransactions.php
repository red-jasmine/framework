<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWalletTransactions extends ListRecords
{
    protected static string $resource = WalletTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
