<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    use ResourcePageHelper;

    protected function resolveRecord(int|string $key) : Model
    {
        $query          = FindQuery::make($key);
        $query->include = [ 'skus', 'info' ];
        $model          = app(static::getResource()::getQueryService())->findById($query);
        foreach ($model->info->getAttributes() as $key => $value) {
            $model->setAttribute($key, $model->info->{$key});
        }
        $model->setAttribute('skus', $model->skus->toArray());

        return $model;
    }

}
