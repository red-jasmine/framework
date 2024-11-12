<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\FilamentCore\Columns\UserAbleColumn;
use RedJasmine\FilamentCore\Filters\InputFilter;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order as OrderCluster;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderResource\RelationManagers;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\OrderQueryService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Order;

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
                                     ->compact()
                                     ->inlineLabel()
                                     ->schema([
                                                  TextEntry::make('order_status')->label(__('red-jasmine-order::order.fields.order_status'))->useEnum(),
                                                  TextEntry::make('seller_custom_status')->label(__('red-jasmine-order::order.fields.seller_custom_status'))->badge(),
                                                  TextEntry::make('accept_status')->label(__('red-jasmine-order::order.fields.accept_status'))->useEnum(),
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
                                                           ->hintAction(
                                                               OrderCluster\Resources\OrderResource\Actions\InfoList\SellerRemarksInfoListAction::make('seller_message')
                                                           ),
                                                  TextEntry::make('info.seller_remarks')
                                                           ->hintColor('primary')
                                                           ->hintIcon('heroicon-m-exclamation-circle')
                                                           ->hintIconTooltip(__('red-jasmine-order::tips.seller_remarks'))
                                                           ->label(__('red-jasmine-order::order.fields.seller_remarks'))
                                                           ->hintAction(
                                                               OrderCluster\Resources\OrderResource\Actions\InfoList\SellerRemarksInfoListAction::make('seller_remarks')
                                                           ),
                                              ])
                                     ->columns(2)
                                     ->footerActions([
                                                         OrderCluster\Resources\OrderResource\Actions\InfoList\OrderAcceptInfoListAction::make('accept')
                                                                                                                                        ->successRedirectUrl(static fn(Model $model) => static::getUrl('view', [ 'record' => $model->id ])),
                                                         OrderCluster\Resources\OrderResource\Actions\InfoList\OrderAcceptInfoListAction::make('reject')
                                                                                                                                        ->successRedirectUrl(static fn(Model $model) => static::getUrl('view', [ 'record' => $model->id ])),

                                                         OrderCluster\Resources\OrderResource\Actions\InfoList\OrderShippingInfoListAction::make('shipping')
                                                                                                                                          ->successRedirectUrl(static fn(Model $model) => static::getUrl('view', [ 'record' => $model->id ])),
                                                     ])
                              ,

                              Section::make('订单信息')
                                     ->compact()
                                     ->schema([

                                                  Fieldset::make('infos')
                                                          ->label(__('red-jasmine-order::order.labels.infos'))
                                                          ->schema([
                                                                       TextEntry::make('id')->copyable()->label(__('red-jasmine-order::order.fields.id')),
                                                                       TextEntry::make('shipping_type')->label(__('red-jasmine-order::order.fields.shipping_type'))->useEnum(),
                                                                       TextEntry::make('order_type')->label(__('red-jasmine-order::order.fields.order_type'))->useEnum(),
                                                                       TextEntry::make('created_time')->label(__('red-jasmine-order::order.fields.created_time')),
                                                                       TextEntry::make('payment_time')->label(__('red-jasmine-order::order.fields.payment_time')),
                                                                       TextEntry::make('confirm_time')->label(__('red-jasmine-order::order.fields.confirm_time')),


                                                                       //TextEntry::make('accept_time')->label(__('red-jasmine-order::order.fields.accept_time')),
                                                                       //TextEntry::make('shipping_time')->label(__('red-jasmine-order::order.fields.shipping_time')),
                                                                       //TextEntry::make('signed_time')->label(__('red-jasmine-order::order.fields.signed_time')),
                                                                       //TextEntry::make('confirm_time')->label(__('red-jasmine-order::order.fields.confirm_time')),
                                                                       //TextEntry::make('close_time')->label(__('red-jasmine-order::order.fields.close_time')),
                                                                       //TextEntry::make('refund_time')->label(__('red-jasmine-order::order.fields.refund_time')),
                                                                       //TextEntry::make('rate_time')->label(__('red-jasmine-order::order.fields.rate_time')),
                                                                       //TextEntry::make('settlement_time')->label(__('red-jasmine-order::order.fields.settlement_time')),

                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(2)
                                                          ->columnSpan(2),


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


                                                  Section::make('更多')
                                                         ->schema(function (Model $record) {

                                                             $schema = [


                                                                 Fieldset::make('infos')
                                                                         ->label(__('red-jasmine-order::order.labels.infos'))
                                                                         ->schema([
                                                                                      TextEntry::make('payment_time')->label(__('red-jasmine-order::order.fields.payment_time')),
                                                                                      TextEntry::make('accept_time')->label(__('red-jasmine-order::order.fields.accept_time')),
                                                                                      TextEntry::make('shipping_time')->label(__('red-jasmine-order::order.fields.shipping_time')),
                                                                                      TextEntry::make('signed_time')->label(__('red-jasmine-order::order.fields.signed_time')),
                                                                                      TextEntry::make('confirm_time')->label(__('red-jasmine-order::order.fields.confirm_time')),
                                                                                      TextEntry::make('close_time')->label(__('red-jasmine-order::order.fields.close_time')),
                                                                                      TextEntry::make('refund_time')->label(__('red-jasmine-order::order.fields.refund_time')),
                                                                                      TextEntry::make('rate_time')->label(__('red-jasmine-order::order.fields.rate_time')),
                                                                                      TextEntry::make('settlement_time')->label(__('red-jasmine-order::order.fields.settlement_time')),

                                                                                  ])
                                                                         ->inlineLabel()
                                                                         ->columns(2)
                                                                         ->columnSpan(2),


                                                                 Livewire::make(OrderCluster\Resources\Components\OrderPayments::class, fn(Model $record) : array => [ 'orderId' => $record->id, ])->key('order-payments')->columnSpanFull(),
                                                             ];
                                                             if ($record->shipping_type === ShippingTypeEnum::LOGISTICS) {
                                                                 $schema[] = Livewire::make(OrderCluster\Resources\Components\OrderLogistics::class, fn(Model $record) : array => [ 'orderId' => $record->id, ])->key('order-logistics')->columnSpanFull();

                                                             }
                                                             if ($record->shipping_type === ShippingTypeEnum::CDK) {
                                                                 $schema[] = Livewire::make(OrderCluster\Resources\Components\OrderCardKeys::class, fn(Model $record) : array => [ 'orderId' => $record->id, ])->key('order-card-keys')->columnSpanFull();

                                                             }

                                                             return $schema;
                                                         }
                                                         )
                                                         ->columnSpanFull()
                                                         ->compact()
                                                         ->collapsed(),

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
        return $form;
    }

    /**
     * @param Table $table
     * @return Table
     * @throws Exception
     */
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
                          Tables\Columns\TextColumn::make('urge')->label(__('red-jasmine-order::common.fields.urge'))
                                                                 ->badge()
                              ->tooltip(fn(Order $record)=>$record->urge_time)
                                                                 ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('urge_time')->label(__('red-jasmine-order::common.fields.urge_time'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\IconColumn::make('is_seller_delete')->boolean()->label(__('red-jasmine-order::order.fields.is_seller_delete'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\IconColumn::make('is_buyer_delete')->boolean()->label(__('red-jasmine-order::order.fields.is_buyer_delete'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('outer_order_id')->label(__('red-jasmine-order::order.fields.outer_order_id'))->toggleable(isToggledHiddenByDefault: true)->copyable(),
                          Tables\Columns\TextColumn::make('cancel_reason')->label(__('red-jasmine-order::order.fields.cancel_reason'))->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('version')->label(__('red-jasmine-order::order.fields.version'))->toggleable(isToggledHiddenByDefault: true),
                          ...static::operateTableColumns()

                      ])
            ->filters([
                          InputFilter::make('id')->inputLabel(__('red-jasmine-order::order.fields.id')),

                          Tables\Filters\SelectFilter::make('order_status')
                                                     ->label(__('red-jasmine-order::order.fields.order_status'))
                                                     ->options(OrderStatusEnum::options()),
                          Tables\Filters\SelectFilter::make('order_type')
                                                     ->label(__('red-jasmine-order::order.fields.order_type'))
                                                     ->options(OrderTypeEnum::options()),
                          Tables\Filters\SelectFilter::make('shipping_type')
                                                     ->label(__('red-jasmine-order::order.fields.shipping_type'))
                                                     ->options(ShippingTypeEnum::options()),
                          Tables\Filters\SelectFilter::make('payment_status')
                                                     ->label(__('red-jasmine-order::order.fields.payment_status'))
                                                     ->options(PaymentStatusEnum::options()),
                          DateRangeFilter::make('created_time')
                                         ->withIndicator()
                                         ->alwaysShowCalendar()
                                         ->timePickerSecond()
                                         ->displayFormat('YYYY/MM/DD')
                                         ->format('Y/m/d')
                                         ->timePicker24()
                                         ->icon('heroicon-o-backspace')
                                         ->linkedCalendars()
                                         ->autoApply()
                                         ->label(__('red-jasmine-order::order.fields.created_time')),

                          DateRangeFilter::make('payment_time')
                                         ->withIndicator()
                                         ->alwaysShowCalendar()
                                         ->timePickerSecond()
                                         ->displayFormat('YYYY/MM/DD')
                                         ->format('Y/m/d')
                                         ->timePicker24()
                                         ->icon('heroicon-o-backspace')
                                         ->linkedCalendars()
                                         ->autoApply()
                                         ->label(__('red-jasmine-order::order.fields.payment_time')),

                          InputFilter::make('outer_order_id')->inputLabel(__('red-jasmine-order::order.fields.outer_order_id')),


                          //Tables\Filters\TrashedFilter::make(),
                      ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns([
                                     'sm'  => 2,
                                     'lg'  => 3,
                                     'xl'  => 4,
                                     '2xl' => 6,
                                 ])
            ->deferFilters()
            ->actions([
                          Tables\Actions\ViewAction::make(),
                          OrderCluster\Resources\OrderResource\Actions\Table\OrderShippingTableAction::make('shipping'),
                          OrderCluster\Resources\OrderResource\Actions\Table\OrderAcceptTableAction::make('accept'),
                          OrderCluster\Resources\OrderResource\Actions\Table\OrderAcceptTableAction::make('reject'),
                          // 其他操作

                          Tables\Actions\ActionGroup::make([

                                                               OrderCluster\Resources\OrderResource\Actions\Table\SellerRemarksTableAction::make('seller_remarks'),
                                                               OrderCluster\Resources\OrderResource\Actions\Table\SellerRemarksTableAction::make('seller_message'),
                                                               OrderCluster\Resources\OrderResource\Actions\Table\OrderStarTableAction::make('star'),


                                                           ])->label('more'),

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
