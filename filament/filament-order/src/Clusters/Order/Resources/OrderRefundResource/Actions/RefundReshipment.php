<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms;
use Filament\Notifications\Notification;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderDummyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCardKeyReshipmentCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyContentTypeEnum;
use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Support\Exceptions\BaseException;

trait RefundReshipment
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::refund.commands.reshipment'));

        $this->visible(fn(Refund $record) => $record->isAllowReshipment());

        $this->form(function (Refund $record) {
            return match ($record->shipping_type) {
                ShippingTypeEnum::DUMMY => $this->dummyForm($record),
                ShippingTypeEnum::DIGITAL => $this->cardKeyForm($record),
                ShippingTypeEnum::LOGISTICS => $this->logisticsForm($record),
                ShippingTypeEnum::DELIVERY => $this->logisticsForm($record),
                ShippingTypeEnum::NONE => []

            };


        });


        $this->action(function ($data, $record) {

            try {
                match ($record->shipping_type) {
                    ShippingTypeEnum::DUMMY => $this->dummyAction($data, $record),
                    ShippingTypeEnum::DIGITAL => $this->cardKeyAction($data, $record),
                    ShippingTypeEnum::LOGISTICS => $this->dummyAction($data, $record),
                    ShippingTypeEnum::DELIVERY => $this->dummyAction($data, $record),
                    ShippingTypeEnum::NONE => $this->dummyAction($data, $record),

                };
            } catch (BaseException $abstractException) {
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


            Radio::make('content_type')
                                  ->required()
                                  ->inline()
                                  ->default(OrderCardKeyContentTypeEnum::TEXT->value)
                                  ->label(__('red-jasmine-order::card-keys.fields.content_type'))
                                  ->options(OrderCardKeyContentTypeEnum::options()),
            Textarea::make('content')
                                     ->required()
                                     ->rows(5)
                                     ->label(__('red-jasmine-order::card-keys.fields.content'))
            ,
            TextInput::make('quantity')
                                      ->required()
                                      ->default(1)
                                      ->numeric()->minValue(1)
                                      ->label(__('red-jasmine-order::card-keys.fields.quantity'))
            ,
            TextInput::make('source_type')->label(__('red-jasmine-order::card-keys.fields.source_type')),
            TextInput::make('source_id')->label(__('red-jasmine-order::card-keys.fields.source_id')),


        ];
    }


    protected function cardKeyAction($data, $record) : void
    {
        $data['id'] = $record->id;
        $command     = RefundCardKeyReshipmentCommand::from($data);
        app(RefundApplicationService::class)->cardKeyReshipment($command);
    }

    protected function dummyForm($record) : array
    {
        return [

            CheckboxList::make('order_products')
                                         ->label(__('red-jasmine-order::order.fields.products'))
                                         ->options($record->products->pluck('title', 'id')->toArray())
                                         ->bulkToggleable()
            ,

            ToggleButtons::make('is_finished')
                                          ->label(__('red-jasmine-order::commands.shipping.is_finished'))
                                          ->default(true)
                                          ->grouped()
                                          ->boolean()

        ];
    }


    protected function dummyAction($data, $record) : void
    {

        $data['id'] = $record->id;
        $command    = OrderDummyShippingCommand::from($data);
        app(OrderApplicationService::class)->dummyShipping($command);

    }

    protected function logisticsForm($record) : array
    {
        return [
            ToggleButtons::make('is_split')
                                          ->label(__('red-jasmine-order::commands.shipping.is_split'))
                                          ->default(false)
                                          ->grouped()
                                          ->live()
                                          ->boolean(),
            CheckboxList::make('order_products')
                                         ->label(__('red-jasmine-order::commands.shipping.products'))
                                         ->visible(fn(Get $get) => $get('is_split'))
                                         ->options($record->products->pluck('title', 'id')->toArray()),

            TextInput::make('logistics_company_code')
                                      ->label(__('red-jasmine-order::commands.shipping.logistics_company_code'))
                                      ->required(),
            TextInput::make('logistics_no')
                                      ->label(__('red-jasmine-order::commands.shipping.logistics_no'))
                                      ->required(),


        ];
    }


}
