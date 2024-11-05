<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions\Table;

use Filament\Tables\Actions\Action;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions\Shipping;

class OrderShippingTableAction extends Action
{

    use Shipping;
//    protected function setUp() : void
//    {
//        parent::setUp();
//
//        $this->label(__('red-jasmine-order::order.actions.shipping'));
//
//        $this->visible(fn($record) => $record->isAllowShipping());
//    }


}
