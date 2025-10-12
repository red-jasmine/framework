<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeResource;

class EditProductAttribute extends EditRecord

{
    protected static string $resource = ProductAttributeResource::class;

    protected function getHeaderActions() : array
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
