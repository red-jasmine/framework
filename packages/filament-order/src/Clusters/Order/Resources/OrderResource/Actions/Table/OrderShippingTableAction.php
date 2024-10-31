<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions\Table;

use Filament\Tables\Actions\Action;

class OrderShippingTableAction extends Action
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(__('red-jasmine-order::order.actions.shipping'));

        $this->visible(fn($record) => $record->isAllowShipping());
    }


}
