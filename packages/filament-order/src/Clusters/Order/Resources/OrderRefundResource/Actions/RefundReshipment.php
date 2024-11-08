<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Actions;

use Filament\Forms;
use Filament\Notifications\Notification;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCardKeyReshipmentCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderDummyShippingCommand;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyContentTypeEnum;
use RedJasmine\Order\Domain\Models\OrderRefund;
use RedJasmine\Support\Exceptions\AbstractException;

trait RefundReshipment
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->label(label: __('red-jasmine-order::refund.actions.reshipment'));

        $this->visible(fn(OrderRefund $record) => $record->isAllowReshipment());

        $this->form(function (OrderRefund $record) {
            return match ($record->shipping_type) {
                ShippingTypeEnum::DUMMY => $this->dummyForm($record),
                ShippingTypeEnum::CDK => $this->cdkForm($record),
                ShippingTypeEnum::EXPRESS => $this->expressForm($record),
                ShippingTypeEnum::DELIVERY => $this->expressForm($record),
                ShippingTypeEnum::NONE => []

            };


        });


        $this->action(function ($data, $record) {

            try {
                match ($record->shipping_type) {
                    ShippingTypeEnum::DUMMY => $this->dummyAction($data, $record),
                    ShippingTypeEnum::CDK => $this->cdkAction($data, $record),
                    ShippingTypeEnum::EXPRESS => $this->dummyAction($data, $record),
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


    protected function cdkForm($record) : array
    {
        return [


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
            Forms\Components\TextInput::make('num')
                                      ->required()
                                      ->default(1)
                                      ->numeric()->minValue(1)
                                      ->label(__('red-jasmine-order::card-keys.fields.num'))
            ,
            Forms\Components\TextInput::make('source_type')->label(__('red-jasmine-order::card-keys.fields.source_type')),
            Forms\Components\TextInput::make('source_id')->label(__('red-jasmine-order::card-keys.fields.source_id')),


        ];
    }


    protected function cdkAction($data, $record) : void
    {
        $data['rid'] = $record->id;
        $command     = RefundCardKeyReshipmentCommand::from($data);
        app(RefundCommandService::class)->cardKeyReshipment($command);
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
        $command    = OrderDummyShippingCommand::from($data);
        app(OrderCommandService::class)->dummyShipping($command);

    }

    protected function expressForm($record) : array
    {
        return [
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