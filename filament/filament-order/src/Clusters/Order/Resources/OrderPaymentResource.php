<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\BulkActionGroup;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderPaymentResource\Pages\ListOrderPayments;
use Filament\Forms;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 4;


    protected static ?string $model = OrderPayment::class;

    protected static ?string $cluster = Order::class;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('seller_type')
                                          ->required()
                                          ->maxLength(32),
                TextInput::make('seller_id')
                                          ->required()
                                          ->numeric(),
                TextInput::make('buyer_type')
                                          ->required()
                                          ->maxLength(32),
                TextInput::make('buyer_id')
                                          ->required()
                                          ->numeric(),
                Select::make('order_id')
                                       ->relationship('order', 'title')
                                       ->required(),
                TextInput::make('entity_type')
                                          ->required()
                                          ->maxLength(255),
                TextInput::make('entity_id')
                                          ->required()
                                          ->numeric(),
                TextInput::make('amount_type')
                                          ->required()
                                          ->maxLength(32),
                TextInput::make('payment_amount')
                                          ->required()
                                          ->numeric(),
                TextInput::make('status')
                                          ->required()
                                          ->maxLength(32),
                DateTimePicker::make('payment_time'),
                TextInput::make('payment_type')
                                          ->maxLength(32),
                TextInput::make('payment_id')
                                          ->maxLength(255),
                TextInput::make('payment_method')
                                          ->maxLength(255),
                TextInput::make('payment_channel')
                                          ->maxLength(255),
                TextInput::make('payment_channel_no')
                                          ->maxLength(255),
                TextInput::make('message')
                                          ->maxLength(255),
                TextInput::make('version')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                TextInput::make('creator_type')
                                          ->maxLength(255),
                TextInput::make('creator_id')
                                          ->numeric(),
                TextInput::make('updater_type')
                                          ->maxLength(255),
                TextInput::make('updater_id')
                                          ->numeric(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')->copyable(),
                TextColumn::make('order_id')->copyable(),
                UserAbleColumn::make('seller')->toggleable(isToggledHiddenByDefault: true),
                UserAbleColumn::make('buyer')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('entity_type')
                                         ->useEnum(),
                TextColumn::make('entity_id'),
                TextColumn::make('amount_type')
                                         ->useEnum(),
                TextColumn::make('payment_amount')
                ,
                TextColumn::make('status')->useEnum()
                ,
                TextColumn::make('payment_time')
                                         ->dateTime()
                ,
                TextColumn::make('payment_type')
                ,
                TextColumn::make('payment_id')
                ,
                TextColumn::make('payment_method')
                ,
                TextColumn::make('payment_channel')
                ,
                TextColumn::make('payment_channel_no')
                ,
                TextColumn::make('message')
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
                SelectFilter::make('amount_type')->options(AmountTypeEnum::options()),
                SelectFilter::make('status')->options(PaymentStatusEnum::options()),
                DateRangeFilter::make('payment_time')
            ], FiltersLayout::AboveContent)
            ->recordActions([
                //Tables\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
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
            'index' => ListOrderPayments::route('/'),
        ];
    }
}
