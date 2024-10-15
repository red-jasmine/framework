<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyValueResource;

class EditProductPropertyValue extends EditRecord
{
    protected static string $resource = ProductPropertyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    use ResourcePageHelper;
}
