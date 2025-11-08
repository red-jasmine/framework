<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\ProductServiceResource;

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
