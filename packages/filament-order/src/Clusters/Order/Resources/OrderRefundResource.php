<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
