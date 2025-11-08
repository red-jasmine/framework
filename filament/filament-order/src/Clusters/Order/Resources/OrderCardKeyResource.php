<?php

namespace RedJasmine\FilamentOrder\Clusters\Order\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\BulkActionGroup;
use RedJasmine\FilamentOrder\Clusters\Order\Resources\OrderCardKeyResource\Pages\ListOrderCardKeys;
use Filament\Forms;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 5;

    protected static ?string $model = OrderCardKey::class;


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
                         Select::make('order_product_id')
                                                ->relationship('orderProduct', 'title')
                                                ->required(),
                         TextInput::make('quantity')
                                                   ->required()
                                                   ->numeric()
                                                   ->default(1),
                         TextInput::make('content_type')
                                                   ->required()
                                                   ->maxLength(255)
                                                   ->default('text'),
                         Textarea::make('content')
                                                  ->columnSpanFull(),
                         TextInput::make('source_type')
                                                   ->maxLength(255),
                         TextInput::make('source_id')
                                                   ->maxLength(255),
                         TextInput::make('status')
                                                   ->maxLength(255),
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
                          TextColumn::make('entity_id')->copyable(),
                          TextColumn::make('quantity'),
                          TextColumn::make('content_type')->useEnum(),
                          TextColumn::make('source_type'),
                          TextColumn::make('source_id'),
                          TextColumn::make('status')->useEnum(),
                          
                      ])
            ->deferFilters()
            ->filters([
                          InputFilter::make('id'),
                          InputFilter::make('order_id'),
                          SelectFilter::make('status')->options(OrderCardKeyStatusEnum::options()),
                          DateRangeFilter::make('created_at'),
                      ], FiltersLayout::AboveContent)
            ->recordActions([

                      ])
            ->toolbarActions([
                              BulkActionGroup::make([

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
            'index' => ListOrderCardKeys::route('/'),

        ];
    }
}
