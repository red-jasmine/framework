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
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderCardKeyResource\Pages;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderCardKeyResource\RelationManagers;
use RedJasmine\Order\Application\Services\OrderCardKeyApplicationService;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyStatusEnum;
use RedJasmine\Order\Domain\Models\OrderCardKey;

class OrderCardKeyResource extends Resource
{


    use ResourcePageHelper;

    protected static bool $onlyOwner = false;

    protected static ?string $service = OrderCardKeyApplicationService::class;


    protected static string $translationNamespace = 'red-jasmine-order::card-keys';

    public static function getModelLabel() : string
    {
        return __(static::$translationNamespace . '.label');
    }

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 5;

    protected static ?string $model = OrderCardKey::class;


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
                         Forms\Components\Select::make('order_product_id')
                                                ->relationship('orderProduct', 'title')
                                                ->required(),
                         Forms\Components\TextInput::make('quantity')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(1),
                         Forms\Components\TextInput::make('content_type')
                                                   ->required()
                                                   ->maxLength(255)
                                                   ->default('text'),
                         Forms\Components\Textarea::make('content')
                                                  ->columnSpanFull(),
                         Forms\Components\TextInput::make('source_type')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('source_id')
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('status')
                                                   ->maxLength(255),
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
                          Tables\Columns\TextColumn::make('entity_id')->copyable(),
                          Tables\Columns\TextColumn::make('quantity'),
                          Tables\Columns\TextColumn::make('content_type')->useEnum(),
                          Tables\Columns\TextColumn::make('source_type'),
                          Tables\Columns\TextColumn::make('source_id'),
                          Tables\Columns\TextColumn::make('status')->useEnum(),
                          ...static::operateTableColumns()
                      ])
            ->deferFilters()
            ->filters([
                          InputFilter::make('id'),
                          InputFilter::make('order_id'),
                          Tables\Filters\SelectFilter::make('status')->options(OrderCardKeyStatusEnum::options()),
                          DateRangeFilter::make('created_at'),
                      ], Tables\Enums\FiltersLayout::AboveContent)
            ->actions([

                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([

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
            'index' => Pages\ListOrderCardKeys::route('/'),

        ];
    }
}
