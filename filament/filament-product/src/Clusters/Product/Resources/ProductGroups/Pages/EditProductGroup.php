<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\ProductGroupResource;

class EditProductGroup extends EditRecord
{
    protected static string $resource = ProductGroupResource::class;


    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),
        ];
    }

    use ResourcePageHelper;
}
