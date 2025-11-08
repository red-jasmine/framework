<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\BrandResource;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;
    use ResourcePageHelper;

    protected function getHeaderActions() : array
    {
        return [
            DeleteAction::make(),
        ];
    }


}
