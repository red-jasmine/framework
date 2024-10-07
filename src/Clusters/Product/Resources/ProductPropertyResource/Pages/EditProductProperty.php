<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyResource;

class EditProductProperty extends EditRecord

{
    protected static string $resource = ProductPropertyResource::class;

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
