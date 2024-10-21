<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Orders;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\RelationManagers;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\OrderQueryService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PayTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\RateStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\SettlementStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RedJasmine\Payment\Enums\PaymentStatus;

class OrderResource extends Resource
{

    use ResourcePageHelper;

    protected static bool    $onlyOwner      = false;
    protected static ?string $commandService = OrderCommandService::class;
    protected static ?string $queryService   = OrderQueryService::class;
    protected static ?string $createCommand  = OrderCreateCommand::class;


    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Orders::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-order::order.label.order');
    }


    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         Forms\Components\TextInput::make('seller_type')
                                                   ->required()
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('seller_id')
                                                   ->required()
                                                   ->numeric(),
                         Forms\Components\TextInput::make('seller_nickname')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('buyer_type')
                                                   ->required()
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('buyer_id')
                                                   ->required()
                                                   ->numeric(),
                         Forms\Components\TextInput::make('buyer_nickname')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('title')
                                                   ->maxLength(255),
                         Forms\Components\Select::make('order_type')
                                                ->useEnum(OrderTypeEnum::class)
                                                ->required()
                         ,
                         Forms\Components\Select::make('pay_type')
                                                ->useEnum(PayTypeEnum::class)
                                                ->required()
                         ,
                         Forms\Components\Select::make('order_status')
                                                ->useEnum(PayTypeEnum::class)
                                                ->required()
                         ,
                         Forms\Components\Select::make('payment_status')
                                                ->useEnum(PaymentStatusEnum::class),
                         Forms\Components\Select::make('shipping_status')
                                                ->useEnum(ShippingStatusEnum::class),
                         Forms\Components\Select::make('refund_status')
                                                ->useEnum(RefundStatusEnum::class),
                         Forms\Components\Select::make('rate_status')
                                                ->useEnum(RateStatusEnum::class),
                         Forms\Components\Select::make('settlement_status')
                                                ->useEnum(SettlementStatusEnum::class),
                         Forms\Components\TextInput::make('seller_custom_status')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('product_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('cost_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('tax_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('commission_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('product_payable_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('freight_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('discount_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('payable_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('payment_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('refund_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\TextInput::make('service_amount')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0.00)->formatStateUsing(fn($state
                             ) => is_object($state) ? $state->value() : $state),
                         Forms\Components\DateTimePicker::make('created_time'),
                         Forms\Components\DateTimePicker::make('payment_time'),
                         Forms\Components\DateTimePicker::make('close_time'),
                         Forms\Components\DateTimePicker::make('shipping_time'),
                         Forms\Components\DateTimePicker::make('collect_time'),
                         Forms\Components\DateTimePicker::make('dispatch_time'),
                         Forms\Components\DateTimePicker::make('signed_time'),
                         Forms\Components\DateTimePicker::make('confirm_time'),
                         Forms\Components\DateTimePicker::make('refund_time'),
                         Forms\Components\DateTimePicker::make('rate_time'),
                         Forms\Components\DateTimePicker::make('settlement_time'),
                         Forms\Components\TextInput::make('channel_type')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('channel_id')
                                                   ->numeric(),
                         Forms\Components\TextInput::make('channel_name')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('guide_type')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('guide_id')
                                                   ->numeric(),
                         Forms\Components\TextInput::make('guide_name')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('store_type')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('store_id')
                                                   ->numeric(),
                         Forms\Components\TextInput::make('store_name')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('client_type')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('client_version')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('client_ip')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('source_type')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('source_id')
                                                   ->maxLength(32),
                         Forms\Components\TextInput::make('contact')->maxLength(255),
                         Forms\Components\TextInput::make('password')->maxLength(255),
                         Forms\Components\TextInput::make('star')
                                                   ->numeric(),
                         Forms\Components\Toggle::make('is_seller_delete')
                                                ->required(),
                         Forms\Components\Toggle::make('is_buyer_delete')
                                                ->required(),
                         Forms\Components\TextInput::make('outer_order_id')
                                                   ->maxLength(64),
                         Forms\Components\TextInput::make('cancel_reason')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('version')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0),
                         Forms\Components\TextInput::make('creator_type')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('creator_id')
                                                   ->numeric(),
                         Forms\Components\TextInput::make('updater_type')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('updater_id')
                                                   ->numeric(),
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine-order::order.fields.id'))
                                                   ->label('ID')
                                                   ->sortable()->copyable(),
                          Tables\Columns\TextColumn::make('seller_type')
                              ->label(__('red-jasmine-order::order.fields.seller_type'))
                          ,
                          Tables\Columns\TextColumn::make('seller_id')
                              ->label(__('red-jasmine-order::order.fields.seller_id')),
                          Tables\Columns\TextColumn::make('seller_nickname')
                              ->label(__('red-jasmine-order::order.fields.seller_nickname'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('buyer_type')
                              ->label(__('red-jasmine-order::order.fields.buyer_type'))
                          ,
                          Tables\Columns\TextColumn::make('buyer_id')
                              ->label(__('red-jasmine-order::order.fields.buyer_id'))
                          ,
                          Tables\Columns\TextColumn::make('buyer_nickname')
                                                   ->searchable() ->label(__('red-jasmine-order::order.fields.buyer_nickname')),
                          Tables\Columns\TextColumn::make('title')
                                                   ->searchable() ->label(__('red-jasmine-order::order.fields.title')),
                          Tables\Columns\TextColumn::make('order_type')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.order_type')),
                          Tables\Columns\TextColumn::make('pay_type')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.pay_type')),
                          Tables\Columns\TextColumn::make('order_status')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.order_status')),
                          Tables\Columns\TextColumn::make('payment_status')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.payment_status')),
                          Tables\Columns\TextColumn::make('shipping_status')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.shipping_status')),
                          Tables\Columns\TextColumn::make('refund_status')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.refund_status')),
                          Tables\Columns\TextColumn::make('rate_status')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.rate_status')),
                          Tables\Columns\TextColumn::make('settlement_status')
                                                   ->useEnum() ->label(__('red-jasmine-order::order.fields.settlement_status')),
                          Tables\Columns\TextColumn::make('seller_custom_status')
                                                   ->searchable() ->label(__('red-jasmine-order::order.fields.seller_custom_status')),
                          Tables\Columns\TextColumn::make('product_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.product_amount')),
                          Tables\Columns\TextColumn::make('cost_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.cost_amount')),
                          Tables\Columns\TextColumn::make('tax_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.tax_amount')),
                          Tables\Columns\TextColumn::make('commission_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.commission_amount')),
                          Tables\Columns\TextColumn::make('product_payable_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.product_payable_amount')),
                          Tables\Columns\TextColumn::make('freight_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.freight_amount')),
                          Tables\Columns\TextColumn::make('discount_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.discount_amount')),
                          Tables\Columns\TextColumn::make('payable_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.payable_amount')),
                          Tables\Columns\TextColumn::make('payment_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.payment_amount')),
                          Tables\Columns\TextColumn::make('refund_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.refund_amount')),
                          Tables\Columns\TextColumn::make('service_amount')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.service_amount')),
                          Tables\Columns\TextColumn::make('created_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.created_time')),
                          Tables\Columns\TextColumn::make('payment_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.payment_time')),
                          Tables\Columns\TextColumn::make('close_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.close_time')),
                          Tables\Columns\TextColumn::make('shipping_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.id')),
                          Tables\Columns\TextColumn::make('collect_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.collect_time')),
                          Tables\Columns\TextColumn::make('dispatch_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.dispatch_time')),
                          Tables\Columns\TextColumn::make('signed_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.signed_time')),
                          Tables\Columns\TextColumn::make('confirm_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.confirm_time')),
                          Tables\Columns\TextColumn::make('refund_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.refund_time')),
                          Tables\Columns\TextColumn::make('rate_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.rate_time')),
                          Tables\Columns\TextColumn::make('settlement_time')
                                                   ->dateTime()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.settlement_time')),
                          Tables\Columns\TextColumn::make('channel_type') ->label(__('red-jasmine-order::order.fields.channel_type')),
                          Tables\Columns\TextColumn::make('channel_id') ->label(__('red-jasmine-order::order.fields.channel_id')),
                          Tables\Columns\TextColumn::make('channel_name') ->label(__('red-jasmine-order::order.fields.channel_name')),
                          Tables\Columns\TextColumn::make('guide_type') ->label(__('red-jasmine-order::order.fields.guide_type')),
                          Tables\Columns\TextColumn::make('guide_id') ->label(__('red-jasmine-order::order.fields.guide_id')),
                          Tables\Columns\TextColumn::make('guide_name') ->label(__('red-jasmine-order::order.fields.guide_name')),
                          Tables\Columns\TextColumn::make('store_type') ->label(__('red-jasmine-order::order.fields.store_type')),
                          Tables\Columns\TextColumn::make('store_id') ->label(__('red-jasmine-order::order.fields.store_id')),
                          Tables\Columns\TextColumn::make('store_name') ->label(__('red-jasmine-order::order.fields.store_name')),
                          Tables\Columns\TextColumn::make('client_type') ->label(__('red-jasmine-order::order.fields.client_type')),
                          Tables\Columns\TextColumn::make('client_version') ->label(__('red-jasmine-order::order.fields.client_version')),
                          Tables\Columns\TextColumn::make('client_ip') ->label(__('red-jasmine-order::order.fields.client_ip')),
                          Tables\Columns\TextColumn::make('source_type') ->label(__('red-jasmine-order::order.fields.source_type')),
                          Tables\Columns\TextColumn::make('source_id') ->label(__('red-jasmine-order::order.fields.source_id')),
                          Tables\Columns\TextColumn::make('contact') ->label(__('red-jasmine-order::order.fields.contact')),
                          Tables\Columns\TextColumn::make('star') ->label(__('red-jasmine-order::order.fields.star')),
                          Tables\Columns\IconColumn::make('is_seller_delete')->boolean() ->label(__('red-jasmine-order::order.fields.is_seller_delete')),
                          Tables\Columns\IconColumn::make('is_buyer_delete')->boolean() ->label(__('red-jasmine-order::order.fields.is_buyer_delete')),
                          Tables\Columns\TextColumn::make('outer_order_id')->searchable() ->label(__('red-jasmine-order::order.fields.outer_order_id')),
                          Tables\Columns\TextColumn::make('cancel_reason')->searchable() ->label(__('red-jasmine-order::order.fields.cancel_reason')),
                          Tables\Columns\TextColumn::make('version')
                                                   ->numeric()
                                                   ->sortable() ->label(__('red-jasmine-order::order.fields.version')),
                          ...static::operateTableColumns()
                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
                      ])
            ->actions([
                          Tables\Actions\ViewAction::make(),
                          // Tables\Actions\EditAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                       Tables\Actions\ForceDeleteBulkAction::make(),
                                                                       Tables\Actions\RestoreBulkAction::make(),
                                                                   ]),
                          ]);
    }

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            //'create' => Pages\CreateOrder::route('/create'),
            'view'  => Pages\ViewOrder::route('/{record}'),
            //'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }


}
