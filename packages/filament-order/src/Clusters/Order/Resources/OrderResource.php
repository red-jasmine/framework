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
                                                   ->label('ID')
                                                   ->sortable()->copyable(),
                          Tables\Columns\TextColumn::make('seller_type')
                          ,
                          Tables\Columns\TextColumn::make('seller_id'),
                          Tables\Columns\TextColumn::make('seller_nickname')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('buyer_type')
                          ,
                          Tables\Columns\TextColumn::make('buyer_id')
                          ,
                          Tables\Columns\TextColumn::make('buyer_nickname')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('title')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('order_type')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('pay_type')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('order_status')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('payment_status')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('shipping_status')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('refund_status')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('rate_status')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('settlement_status')
                                                   ->useEnum(),
                          Tables\Columns\TextColumn::make('seller_custom_status')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('product_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('cost_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('tax_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('commission_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('product_payable_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('freight_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('discount_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('payable_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('payment_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('refund_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('service_amount')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('created_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('payment_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('close_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('shipping_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('collect_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('dispatch_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('signed_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('confirm_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('refund_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('rate_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('settlement_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('channel_type'),
                          Tables\Columns\TextColumn::make('channel_id'),
                          Tables\Columns\TextColumn::make('channel_name'),
                          Tables\Columns\TextColumn::make('guide_type'),
                          Tables\Columns\TextColumn::make('guide_id'),
                          Tables\Columns\TextColumn::make('guide_name'),
                          Tables\Columns\TextColumn::make('store_type'),
                          Tables\Columns\TextColumn::make('store_id'),
                          Tables\Columns\TextColumn::make('store_name'),
                          Tables\Columns\TextColumn::make('client_type'),
                          Tables\Columns\TextColumn::make('client_version'),
                          Tables\Columns\TextColumn::make('client_ip'),
                          Tables\Columns\TextColumn::make('source_type'),
                          Tables\Columns\TextColumn::make('source_id'),
                          Tables\Columns\TextColumn::make('contact'),
                          Tables\Columns\TextColumn::make('star'),
                          Tables\Columns\IconColumn::make('is_seller_delete')->boolean(),
                          Tables\Columns\IconColumn::make('is_buyer_delete')->boolean(),
                          Tables\Columns\TextColumn::make('outer_order_id')->searchable(),
                          Tables\Columns\TextColumn::make('cancel_reason')->searchable(),
                          Tables\Columns\TextColumn::make('version')
                                                   ->numeric()
                                                   ->sortable(),
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
