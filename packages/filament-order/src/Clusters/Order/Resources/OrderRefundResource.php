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
                Forms\Components\TextInput::make('outer_id')
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
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_id'),
                Tables\Columns\TextColumn::make('order_product_id'),
                Tables\Columns\TextColumn::make('seller_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_id'),
                Tables\Columns\TextColumn::make('buyer_type'),
                Tables\Columns\TextColumn::make('buyer_id'),
                Tables\Columns\TextColumn::make('order_product_type')->useEnum(),
                Tables\Columns\TextColumn::make('shipping_type')->useEnum(),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('sku_name'),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('product_type'),

                Tables\Columns\TextColumn::make('sku_id'),
                Tables\Columns\TextColumn::make('category_id'),
                Tables\Columns\TextColumn::make('product_group_id'),
                Tables\Columns\TextColumn::make('outer_id'),
                Tables\Columns\TextColumn::make('outer_sku_id'),
                Tables\Columns\TextColumn::make('barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('num'),
                Tables\Columns\TextColumn::make('price')->money(),
                Tables\Columns\TextColumn::make('cost_price')->money(),
                Tables\Columns\TextColumn::make('product_amount')->money(),
                Tables\Columns\TextColumn::make('tax_amount')->money(),
                Tables\Columns\TextColumn::make('discount_amount')->money(),
                Tables\Columns\TextColumn::make('payable_amount')->money(),
                Tables\Columns\TextColumn::make('payment_amount')->money(),
                Tables\Columns\TextColumn::make('divided_payment_amount')->money(),
                Tables\Columns\TextColumn::make('shipping_status'),
                Tables\Columns\TextColumn::make('refund_type')->useEnum(),
                Tables\Columns\TextColumn::make('phase')->useEnum(),
                Tables\Columns\TextColumn::make('has_good_return'),
                Tables\Columns\TextColumn::make('good_status'),
                Tables\Columns\TextColumn::make('reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('outer_refund_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('refund_status')->useEnum(),
                Tables\Columns\TextColumn::make('freight_amount')->money(),
                Tables\Columns\TextColumn::make('refund_amount')->money(),
                Tables\Columns\TextColumn::make('total_refund_amount')->money(),

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
