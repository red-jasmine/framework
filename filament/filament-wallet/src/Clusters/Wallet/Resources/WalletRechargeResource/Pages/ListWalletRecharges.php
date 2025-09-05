<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\Pages;

use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWalletRecharges extends ListRecords
{
    protected static string $resource = WalletRechargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
