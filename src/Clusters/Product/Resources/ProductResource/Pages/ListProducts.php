<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;


    public function getTabs() : array
    {
        return [
            'all'       => Tab::make()->label(__('red-jasmine-product::product.scopes.all')),
            'sale'      => Tab::make()->label(__('red-jasmine-product::product.scopes.sale'))->modifyQueryUsing(fn(Builder $query) => $query->sale()),
            'off_shelf' => Tab::make()->label(__('red-jasmine-product::product.scopes.off_shelf'))->modifyQueryUsing(fn(Builder $query) => $query->offShelf()),
            'draft'     => Tab::make()->label(__('red-jasmine-product::product.scopes.draft'))->modifyQueryUsing(fn(Builder $query) => $query->draft()),

        ];
    }

    protected function getHeaderActions() : array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
