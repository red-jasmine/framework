<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Actions;

use Filament\Forms;
use Filament\Notifications\Notification;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCardKeyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderDummyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderLogisticsShippingCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyContentTypeEnum;
use RedJasmine\Support\Exceptions\AbstractException;

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
                ShippingTypeEnum::LOGISTICS => $this->logisticsForm($record),
                ShippingTypeEnum::CARD_KEY => $this->cardKeyForm($record),
                ShippingTypeEnum::DELIVERY => $this->dummyForm($record),
                ShippingTypeEnum::NONE => $this->dummyForm($record),

            };


        });


        $this->action(function ($data, $record) {

            try {
                match ($record->shipping_type) {
                    ShippingTypeEnum::DUMMY => $this->dummyAction($data, $record),
                    ShippingTypeEnum::LOGISTICS => $this->logisticsAction($data, $record),
                    ShippingTypeEnum::CARD_KEY => $this->cardKeyAction($data, $record),
                    ShippingTypeEnum::DELIVERY => $this->dummyAction($data, $record),
                    ShippingTypeEnum::NONE => $this->dummyAction($data, $record),

                };
            } catch (AbstractException $abstractException) {
                Notification::make()->danger()
                            ->title($abstractException->getMessage())
                            ->send();
                return;
            }


            Notification::make()->success()
                        ->title('OK')
                        ->send();
        });


    }


    protected function cardKeyForm($record) : array
    {
        return [

            Forms\Components\Radio::make('order_product_id')
                                  ->required()
                                  ->label(__('red-jasmine-order::order.fields.products'))
                                  ->options($record->products->pluck('title', 'id')->toArray()),


            Forms\Components\Radio::make('content_type')
                                  ->required()
                                  ->inline()
                                  ->default(OrderCardKeyContentTypeEnum::TEXT->value)
                                  ->label(__('red-jasmine-order::card-keys.fields.content_type'))
                                  ->options(OrderCardKeyContentTypeEnum::options()),
            Forms\Components\Textarea::make('content')
                                     ->required()
                                     ->rows(5)
                                     ->label(__('red-jasmine-order::card-keys.fields.content'))
            ,
            Forms\Components\TextInput::make('quantity')
                                      ->required()
                                      ->default(1)
                                      ->numeric()->minValue(1)
                                      ->label(__('red-jasmine-order::card-keys.fields.quantity'))
            ,
            Forms\Components\TextInput::make('source_type')->label(__('red-jasmine-order::card-keys.fields.source_type')),
            Forms\Components\TextInput::make('source_id')->label(__('red-jasmine-order::card-keys.fields.source_id')),


        ];
    }


    protected function cardKeyAction($data, $record) : void
    {
        $data['id'] = $record->id;
        $data['orderNo']     = $record->order_no;
        $command    = OrderCardKeyShippingCommand::from($data);
        app(OrderApplicationService::class)->cardKeyShipping($command);
    }

    protected function dummyForm($record) : array
    {
        return [

            Forms\Components\CheckboxList::make('order_products')
                                         ->label(__('red-jasmine-order::order.fields.products'))
                                         ->options($record->products->pluck('title', 'id')->toArray())
                                         ->bulkToggleable()
            ,

            Forms\Components\ToggleButtons::make('is_finished')
                                          ->label(__('red-jasmine-order::commands.shipping.is_finished'))
                                          ->default(true)
                                          ->grouped()
                                          ->boolean()

        ];
    }


    protected function dummyAction($data, $record) : void
    {

        $data['id'] = $record->id;
        $data['orderNo']     = $record->order_no;
        $command    = OrderDummyShippingCommand::from($data);
        app(OrderApplicationService::class)->dummyShipping($command);

    }


    protected function logisticsAction($data, $record) : void
    {
        $data['id'] = $record->id;
        $data['orderNo']     = $record->order_no;
        $command    = OrderLogisticsShippingCommand::from($data);

        app(OrderApplicationService::class)->logisticsShipping($command);

    }

    protected function logisticsForm($record) : array
    {
        return [
            Forms\Components\ToggleButtons::make('is_finished')
                                          ->label(__('red-jasmine-order::commands.shipping.is_finished'))
                                          ->default(true)
                                          ->grouped()
                                          ->boolean(),
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

            Forms\Components\TextInput::make('logistics_company_code')
                                      ->label(__('red-jasmine-order::commands.shipping.logistics_company_code'))
                                      ->required(),
            Forms\Components\TextInput::make('logistics_no')
                                      ->label(__('red-jasmine-order::commands.shipping.logistics_no'))
                                      ->required(),


        ];
    }


}
