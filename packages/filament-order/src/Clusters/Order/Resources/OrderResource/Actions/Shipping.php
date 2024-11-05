<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;
use Filament\Forms;

trait Shipping
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::order.actions.shipping'));


        $this->visible(fn($record) => $record->isAllowShipping());

        $this->form(function ($record) {
            return [

                Forms\Components\CheckboxList::make('order_products')
                                             ->label(__('red-jasmine-order::order.fields.products'))
                                             ->options($record->products->pluck('title', 'id')->toArray()),

                Forms\Components\ToggleButtons::make('is_finished')
                                              ->label(__('red-jasmine-order::commands.shipping.is_finished'))
                                              ->default(true)
                                              ->grouped()
                                              ->boolean()

            ];
        });



    }
}
