<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\View;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\FilamentCore\Columns\UserAbleColumn;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\RelationManagers;
use RedJasmine\FilamentOrder\Filament\Tables\Columns\OrderProduct;

use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\OrderQueryService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PayTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\RateStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\SettlementStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\FilamentOrder\Clusters\Order as OrderCluster;

class OrderResource extends Resource
{

    use ResourcePageHelper;

    protected static bool    $onlyOwner      = false;
    protected static ?string $commandService = OrderCommandService::class;
    protected static ?string $queryService   = OrderQueryService::class;
    protected static ?string $createCommand  = OrderCreateCommand::class;


    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $cluster        = OrderCluster::class;
    protected static ?int    $navigationSort = 1;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-order::order.labels.order');
    }

    public static function infolist(Infolist $infoList) : Infolist
    {
        $infoList->schema([
                              Section::make(static fn(Model $record) => $record->id)
                                     ->schema([
                                                  TextEntry::make('order_status')->label(__('red-jasmine-order::order.fields.order_status'))
                                                           ->useEnum(),
                                                  RatingEntry::make('star')
                                                             ->stars(10)
                                                             ->allowZero()
                                                             ->label(__('red-jasmine-order::order.fields.star'))
                                                             ->hintAction(
                                                                 OrderCluster\Resources\OrderResource\Actions\InfoList\OrderStarInfoListAction::make('star')
                                                             )
                                                  ,
                                                  TextEntry::make('info.buyer_message')->label(__('red-jasmine-order::order.fields.buyer_message'))
                                                  ,
                                                  TextEntry::make('info.seller_message')->label(__('red-jasmine-order::order.fields.seller_message'))
                                                           ->prefixAction(
                                                               OrderCluster\Resources\OrderResource\Actions\InfoList\SellerRemarksInfoListAction::make('seller_message')
                                                           ),
                                                  TextEntry::make('info.seller_remarks')
                                                           ->hintColor('primary')
                                                           ->hintIcon('heroicon-m-exclamation-circle')
                                                           ->hint(__('red-jasmine-order::tips.seller_remarks'))
                                                           ->label(__('red-jasmine-order::order.fields.seller_remarks'))
                                                           ->prefixAction(
                                                               OrderCluster\Resources\OrderResource\Actions\InfoList\SellerRemarksInfoListAction::make('seller_remarks')
                                                           ),
                                              ])
                                     ->columns(2)
                              ,

                              Section::make('订单信息')
                                     ->schema([

                                                  Fieldset::make('infos')
                                                          ->schema([
                                                                       TextEntry::make('id')->copyable()->label(__('red-jasmine-order::order.fields.order_status')),
                                                                       TextEntry::make('created_time')->label(__('red-jasmine-order::order.fields.created_time')),
                                                                       TextEntry::make('payment_time')->label(__('red-jasmine-order::order.fields.payment_time')),
                                                                       TextEntry::make('shipping_time')->label(__('red-jasmine-order::order.fields.shipping_time')),
                                                                       TextEntry::make('signed_time')->label(__('red-jasmine-order::order.fields.signed_time')),
                                                                       TextEntry::make('confirm_time')->label(__('red-jasmine-order::order.fields.confirm_time')),

                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(1)
                                                          ->columnSpan(1),


                                                  Fieldset::make('seller')
                                                          ->label(__('red-jasmine-order::order.fields.seller'))
                                                          ->schema([
                                                                       TextEntry::make('seller_type')->label(__('red-jasmine-order::order.fields.seller_type')),
                                                                       TextEntry::make('seller_id')->copyable()->label(__('red-jasmine-order::order.fields.seller_id')),
                                                                       TextEntry::make('seller_nickname')->copyable()->label(__('red-jasmine-order::order.fields.seller_nickname')),
                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(1)
                                                          ->columnSpan(1),
                                                  Fieldset::make('buyer')
                                                          ->label(__('red-jasmine-order::order.fields.buyer'))
                                                          ->schema([
                                                                       TextEntry::make('buyer_type')->label(__('red-jasmine-order::order.fields.buyer_type')),
                                                                       TextEntry::make('buyer_id')->copyable()->label(__('red-jasmine-order::order.fields.buyer_id')),
                                                                       TextEntry::make('buyer_nickname')->copyable()->label(__('red-jasmine-order::order.fields.buyer_nickname')),
                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(1)
                                                          ->columnSpan(1),
                                                  Fieldset::make('address')
                                                          ->label(__('red-jasmine-order::order.fields.address.address'))
                                                          ->schema([
                                                                       TextEntry::make('address.full_address')->label(__('red-jasmine-order::order.fields.address.full_address')),
                                                                       TextEntry::make('address.contacts')->label(__('red-jasmine-order::order.fields.address.contacts')),
                                                                       TextEntry::make('address.mobile')->label(__('red-jasmine-order::order.fields.address.mobile')),
                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(1)
                                                          ->columnSpan(1),

                                              ])->columns(5),


                              Livewire::make(OrderCluster\Resources\OrderResource\Components\OrderProducts::class, fn(Model $record) : array => [ 'id' => $record->id ])->columnSpanFull(),

                              Fieldset::make('amount')
                                      ->label(__('red-jasmine-order::order.fields.amount'))
                                      ->schema([

                                                   TextEntry::make('product_payable_amount')->prefix('￥')->money('CNY')->label(__('red-jasmine-order::order.fields.product_payable_amount')),
                                                   TextEntry::make('freight_amount')->prefix('￥')->money('CNY')->label(__('red-jasmine-order::order.fields.freight_amount')),
                                                   TextEntry::make('discount_amount')->prefix('￥')->money('CNY')->label(__('red-jasmine-order::order.fields.discount_amount')),
                                                   TextEntry::make('payable_amount')->prefix('￥')->money('CNY')->label(__('red-jasmine-order::order.fields.payable_amount')),
                                                   TextEntry::make('payment_amount')->prefix('￥')->weight(FontWeight::Bold)->color('danger')->money('CNY')->label(__('red-jasmine-order::order.fields.payment_amount')),
                                                   TextEntry::make('refund_amount')->prefix('￥')->money('CNY')->label(__('red-jasmine-order::order.fields.refund_amount')),
                                               ])
                                      ->inlineLabel()
                                      ->columns(1)
                                      ->columnSpanFull(),


                          ]);

        return $infoList;

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
                         Forms\Components\Select::make('order_status')
                                                ->useEnum(PayTypeEnum::class)
                                                ->required()
                         ,
                         Forms\Components\Select::make('payment_status')
                                                ->useEnum(PaymentStatusEnum::class),
                         Forms\Components\Select::make('shipping_status')
                                                ->useEnum(ShippingStatusEnum::class),
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
            ->defaultSort('id', 'DESC')
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine-order::order.fields.id'))->copyable(),
                          OrderCluster\Resources\OrderResource\Columns\OrderProductShowColumn::make('products')->label(__('red-jasmine-order::order.fields.products')),
                          //Tables\Columns\TextColumn::make('title')->label(__('red-jasmine-order::order.fields.title')),
                          Tables\Columns\TextColumn::make('order_type')->alignCenter()->useEnum()->label(__('red-jasmine-order::order.fields.order_type')),
                          Tables\Columns\TextColumn::make('shipping_type')->alignCenter()->useEnum()->label(__('red-jasmine-order::order.fields.shipping_type')),
                          UserAbleColumn::make('seller')->alignCenter()
                                        ->label(__('red-jasmine-order::order.fields.seller'))->toggleable(isToggledHiddenByDefault: true),
                          UserAbleColumn::make('buyer')->label(__('red-jasmine-order::order.fields.buyer'))
                                        ->extraAttributes([ 'class' => 'px-4' ])
                                        ->grow(),


                          Tables\Columns\ColumnGroup::make('status')->label(__('red-jasmine-order::order.labels.status'))
                                                    ->alignCenter()
                                                    ->columns([

                                                                  Tables\Columns\ViewColumn::make('order_status')->view('red-jasmine-filament-order::resources.order-resource.columns.order-status')
                                                                                           ->label(__('red-jasmine-order::order.fields.order_status')),
                                                                  Tables\Columns\TextColumn::make('payment_status')->useEnum()->label(__('red-jasmine-order::order.fields.payment_status')),
                                                                  Tables\Columns\TextColumn::make('settlement_status')->badge()->label(__('red-jasmine-order::order.fields.settlement_status'))->toggleable(isToggledHiddenByDefault: true),
                                                                  Tables\Columns\TextColumn::make('seller_custom_status')->label(__('red-jasmine-order::order.fields.seller_custom_status'))->toggleable(isToggledHiddenByDefault: true),
                                                              ]),

                          Tables\Columns\ColumnGroup::make('amount')
                                                    ->alignCenter()
                                                    ->label(__('red-jasmine-order::order.labels.amount'))
                                                    ->columns([
//                                                                                  Tables\Columns\TextColumn::make('product_payable_amount')
//                                                                                                           ->numeric()
//                                                                                                           ->label(__('red-jasmine-order::order.fields.product_payable_amount')),
//
//                                                                                  Tables\Columns\TextColumn::make('service_amount')
//                                                                                                           ->numeric()
//                                                                                                           ->label(__('red-jasmine-order::order.fields.service_amount'))->toggleable(isToggledHiddenByDefault: true),
//
//                                                                                  Tables\Columns\TextColumn::make('freight_amount')
//                                                                                                           ->numeric()
//                                                                                                           ->label(__('red-jasmine-order::order.fields.freight_amount')),
//                                                                                  Tables\Columns\TextColumn::make('discount_amount')
//                                                                                                           ->numeric()
//                                                                                                           ->label(__('red-jasmine-order::order.fields.discount_amount')),
Tables\Columns\TextColumn::make('payable_amount')
                         ->numeric()
                         ->label(__('red-jasmine-order::order.fields.payable_amount')),
Tables\Columns\TextColumn::make('payment_amount')
                         ->numeric()
                         ->label(__('red-jasmine-order::order.fields.payment_amount')),
Tables\Columns\TextColumn::make('refund_amount')
                         ->numeric()
                         ->label(__('red-jasmine-order::order.fields.refund_amount')),
Tables\Columns\TextColumn::make('commission_amount')
                         ->numeric()
                         ->label(__('red-jasmine-order::order.fields.commission_amount'))
                         ->toggleable(isToggledHiddenByDefault: true),
Tables\Columns\TextColumn::make('cost_amount')
                         ->numeric()
                         ->label(__('red-jasmine-order::order.fields.cost_amount'))->toggleable(isToggledHiddenByDefault: true),
                                                              ]),

                          Tables\Columns\TextColumn::make('created_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.created_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('payment_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.payment_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('close_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.close_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('shipping_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.shipping_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('collect_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.collect_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('dispatch_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.dispatch_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('signed_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.signed_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('confirm_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.confirm_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('refund_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.refund_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('rate_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.rate_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('settlement_time')
                                                   ->dateTime()
                                                   ->label(__('red-jasmine-order::order.fields.settlement_time'))->toggleable(isToggledHiddenByDefault: true),

                          UserAbleColumn::make('channel')->setNickname('name')->label(__('red-jasmine-order::order.fields.channel')),
                          UserAbleColumn::make('guide')->setNickname('name')->label(__('red-jasmine-order::order.fields.guide')),
                          UserAbleColumn::make('store')->setNickname('name')->label(__('red-jasmine-order::order.fields.store')),

                          Tables\Columns\TextColumn::make('client_type')->label(__('red-jasmine-order::order.fields.client_type'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('client_version')->label(__('red-jasmine-order::order.fields.client_version'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('client_ip')->label(__('red-jasmine-order::order.fields.client_ip'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('source_type')->label(__('red-jasmine-order::order.fields.source_type'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('source_id')->label(__('red-jasmine-order::order.fields.source_id'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('contact')
                                                   ->label(__('red-jasmine-order::order.fields.contact'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('star')->label(__('red-jasmine-order::order.fields.star'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\IconColumn::make('is_seller_delete')->boolean()->label(__('red-jasmine-order::order.fields.is_seller_delete'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\IconColumn::make('is_buyer_delete')->boolean()->label(__('red-jasmine-order::order.fields.is_buyer_delete'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('outer_order_id')->label(__('red-jasmine-order::order.fields.outer_order_id'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('cancel_reason')->label(__('red-jasmine-order::order.fields.cancel_reason'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('version')
                                                   ->sortable()->label(__('red-jasmine-order::order.fields.version'))->toggleable(isToggledHiddenByDefault: true),
                          ...static::operateTableColumns()

                      ])
            ->filters([


                          Tables\Filters\SelectFilter::make('order_status')
                                                     ->label(__('red-jasmine-order::order.fields.order_status'))
                                                     ->options(OrderStatusEnum::options()),
                          Tables\Filters\SelectFilter::make('order_type')
                                                     ->label(__('red-jasmine-order::order.fields.order_type'))
                                                     ->options(OrderTypeEnum::options()),
                          Tables\Filters\SelectFilter::make('shipping_type')
                                                     ->label(__('red-jasmine-order::order.fields.shipping_type'))
                                                     ->options(ShippingTypeEnum::options()),
                          //Tables\Filters\TrashedFilter::make(),
                      ], layout: Tables\Enums\FiltersLayout::AboveContent)

            ->actions([
                          Tables\Actions\ViewAction::make(),
                          OrderCluster\Resources\OrderResource\Actions\Table\OrderShippingTableAction::make('shipping')
                                                                                                     ->url(fn($record) => static::getUrl('shipping', [ 'record' => $record->id ]))

                          ,
                          // 其他操作

                          Tables\Actions\ActionGroup::make([

                                                               OrderCluster\Resources\OrderResource\Actions\Table\SellerRemarksTableAction::make('seller_remarks'),
                                                               OrderCluster\Resources\OrderResource\Actions\Table\SellerRemarksTableAction::make('seller_message'),
                                                               OrderCluster\Resources\OrderResource\Actions\Table\OrderStarTableAction::make('star'),


                                                           ])->label('more'),


                          //OrderCluster\Resources\OrderResource\Actions\SellerRemarksAction::make('seller_remarks'),
                          //OrderCluster\Resources\OrderResource\Actions\SellerRemarksAction::make('seller_message'),

                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                       Tables\Actions\ForceDeleteBulkAction::make(),
                                                                       Tables\Actions\RestoreBulkAction::make(),
                                                                   ]),
                          ])
            ->recordUrl(null);
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
            'index'    => Pages\ListOrders::route('/'),
            //'create' => Pages\CreateOrder::route('/create'),
            'view'     => Pages\ViewOrder::route('/{record}'),
            //            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'shipping' => Pages\Shipping::route('/{record}/shipping'),
        ];
    }


}
