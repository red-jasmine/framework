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
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource\RelationManagers;
use RedJasmine\Order\Application\Services\Logistics\OrderLogisticsApplicationService;
use RedJasmine\Order\Application\Services\Logistics\OrderLogisticsQueryService;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;
use RedJasmine\Order\Domain\Models\OrderLogistics;

class OrderLogisticsResource extends Resource
{


    use ResourcePageHelper;

    protected static bool    $onlyOwner = false;
    protected static ?string $service   = OrderLogisticsApplicationService::class;
    

    protected static string $translationNamespace = 'red-jasmine-order::logistics';

    public static function getModelLabel() : string
    {
        return __(static::$translationNamespace.'.label');
    }

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int    $navigationSort = 3;

    protected static ?string $model   = OrderLogistics::class;
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
                Forms\Components\TextInput::make('order_id')
                                          ->required()
                                          ->numeric(),
                Forms\Components\TextInput::make('entity_type')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('entity_id')
                                          ->required()
                                          ->numeric(),
                Forms\Components\TextInput::make('order_product_id')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('shipper')
                                          ->required()
                                          ->maxLength(32),
                Forms\Components\TextInput::make('status')
                                          ->required()
                                          ->maxLength(32),
                Forms\Components\TextInput::make('logistics_company_code')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('logistics_no')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\DateTimePicker::make('shipping_time'),
                Forms\Components\DateTimePicker::make('collect_time'),
                Forms\Components\DateTimePicker::make('dispatch_time'),
                Forms\Components\DateTimePicker::make('signed_time'),
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
                UserAbleColumn::make('buyer'),
                Tables\Columns\TextColumn::make('entity_type')->useEnum(),
                Tables\Columns\TextColumn::make('entity_id')->copyable()
                ,
                Tables\Columns\TextColumn::make('order_product_id')->copyable()
                ,
                Tables\Columns\TextColumn::make('shipper')->useEnum(),
                Tables\Columns\TextColumn::make('status')->useEnum(),
                Tables\Columns\TextColumn::make('logistics_company_code')
                ,
                Tables\Columns\TextColumn::make('logistics_no')->copyable()
                ,
                Tables\Columns\TextColumn::make('shipping_time')
                                         ->dateTime()
                ,
                Tables\Columns\TextColumn::make('collect_time')
                                         ->dateTime()
                ,
                Tables\Columns\TextColumn::make('dispatch_time')
                                         ->dateTime()
                ,
                Tables\Columns\TextColumn::make('signed_time')
                                         ->dateTime()
                ,

                ...static::operateTableColumns()
            ])
            ->deferFilters()
            ->filters([
                InputFilter::make('id'),
                InputFilter::make('order_id'),
                InputFilter::make('logistics_company_code'),
                InputFilter::make('logistics_no'),
                //Tables\Filters\SelectFilter::make('shipper')->options(LogisticsShipperEnum::options()),
                Tables\Filters\SelectFilter::make('status')->options(LogisticsStatusEnum::options()),

                DateRangeFilter::make('shipping_time'),
                DateRangeFilter::make('signed_time'),
            ], Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrderLogistics::route('/'),
            //'create' => Pages\CreateOrderLogistics::route('/create'),
            //'edit'   => Pages\EditOrderLogistics::route('/{record}/edit'),
        ];
    }
}
