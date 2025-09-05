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
            'all'            => Tab::make()
                                   ->label(__('red-jasmine-product::product.scopes.all')),
            'on-sale'        => Tab::make()
                                   ->badge(static::getResource()::getEloquentQuery()->onSale()->count())
                                   ->label(__('red-jasmine-product::product.scopes.on-sale'))
                                   ->modifyQueryUsing(fn(Builder $query) => $query->onSale()),
            'sold-out'       => Tab::make()->label(__('red-jasmine-product::product.scopes.sold-out'))
                                   ->badge(static::getResource()::getEloquentQuery()->soldOut()->count())
                                   ->modifyQueryUsing(fn(Builder $query) => $query->soldOut()),
            'warehoused'     => Tab::make()->label(__('red-jasmine-product::product.scopes.warehoused'))
                                   ->badge(static::getResource()::getEloquentQuery()->warehoused()->count())
                                   ->modifyQueryUsing(fn(Builder $query) => $query->warehoused()),
//            'stock-alarming' => Tab::make()->label(__('red-jasmine-product::product.scopes.stock-alarming'))
//                                   ->badge(static::getResource()::getEloquentQuery()->stockAlarming()->count())
//                                   ->modifyQueryUsing(fn(Builder $query) => $query->stockAlarming()),

        ];
    }

    protected function getHeaderActions() : array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
