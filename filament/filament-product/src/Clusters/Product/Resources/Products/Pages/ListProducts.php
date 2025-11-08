<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Pages;

use Filament\Actions\CreateAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\ProductResource;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;


    public function getTabs() : array
    {
        return [
            'all'            => Tab::make()
                                   ->label(__('red-jasmine-product::product.scopes.all')),
            'available'        => Tab::make()
                                   ->badge(static::getResource()::getEloquentQuery()->available()->count())
                                   ->label(__('red-jasmine-product::product.scopes.available'))
                                   ->modifyQueryUsing(fn(Builder $query) => $query->available()),
            'warehoused'     => Tab::make()->label(__('red-jasmine-product::product.scopes.warehoused'))
                                   ->badge(static::getResource()::getEloquentQuery()->warehoused()->count())
                                   ->modifyQueryUsing(fn(Builder $query) => $query->warehoused()),
        ];
    }

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
