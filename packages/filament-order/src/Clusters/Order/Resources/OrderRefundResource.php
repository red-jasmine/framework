<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\FilamentCore\Filters\DateRangeFilter;
use RedJasmine\FilamentCore\Filters\InputFilter;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\RelationManagers;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\Services\RefundQueryService;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\OrderRefund;

class OrderRefundResource extends Resource
{

    use ResourcePageHelper;

    protected static bool    $onlyOwner      = false;
    protected static ?string $commandService = RefundCommandService::class;
    protected static ?string $queryService   = RefundQueryService::class;
    protected static ?string $createCommand  = RefundCreateCommand::class;

    protected static ?string $model = OrderRefund::class;

    public static string $translationNamespace = 'red-jasmine-order::refund';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Order::class;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-order::refund.labels.refund');
    }

    public static function infolist(Infolist $infoList) : Infolist
    {

        $infoList->schema([
                              Section::make(static fn(Model $record) => $record->id)
                                     ->schema([
                                                  TextEntry::make('refund_status')->useEnum(),
                                                  TextEntry::make('info.reject_reason'),
                                                  TextEntry::make('seller_custom_status')->badge(),
                                                  TextEntry::make('info.seller_remarks')
                                                           ->hintColor('primary')
                                                           ->hintIcon('heroicon-m-exclamation-circle')
                                                           ->hintIconTooltip(__('red-jasmine-order::tips.seller_remarks'))
                                                           ->hintAction(
                                                               Order\Resources\OrderRefundResource\Actions\InfoList\RefundSellerRemarksInfoListAction::make('seller-remarks')

                                                           ),


                                                  RatingEntry::make('star')
                                                             ->stars(10)
                                                             ->allowZero()
                                                             ->hintAction(
                                                                 Order\Resources\OrderRefundResource\Actions\InfoList\RefundStarInfoListAction::make('star'),
                                                             )
                                                  ,
                                              ])
                                     ->columns(2)
                                     ->inlineLabel()
                                     ->footerActions([
                                                         Order\Resources\OrderRefundResource\Actions\InfoList\RefundAgreeInfoListAction::make('agree')
                                                                                                                                       ->successRedirectUrl(static fn(Model $model) => static::getUrl('view', [ 'record' => $model->id ]))
                                                         ,

                                                         Order\Resources\OrderRefundResource\Actions\InfoList\RefundAgreeReshipmentInfoListAction::make('agree-reshipment')
                                                                                                                                                 ->successRedirectUrl(static fn(Model $model) => static::getUrl('view', [ 'record' => $model->id ]))
                                                         ,

                                                         Order\Resources\OrderRefundResource\Actions\InfoList\RefundReshipmentInfoListAction::make('reshipment')
                                                                                                                                            ->successRedirectUrl(static fn(Model $model) => static::getUrl('view', [ 'record' => $model->id ]))
                                                         ,

                                                         Order\Resources\OrderRefundResource\Actions\InfoList\RefundRejectInfoListAction::make('reject')
                                                                                                                                        ->successRedirectUrl(static fn(Model $model) => static::getUrl('view', [ 'record' => $model->id ]))
                                                         ,

                                                     ])
                              ,
                              Section::make('退款')
                                     ->schema(function (OrderRefund $record) {
                                         $schema     = [
                                             TextEntry::make('refund_type')->useEnum(),
                                             TextEntry::make('phase')->useEnum(),
                                             IconEntry::make('has_good_return')->boolean(),
                                             TextEntry::make('good_status')->useEnum(),
                                             TextEntry::make('reason'),
                                             TextEntry::make('freight_amount'),
                                             TextEntry::make('refund_amount'),
                                             TextEntry::make('total_refund_amount'),
                                             TextEntry::make('description'),
                                             ImageEntry::make('info.images')->stacked()->limit(10)
                                                       ->checkFileExistence(false)
                                                       ->limitedRemainingText(),

                                         ];
                                         $components = [];

                                         if (in_array($record->refund_type, [
                                                 RefundTypeEnum::REFUND,
                                                 RefundTypeEnum::RETURN_GOODS_REFUND,
                                             ],       true)
                                             && $record->refund_status === RefundStatusEnum::FINISHED
                                         ) {
                                             $components[] = Order\Resources\Components\OrderPayments::class;
                                         }
                                         if (
                                             $record->shipping_type === ShippingTypeEnum::LOGISTICS &&
                                             in_array($record->refund_type, [
                                                 RefundTypeEnum::RETURN_GOODS_REFUND,
                                                 RefundTypeEnum::RESHIPMENT,
                                                 RefundTypeEnum::WARRANTY,
                                             ],       true)

                                         ) {
                                             $components[] = Order\Resources\Components\OrderLogistics::class;
                                         }
                                         if ($record->refund_type === RefundTypeEnum::RESHIPMENT
                                             && $record->shipping_type === ShippingTypeEnum::CARD_KEY
                                             && $record->refund_status === RefundStatusEnum::FINISHED
                                         ) {
                                             $components[] = Order\Resources\Components\OrderCardKeys::class;
                                         }


                                         if (filled($components)) {
                                             foreach ($components as $component) {
                                                 $schema[] = Livewire::make($component,
                                                     fn(OrderRefund $record) : array => [
                                                         'orderId'    => $record->order_id,
                                                         'entityType' => EntityTypeEnum::REFUND->value,
                                                         'entityId'   => $record->id,
                                                     ])
                                                                     ->key($component)
                                                                     ->columnSpanFull();
                                             }

                                         }


                                         return $schema;
                                     })
                                     ->inlineLabel()
                                     ->columns(6),

                              Section::make('商品')
                                     ->schema([
                                                  TextEntry::make('title'),
                                                  ImageEntry::make('image'),
                                                  TextEntry::make('sku_name'),
                                                  TextEntry::make('quantity'),
                                                  TextEntry::make('unit_quantity'),
                                                  TextEntry::make('unit'),
                                                  TextEntry::make('price'),
                                                  TextEntry::make('product_amount'),
                                                  TextEntry::make('tax_amount'),
                                                  TextEntry::make('discount_amount'),
                                                  TextEntry::make('payable_amount'),
                                                  TextEntry::make('payment_amount'),
                                                  TextEntry::make('divided_payment_amount'),
                                                  TextEntry::make('shipping_status'),
                                              ])
                                     ->inlineLabel()
                                     ->columns(6),
                              Section::make('退款信息')
                                     ->schema([

                                                  Fieldset::make('infos')
                                                          ->schema([
                                                                       TextEntry::make('id')->copyable(),
                                                                       TextEntry::make('order_id')->copyable(),
                                                                       TextEntry::make('created_time'),
                                                                       TextEntry::make('end_time'),

                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(1)
                                                          ->columnSpan(1),


                                                  Fieldset::make('seller')
                                                          ->schema([
                                                                       TextEntry::make('seller_type'),
                                                                       TextEntry::make('seller_id')->copyable(),
                                                                       TextEntry::make('seller_nickname')->copyable(),
                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(1)
                                                          ->columnSpan(1),
                                                  Fieldset::make('buyer')
                                                          ->schema([
                                                                       TextEntry::make('buyer_type'),
                                                                       TextEntry::make('buyer_id')->copyable(),
                                                                       TextEntry::make('buyer_nickname')->copyable(),
                                                                   ])
                                                          ->inlineLabel()
                                                          ->columns(1)
                                                          ->columnSpan(1),

                                              ])->columns(5),


                          ]);

        return $infoList;
    }

    public static function form(Form $form) : Form
    {
        return $form;
    }

    public static function table(Table $table) : Table
    {
        $table
            ->defaultSort('id', 'DESC')
            ->recordUrl(null)
            ->columns([
                          Tables\Columns\TextColumn::make('id')->copyable()
                          ,

                          Tables\Columns\TextColumn::make('order_id')->copyable(),
                          //                Tables\Columns\TextColumn::make('order_product_id') ,

                          Tables\Columns\TextColumn::make('order_product_type')->useEnum(),
                          Tables\Columns\TextColumn::make('shipping_type')->useEnum(),

                          Tables\Columns\TextColumn::make('title'),
                          //                Tables\Columns\TextColumn::make('sku_name'),
                          //                Tables\Columns\ImageColumn::make('image'),
                          //                Tables\Columns\TextColumn::make('product_type'),
                          //                Tables\Columns\TextColumn::make('sku_id'),
                          //                Tables\Columns\TextColumn::make('category_id'),
                          //                Tables\Columns\TextColumn::make('product_group_id'),
                          //                Tables\Columns\TextColumn::make('outer_product_id'),
                          //                Tables\Columns\TextColumn::make('outer_sku_id'),
                          //                Tables\Columns\TextColumn::make('barcode'),
                          //                Tables\Columns\TextColumn::make('unit_quantity'),
                          //                Tables\Columns\TextColumn::make('unit'),
                          //                Tables\Columns\TextColumn::make('quantity'),
                          //                Tables\Columns\TextColumn::make('price')->money(),
                          //                Tables\Columns\TextColumn::make('cost_price')->money(),
                          //                Tables\Columns\TextColumn::make('product_amount')->money(),
                          //                Tables\Columns\TextColumn::make('tax_amount')->money(),
                          //                Tables\Columns\TextColumn::make('discount_amount')->money(),
                          //                Tables\Columns\TextColumn::make('payable_amount')->money(),
                          //                Tables\Columns\TextColumn::make('payment_amount')->money(),
                          //                Tables\Columns\TextColumn::make('divided_payment_amount')->money(),
                          //                Tables\Columns\TextColumn::make('shipping_status'),
                          //


                          Tables\Columns\TextColumn::make('refund_type')->useEnum(),
                          Tables\Columns\TextColumn::make('phase')->useEnum(),
                          Tables\Columns\IconColumn::make('has_good_return')->boolean(),
                          Tables\Columns\TextColumn::make('good_status')->useEnum(),
                          Tables\Columns\TextColumn::make('reason'),
                          Tables\Columns\TextColumn::make('outer_refund_id'),

                          Tables\Columns\TextColumn::make('refund_status')->useEnum(),
                          Tables\Columns\TextColumn::make('freight_amount')->money(),
                          Tables\Columns\TextColumn::make('refund_amount')->money(),
                          Tables\Columns\TextColumn::make('total_refund_amount')->money(),

                          ...static::operateTableColumns()
                      ])
            ->filters([

                          InputFilter::make('id'),
                          InputFilter::make('order_id'),
                          Tables\Filters\SelectFilter::make('refund_status'),
                          Tables\Filters\SelectFilter::make('refund_type'),
                          Tables\Filters\SelectFilter::make('phase'),
                          Tables\Filters\SelectFilter::make('good_status'),
                          DateRangeFilter::make('created_time'),
                          DateRangeFilter::make('end_time'),

                      ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->deferFilters()
            ->actions([
                          Tables\Actions\ViewAction::make(),
                          // Tables\Actions\EditAction::make(),
                          Order\Resources\OrderRefundResource\Actions\Table\RefundAgreeTableAction::make('agree'),
                          Order\Resources\OrderRefundResource\Actions\Table\RefundRejectTableAction::make('reject'),
                          Order\Resources\OrderRefundResource\Actions\Table\RefundAgreeReshipmentTableAction::make('agree-reshipment'),
                          Order\Resources\OrderRefundResource\Actions\Table\RefundReshipmentTableAction::make('reshipment'),

                          Tables\Actions\ActionGroup::make([

                                                               Order\Resources\OrderRefundResource\Actions\Table\RefundStarTableAction::make('star'),
                                                               Order\Resources\OrderRefundResource\Actions\Table\RefundSellerRemarksTableAction::make('seller-remarks'),
                                                           ])->label('more'),

                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                       Tables\Actions\ForceDeleteBulkAction::make(),
                                                                       Tables\Actions\RestoreBulkAction::make(),
                                                                   ]),
                          ]);

        return static::translationLabels($table);

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
            'index'  => Pages\ListOrderRefunds::route('/'),
            'create' => Pages\CreateOrderRefund::route('/create'),
            'view'   => Pages\ViewOrderRefund::route('/{record}'),
            'edit'   => Pages\EditOrderRefund::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery() : Builder
    {
        return parent::getEloquentQuery()
                     ->withoutGlobalScopes([
                                               SoftDeletingScope::class,
                                           ]);
    }
}
