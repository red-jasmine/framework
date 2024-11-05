<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Mokhosh\FilamentRating\Entries\RatingEntry;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderRefundResource\RelationManagers;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\OrderQueryService;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\Services\RefundQueryService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\OrderRefund;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderRefundResource extends Resource
{

    use ResourcePageHelper;

    protected static bool    $onlyOwner      = false;
    protected static ?string $commandService = RefundCommandService::class;
    protected static ?string $queryService   = RefundQueryService::class;
    protected static ?string $createCommand  = RefundCreateCommand::class;

    protected static ?string $model = OrderRefund::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Order::class;

    protected static ?int $navigationSort = 2;
    public static function getModelLabel() : string
    {
        return __('red-jasmine-order::refund.labels.refund');
    }

    public static function infolist(Infolist $infoList) : Infolist
    {

        $infoList ->schema([
                     Section::make(static fn(Model $record) => $record->id)
                            ->schema([
                                         TextEntry::make('refund_status')->label(__('red-jasmine-order::refund.fields.refund_status'))->useEnum(),
                                         TextEntry::make('seller_custom_status')->label(__('red-jasmine-order::refund.fields.seller_custom_status'))->badge(),
                                         TextEntry::make('info.seller_remarks')->label(__('red-jasmine-order::refund.fields.seller_remarks')),
                                         TextEntry::make('info.seller_remarks')->label(__('red-jasmine-order::refund.fields.seller_remarks')),
                                     ])
                            ->columns(2)
                     ,
                     Section::make('退款')
                            ->schema([
                                         TextEntry::make('refund_type')->label(__('red-jasmine-order::refund.fields.refund_type'))->useEnum(),
                                         TextEntry::make('phase')->label(__('red-jasmine-order::refund.fields.phase'))->useEnum(),
                                         TextEntry::make('has_good_return')->label(__('red-jasmine-order::refund.fields.has_good_return')),
                                         TextEntry::make('good_status')->label(__('red-jasmine-order::refund.fields.good_status'))->useEnum(),
                                         TextEntry::make('reason')->label(__('red-jasmine-order::refund.fields.reason')),
                                         TextEntry::make('freight_amount')->label(__('red-jasmine-order::refund.fields.freight_amount')),
                                         TextEntry::make('refund_amount')->label(__('red-jasmine-order::refund.fields.refund_amount')),
                                         TextEntry::make('total_refund_amount')->label(__('red-jasmine-order::refund.fields.total_refund_amount')),
                                         TextEntry::make('description')->label(__('red-jasmine-order::refund.fields.description')),
                                         ImageEntry::make('info.images')->label(__('red-jasmine-order::refund.fields.images'))->stacked()->limit(10)
                                             ->checkFileExistence(false)
                                             ->limitedRemainingText(),

                                     ])
                            ->inlineLabel()
                            ->columns(6),

                     Section::make('商品')
                            ->schema([
                                         TextEntry::make('title')->label(__('red-jasmine-order::refund.fields.title')),
                                         ImageEntry::make('image')->label(__('red-jasmine-order::refund.fields.image')),
                                         TextEntry::make('sku_name')->label(__('red-jasmine-order::refund.fields.sku_name')),
                                         TextEntry::make('num')->label(__('red-jasmine-order::refund.fields.num')),
                                         TextEntry::make('unit_quantity')->label(__('red-jasmine-order::refund.fields.unit_quantity')),
                                         TextEntry::make('unit')->label(__('red-jasmine-order::refund.fields.unit')),
                                         TextEntry::make('price')->label(__('red-jasmine-order::refund.fields.price')),
                                         TextEntry::make('product_amount')->label(__('red-jasmine-order::refund.fields.product_amount')),
                                         TextEntry::make('tax_amount')->label(__('red-jasmine-order::refund.fields.tax_amount')),
                                         TextEntry::make('discount_amount')->label(__('red-jasmine-order::refund.fields.discount_amount')),
                                         TextEntry::make('payable_amount')->label(__('red-jasmine-order::refund.fields.payable_amount')),
                                         TextEntry::make('payment_amount')->label(__('red-jasmine-order::refund.fields.payment_amount')),
                                         TextEntry::make('divided_payment_amount')->label(__('red-jasmine-order::refund.fields.divided_payment_amount')),
                                         TextEntry::make('shipping_status')->label(__('red-jasmine-order::refund.fields.shipping_status')),
                                     ])
                         ->inlineLabel()
                            ->columns(6),
                     Section::make('退款信息')
                            ->schema([

                                         Fieldset::make('infos')
                                                 ->schema([
                                                              TextEntry::make('id')->copyable()->label(__('red-jasmine-order::refund.fields.id')),
                                                              TextEntry::make('order_id')->copyable()->label(__('red-jasmine-order::refund.fields.order_id')),
                                                              TextEntry::make('created_time')->label(__('red-jasmine-order::refund.fields.created_time')),
                                                              TextEntry::make('end_time')->label(__('red-jasmine-order::refund.fields.end_time')),

                                                          ])
                                                 ->inlineLabel()
                                                 ->columns(1)
                                                 ->columnSpan(1),


                                         Fieldset::make('seller')
                                                 ->label(__('red-jasmine-order::refund.fields.seller'))
                                                 ->schema([
                                                              TextEntry::make('seller_type')->label(__('red-jasmine-order::refund.fields.seller_type')),
                                                              TextEntry::make('seller_id')->copyable()->label(__('red-jasmine-order::refund.fields.seller_id')),
                                                              TextEntry::make('seller_nickname')->copyable()->label(__('red-jasmine-order::refund.fields.seller_nickname')),
                                                          ])
                                                 ->inlineLabel()
                                                 ->columns(1)
                                                 ->columnSpan(1),
                                         Fieldset::make('buyer')
                                                 ->label(__('red-jasmine-order::refund.fields.buyer'))
                                                 ->schema([
                                                              TextEntry::make('buyer_type')->label(__('red-jasmine-order::refund.fields.buyer_type')),
                                                              TextEntry::make('buyer_id')->copyable()->label(__('red-jasmine-order::refund.fields.buyer_id')),
                                                              TextEntry::make('buyer_nickname')->copyable()->label(__('red-jasmine-order::refund.fields.buyer_nickname')),
                                                          ])
                                                 ->inlineLabel()
                                                 ->columns(1)
                                                 ->columnSpan(1),

                                     ])->columns(5),


                               ]);

        return  $infoList;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'title')
                    ->required(),
                Forms\Components\TextInput::make('order_product_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('seller_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('seller_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('buyer_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('buyer_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('order_product_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('shipping_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sku_name')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\TextInput::make('product_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                Forms\Components\TextInput::make('sku_id')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('category_id')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('product_group_id')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('outer_product_id')
                    ->maxLength(64),
                Forms\Components\TextInput::make('outer_sku_id')
                    ->maxLength(64),
                Forms\Components\TextInput::make('barcode')
                    ->maxLength(64),
                Forms\Components\TextInput::make('unit_quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('unit')
                    ->maxLength(255),
                Forms\Components\TextInput::make('num')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->prefix('$'),
                Forms\Components\TextInput::make('cost_price')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('product_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('payable_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('payment_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('divided_payment_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('shipping_status')
                    ->maxLength(32),
                Forms\Components\TextInput::make('refund_type')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('phase')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('has_good_return')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('good_status')
                    ->maxLength(32),
                Forms\Components\TextInput::make('reason')
                    ->maxLength(255),
                Forms\Components\TextInput::make('outer_refund_id')
                    ->maxLength(64),
                Forms\Components\TextInput::make('refund_status')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('freight_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('refund_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_refund_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\DateTimePicker::make('created_time'),
                Forms\Components\DateTimePicker::make('end_time'),
                Forms\Components\TextInput::make('seller_custom_status')
                    ->maxLength(30),
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

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'DESC')
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('red-jasmine-order::refund.fields.id')),

                Tables\Columns\TextColumn::make('order_id')->label(__('red-jasmine-order::refund.fields.order_id')),
//                Tables\Columns\TextColumn::make('order_product_id') ->label(__('red-jasmine-order::refund.fields.order_product_id')),

                Tables\Columns\TextColumn::make('order_product_type')->useEnum() ->label(__('red-jasmine-order::refund.fields.order_product_type')),
                Tables\Columns\TextColumn::make('shipping_type')->useEnum()->label(__('red-jasmine-order::refund.fields.shipping_type')),

                Tables\Columns\TextColumn::make('title')->label(__('red-jasmine-order::refund.fields.title')),
//                Tables\Columns\TextColumn::make('sku_name')->label(__('red-jasmine-order::refund.fields.sku_name')),
//                Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine-order::refund.fields.image')),
//                Tables\Columns\TextColumn::make('product_type')->label(__('red-jasmine-order::refund.fields.product_type')),
//                Tables\Columns\TextColumn::make('sku_id')->label(__('red-jasmine-order::refund.fields.sku_id')),
//                Tables\Columns\TextColumn::make('category_id')->label(__('red-jasmine-order::refund.fields.category_id')),
//                Tables\Columns\TextColumn::make('product_group_id')->label(__('red-jasmine-order::refund.fields.product_group_id')),
//                Tables\Columns\TextColumn::make('outer_product_id')->label(__('red-jasmine-order::refund.fields.outer_product_id')),
//                Tables\Columns\TextColumn::make('outer_sku_id')->label(__('red-jasmine-order::refund.fields.outer_sku_id')),
//                Tables\Columns\TextColumn::make('barcode')->label(__('red-jasmine-order::refund.fields.barcode')),
//                Tables\Columns\TextColumn::make('unit_quantity')->label(__('red-jasmine-order::refund.fields.unit_quantity')),
//                Tables\Columns\TextColumn::make('unit')->label(__('red-jasmine-order::refund.fields.sku_name')),
//                Tables\Columns\TextColumn::make('num')->label(__('red-jasmine-order::refund.fields.sku_name')),
//                Tables\Columns\TextColumn::make('price')->money()->label(__('red-jasmine-order::refund.fields.price')),
//                Tables\Columns\TextColumn::make('cost_price')->money()->label(__('red-jasmine-order::refund.fields.cost_price')),
//                Tables\Columns\TextColumn::make('product_amount')->money()->label(__('red-jasmine-order::refund.fields.product_amount')),
//                Tables\Columns\TextColumn::make('tax_amount')->money()->label(__('red-jasmine-order::refund.fields.tax_amount')),
//                Tables\Columns\TextColumn::make('discount_amount')->money()->label(__('red-jasmine-order::refund.fields.discount_amount')),
//                Tables\Columns\TextColumn::make('payable_amount')->money()->label(__('red-jasmine-order::refund.fields.payable_amount')),
//                Tables\Columns\TextColumn::make('payment_amount')->money()->label(__('red-jasmine-order::refund.fields.payment_amount')),
//                Tables\Columns\TextColumn::make('divided_payment_amount')->money()->label(__('red-jasmine-order::refund.fields.divided_payment_amount')),
//                Tables\Columns\TextColumn::make('shipping_status')->label(__('red-jasmine-order::refund.fields.shipping_status')),
//


                Tables\Columns\TextColumn::make('refund_type')->useEnum()->label(__('red-jasmine-order::refund.fields.refund_type')),
                Tables\Columns\TextColumn::make('phase')->useEnum()->label(__('red-jasmine-order::refund.fields.phase')),
                Tables\Columns\TextColumn::make('has_good_return')->label(__('red-jasmine-order::refund.fields.has_good_return')),
                Tables\Columns\TextColumn::make('good_status')->label(__('red-jasmine-order::refund.fields.good_status')),
                Tables\Columns\TextColumn::make('reason')->label(__('red-jasmine-order::refund.fields.reason')),
                Tables\Columns\TextColumn::make('outer_refund_id')->label(__('red-jasmine-order::refund.fields.outer_refund_id')),

                Tables\Columns\TextColumn::make('refund_status')->useEnum()->label(__('red-jasmine-order::refund.fields.refund_status')),
                Tables\Columns\TextColumn::make('freight_amount')->money()->label(__('red-jasmine-order::refund.fields.freight_amount')),
                Tables\Columns\TextColumn::make('refund_amount')->money()->label(__('red-jasmine-order::refund.fields.refund_amount')),
                Tables\Columns\TextColumn::make('total_refund_amount')->money()->label(__('red-jasmine-order::refund.fields.total_refund_amount')),

                ...static::operateTableColumns()
            ])
            ->filters([

                Tables\Filters\Filter::make('order_id')->form(
                    [
                        Forms\Components\TextInput::make('order_id')->label(__('red-jasmine-order::refund.fields.order_id'))
                    ]
                )

                                                       ->query(function (Builder $query, array $data): Builder {

                    return $query->when( $data['order_id'], fn (Builder $query, $data): Builder => $query->where('order_id', $data ));
                }),






                Tables\Filters\SelectFilter::make('refund_status')->label(__('red-jasmine-order::refund.fields.refund_status'))->options(RefundStatusEnum::options()),
                Tables\Filters\SelectFilter::make('refund_type')->label(__('red-jasmine-order::refund.fields.refund_type'))->options(RefundTypeEnum::options()),
                Tables\Filters\SelectFilter::make('phase')->label(__('red-jasmine-order::refund.fields.phase'))->options(RefundPhaseEnum::options()),
                Tables\Filters\SelectFilter::make('good_status')->label(__('red-jasmine-order::refund.fields.good_status'))->options(RefundGoodsStatusEnum::options()),


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
                               ->label(__('red-jasmine-order::refund.fields.created_time')),

                DateRangeFilter::make('end_time')
                               ->withIndicator()
                               ->alwaysShowCalendar()
                               ->timePickerSecond()
                               ->displayFormat('YYYY/MM/DD')
                               ->format('Y/m/d')
                               ->timePicker24()
                               ->icon('heroicon-o-backspace')
                               ->linkedCalendars()
                               ->autoApply()
                               ->label(__('red-jasmine-order::refund.fields.end_time')),


            ],layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderRefunds::route('/'),
            'create' => Pages\CreateOrderRefund::route('/create'),
            'view' => Pages\ViewOrderRefund::route('/{record}'),
            'edit' => Pages\EditOrderRefund::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
