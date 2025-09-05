<?php

namespace RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource\Pages;

use RedJasmine\FilamentWallet\Clusters\Wallet\Resources\WalletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWallet extends EditRecord
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
