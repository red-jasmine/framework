<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPropertyGroupResource;

class EditProductPropertyGroup extends EditRecord
{
    protected static string $resource = ProductPropertyGroupResource::class;

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
