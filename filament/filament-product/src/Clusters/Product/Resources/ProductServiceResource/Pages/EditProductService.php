<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductService extends EditRecord
{
    protected static string $resource = ProductServiceResource::class;
    use ResourcePageHelper;
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
