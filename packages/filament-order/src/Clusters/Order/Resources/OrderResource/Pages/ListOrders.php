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
            'wait_buyer_pay'           => Tab::make()->label(__('red-jasmine-order::order.scopes.wait_buyer_pay'))
                                             ->badge(static::getResource()::getEloquentQuery()->onWaitBuyerPay()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onWaitBuyerPay()),
            'wait_seller_accept'       => Tab::make()->label(__('red-jasmine-order::order.scopes.wait_seller_accept'))
                                             ->badge(static::getResource()::getEloquentQuery()->onWaitSellerAccept()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onWaitSellerAccept()),
            'wait_seller_send_goods'   => Tab::make()->label(__('red-jasmine-order::order.scopes.wait_seller_send_goods'))
                                             ->badge(static::getResource()::getEloquentQuery()->onWaitSellerSendGoods()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onWaitSellerSendGoods()),
            'wait_buyer_confirm_goods' => Tab::make()->label(__('red-jasmine-order::order.scopes.wait_buyer_confirm_goods'))
                                             ->badge(static::getResource()::getEloquentQuery()->onWaitBuyerConfirmGoods()->count())
                                             ->modifyQueryUsing(fn(Builder $query) => $query->onWaitBuyerConfirmGoods()),
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
