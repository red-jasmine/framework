<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentOrder\Clusters\Order;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderLogisticsResource\RelationManagers;
use RedJasmine\Order\Application\Services\OrderLogisticsCommandService;
use RedJasmine\Order\Application\Services\OrderLogisticsQueryService;
use RedJasmine\Order\Domain\Models\OrderLogistics;

class OrderLogisticsResource extends Resource
{


    use ResourcePageHelper;

    protected static bool    $onlyOwner      = false;
    protected static ?string $commandService = OrderLogisticsCommandService::class;
    protected static ?string $queryService   = OrderLogisticsQueryService::class;


    protected static string  $translationNamespace = 'red-jasmine-order::logistics';
    public static function getModelLabel() : string
    {
        return __(static::$translationNamespace . '.label');
    }


    protected static ?string $model = OrderLogistics::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $cluster = Order::class;
    protected static ?int $navigationSort = 3;

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
            ->columns([
                          Tables\Columns\TextColumn::make('seller_type'),
                          Tables\Columns\TextColumn::make('seller_id'),
                          Tables\Columns\TextColumn::make('buyer_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('buyer_id')
                                                   ->numeric(),

                          Tables\Columns\TextColumn::make('order_id'),
                          Tables\Columns\TextColumn::make('entity_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('entity_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('order_product_id')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('shipper')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('status')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('logistics_company_code')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('logistics_no')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('shipping_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('collect_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('dispatch_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('signed_time')
                                                   ->dateTime()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('version')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('creator_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('creator_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('updater_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('updater_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('created_at')
                                                   ->dateTime()
                                                   ->sortable()
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('updated_at')
                                                   ->dateTime()
                                                   ->sortable()
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('deleted_at')
                                                   ->dateTime()
                                                   ->sortable()
                                                   ->toggleable(isToggledHiddenByDefault: true),
                      ])
            ->filters([
                          //
                      ])
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
            'index'  => Pages\ListOrderLogistics::route('/'),
            //'create' => Pages\CreateOrderLogistics::route('/create'),
            //'edit'   => Pages\EditOrderLogistics::route('/{record}/edit'),
        ];
    }
}
