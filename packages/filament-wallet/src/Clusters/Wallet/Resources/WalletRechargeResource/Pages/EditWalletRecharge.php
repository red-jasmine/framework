<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource\Pages;

use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletRechargeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWalletRecharge extends EditRecord
{
    protected static string $resource = WalletRechargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
