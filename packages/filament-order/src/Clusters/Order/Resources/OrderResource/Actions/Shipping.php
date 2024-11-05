<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;

trait Shipping
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::order.actions.shipping'));


        $this->visible(fn($record) => $record->isAllowShipping());


        $this->form(function ($record) {
            return match ($record->shipping_type) {
                ShippingTypeEnum::DUMMY => $this->dummyForm($record),
                ShippingTypeEnum::EXPRESS => $this->expressForm($record),
                ShippingTypeEnum::CDK => $this->cdkForm($record),
                ShippingTypeEnum::DELIVERY => $this->dummyForm($record),
                ShippingTypeEnum::NONE => $this->dummyForm($record),

            };


        });


    }


    protected  function cdkForm($record) : array
    {
        return [

            Forms\Components\Radio::make('order_product_id')
                                         ->label(__('red-jasmine-order::order.fields.products'))
                                         ->options($record->products->pluck('title', 'id')->toArray()),



        ];
    }

    protected function dummyForm($record) : array
    {
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
    }

    protected function expressForm($record) : array
    {
        return  [

            Forms\Components\ToggleButtons::make('is_split')
                                          ->label(__('red-jasmine-order::commands.shipping.is_split'))
                                          ->default(false)
                                          ->grouped()
                                          ->live()
                                          ->boolean(),
            Forms\Components\CheckboxList::make('order_products')
                                         ->label(__('red-jasmine-order::commands.shipping.products'))
                                         ->visible(fn(Forms\Get $get) => $get('is_split'))
                                         ->options($record->products->pluck('title', 'id')->toArray()),

            Forms\Components\TextInput::make('express_company_code')
                                      ->label(__('red-jasmine-order::commands.shipping.express_company_code'))
                                      ->required(),
            Forms\Components\TextInput::make('express_no')
                                      ->label(__('red-jasmine-order::commands.shipping.express_no'))
                                      ->required(),

        ];
    }


}
