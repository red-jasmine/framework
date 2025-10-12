<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroupResource;

class EditProductAttributeGroup extends EditRecord
{
    protected static string $resource = ProductAttributeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
