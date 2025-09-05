<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Pages;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    use ResourcePageHelper;


    public function getTabs() : array
    {
        return [
            'all'                      => Tab::make()->label(__('red-jasmine-order::order.scopes.all')),
            'paying'           => Tab::make()->label(__('red-jasmine-order::order.scopes.paying'))
                                             ->badge(static::getResource()::getEloquentQuery()->onPaying()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onPaying()),
            'accepting'       => Tab::make()->label(__('red-jasmine-order::order.scopes.accepting'))
                                             ->badge(static::getResource()::getEloquentQuery()->onAccepting()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onAccepting()),
            'shipping'   => Tab::make()->label(__('red-jasmine-order::order.scopes.shipping'))
                                             ->badge(static::getResource()::getEloquentQuery()->onShipping()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onShipping()),
            'confirming' => Tab::make()->label(__('red-jasmine-order::order.scopes.confirming'))
                                             ->badge(static::getResource()::getEloquentQuery()->onConfirming()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onConfirming()),
            'finished'                 => Tab::make()->label(__('red-jasmine-order::order.scopes.finished'))
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onFinished()),
            'cancel-closed'            => Tab::make()->label(__('red-jasmine-order::order.scopes.cancel-closed'))
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onCancelClosed()),
        ];
    }

    protected function getHeaderActions() : array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
