<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWallets extends ListRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
