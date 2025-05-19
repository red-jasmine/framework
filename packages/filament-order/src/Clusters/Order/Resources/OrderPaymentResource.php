<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Columns\UserAbleColumn;
use RedJasmine\FilamentCore\Filters\DateRangeFilter;
use RedJasmine\FilamentCore\Filters\InputFilter;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderPaymentResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderPaymentResource\RelationManagers;
use RedJasmine\Order\Application\Services\Payments\OrderPaymentApplicationService;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\OrderPayment;

class OrderPaymentResource extends Resource
{


    use ResourcePageHelper;

    protected static bool    $onlyOwner      = false;

    protected static ?string $service = OrderPaymentApplicationService::class;


    protected static string $translationNamespace = 'red-jasmine-order::payment';

    public static function getModelLabel() : string
    {
        return __(static::$translationNamespace.'.label');
    }

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 4;


    protected static ?string $model = OrderPayment::class;

    protected static ?string $cluster = Order::class;

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
                Forms\Components\TextInput::make('buyer_type')
                                          ->required()
                                          ->maxLength(32),
                Forms\Components\TextInput::make('buyer_id')
                                          ->required()
                                          ->numeric(),
                Forms\Components\Select::make('order_id')
                                       ->relationship('order', 'title')
                                       ->required(),
                Forms\Components\TextInput::make('entity_type')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('entity_id')
                                          ->required()
                                          ->numeric(),
                Forms\Components\TextInput::make('amount_type')
                                          ->required()
                                          ->maxLength(32),
                Forms\Components\TextInput::make('payment_amount')
                                          ->required()
                                          ->numeric(),
                Forms\Components\TextInput::make('status')
                                          ->required()
                                          ->maxLength(32),
                Forms\Components\DateTimePicker::make('payment_time'),
                Forms\Components\TextInput::make('payment_type')
                                          ->maxLength(32),
                Forms\Components\TextInput::make('payment_id')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('payment_method')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('payment_channel')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('payment_channel_no')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('message')
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
        $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->copyable(),
                Tables\Columns\TextColumn::make('order_id')->copyable(),
                UserAbleColumn::make('seller')->toggleable(isToggledHiddenByDefault: true),
                UserAbleColumn::make('buyer')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('entity_type')
                                         ->useEnum(),
                Tables\Columns\TextColumn::make('entity_id'),
                Tables\Columns\TextColumn::make('amount_type')
                                         ->useEnum(),
                Tables\Columns\TextColumn::make('payment_amount')
                ,
                Tables\Columns\TextColumn::make('status')->useEnum()
                ,
                Tables\Columns\TextColumn::make('payment_time')
                                         ->dateTime()
                ,
                Tables\Columns\TextColumn::make('payment_type')
                ,
                Tables\Columns\TextColumn::make('payment_id')
                ,
                Tables\Columns\TextColumn::make('payment_method')
                ,
                Tables\Columns\TextColumn::make('payment_channel')
                ,
                Tables\Columns\TextColumn::make('payment_channel_no')
                ,
                Tables\Columns\TextColumn::make('message')
                ,
                ...static::operateTableColumns()
            ])
            ->deferFilters()
            ->filters([
                InputFilter::make('id'),
                InputFilter::make('order_id'),
                InputFilter::make('payment_id'),
                InputFilter::make('payment_channel'),
                InputFilter::make('payment_channel_no'),
                Tables\Filters\SelectFilter::make('amount_type')->options(AmountTypeEnum::options()),
                Tables\Filters\SelectFilter::make('status')->options(PaymentStatusEnum::options()),
                DateRangeFilter::make('payment_time')
            ], Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrderPayments::route('/'),
        ];
    }
}
