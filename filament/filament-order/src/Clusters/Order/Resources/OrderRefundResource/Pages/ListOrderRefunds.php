<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderRefunds extends ListRecords
{
    protected static string $resource = OrderRefundResource::class;
    use ResourcePageHelper;

    public function getTabs() : array
    {
        return [
            'all'                 => Tab::make()->label(__('red-jasmine-order::refund.scopes.all')),
            'wait_seller_agree'   => Tab::make()->label(__('red-jasmine-order::refund.scopes.wait_seller_handle'))
                                        ->badge(static::getResource()::getEloquentQuery()->waitSellerHandle()->count())
                                        ->modifyQueryUsing(fn(Builder $query) => $query->waitSellerHandle()),
            'wait_seller_confirm' => Tab::make()->label(__('red-jasmine-order::refund.scopes.wait_seller_confirm'))
                                        ->badge(static::getResource()::getEloquentQuery()->waitSellerConfirm()->count())
                                        ->modifyQueryUsing(fn(Builder $query) => $query->waitSellerConfirm()),
            'wait_buyer_handle'   => Tab::make()->label(__('red-jasmine-order::refund.scopes.wait_buyer_handle'))
                                        ->badge(static::getResource()::getEloquentQuery()->waitBuyerHandle()->count())
                                        ->modifyQueryUsing(fn(Builder $query) => $query->waitBuyerHandle()),
            'refund_success'      => Tab::make()->label(__('red-jasmine-order::refund.scopes.refund_success'))
                                        ->modifyQueryUsing(fn(Builder $query) => $query->refundSuccess()),
        ];
    }

    protected function getHeaderActions() : array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
