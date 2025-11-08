<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource\Pages\ListOrderLogistics;
use Filament\Forms;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-truck';
    protected static ?int    $navigationSort = 3;

    protected static ?string $model   = OrderLogistics::class;
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
                TextInput::make('order_id')
                                          ->required()
                                          ->numeric(),
                TextInput::make('entity_type')
                                          ->required()
                                          ->maxLength(255),
                TextInput::make('entity_id')
                                          ->required()
                                          ->numeric(),
                TextInput::make('order_product_id')
                                          ->maxLength(255),
                TextInput::make('shipper')
                                          ->required()
                                          ->maxLength(32),
                TextInput::make('status')
                                          ->required()
                                          ->maxLength(32),
                TextInput::make('logistics_company_code')
                                          ->required()
                                          ->maxLength(255),
                TextInput::make('logistics_no')
                                          ->required()
                                          ->maxLength(255),
                DateTimePicker::make('shipping_time'),
                DateTimePicker::make('collect_time'),
                DateTimePicker::make('dispatch_time'),
                DateTimePicker::make('signed_time'),
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
                UserAbleColumn::make('buyer'),
                TextColumn::make('entity_type')->useEnum(),
                TextColumn::make('entity_id')->copyable()
                ,
                TextColumn::make('order_product_id')->copyable()
                ,
                TextColumn::make('shipper')->useEnum(),
                TextColumn::make('status')->useEnum(),
                TextColumn::make('logistics_company_code')
                ,
                TextColumn::make('logistics_no')->copyable()
                ,
                TextColumn::make('shipping_time')
                                         ->dateTime()
                ,
                TextColumn::make('collect_time')
                                         ->dateTime()
                ,
                TextColumn::make('dispatch_time')
                                         ->dateTime()
                ,
                TextColumn::make('signed_time')
                                         ->dateTime()
                ,

                
            ])
            ->deferFilters()
            ->filters([
                InputFilter::make('id'),
                InputFilter::make('order_id'),
                InputFilter::make('logistics_company_code'),
                InputFilter::make('logistics_no'),
                //Tables\Filters\SelectFilter::make('shipper')->options(LogisticsShipperEnum::options()),
                SelectFilter::make('status')->options(LogisticsStatusEnum::options()),

                DateRangeFilter::make('shipping_time'),
                DateRangeFilter::make('signed_time'),
            ], FiltersLayout::AboveContent)
            ->recordActions([
                //Tables\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListOrderLogistics::route('/'),
            //'create' => Pages\CreateOrderLogistics::route('/create'),
            //'edit'   => Pages\EditOrderLogistics::route('/{record}/edit'),
        ];
    }
}
