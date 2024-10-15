<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    use ResourcePageHelper;



}
