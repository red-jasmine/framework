<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributes\ProductAttributeResource;

class EditProductAttribute extends EditRecord

{
    protected static string $resource = ProductAttributeResource::class;

    protected function getHeaderActions() : array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    use ResourcePageHelper;
}
