<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductTag extends EditRecord
{
    protected static string $resource = ProductTagResource::class;
    use ResourcePageHelper;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
