<?php

namespace RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use RedJasmine\FilamentCore\Resources\Schemas\Owner;
use RedJasmine\Warehouse\Domain\Models\Enums\WarehouseTypeEnum;

class WarehouseForm
{
    /**
     * 配置表单
     */
    public static function configure(Schema $form): Schema
    {
        return $form->components([
            Section::make([
                TextInput::make('code')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.code'))
                    ->required()
                    ->maxLength(64)
                    ->unique(ignoreRecord: true)
                    ->columnSpan(1),

                TextInput::make('name')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.name'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),

                Select::make('warehouse_type')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.warehouse_type'))
                    ->options(WarehouseTypeEnum::options())
                    ->required()
                    ->default(WarehouseTypeEnum::WAREHOUSE)
                    ->columnSpan(1),

                Textarea::make('address')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.address'))
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('contact_phone')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.contact_phone'))
                    ->maxLength(32)
                    ->tel()
                    ->columnSpan(1),

                TextInput::make('contact_person')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.contact_person'))
                    ->maxLength(64)
                    ->columnSpan(1),
            ])->columns(3),

            Section::make([
                Owner::make(),

                Toggle::make('is_active')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.is_active'))
                    ->default(true)
                    ->inline(),

                Toggle::make('is_default')
                    ->label(__('red-jasmine-warehouse::warehouse.fields.is_default'))
                    ->default(false)
                    ->inline(),
            ])->grow(false),

            Section::make(__('red-jasmine-warehouse::warehouse.labels.markets'))
                ->description(__('red-jasmine-warehouse::warehouse.labels.markets_desc'))
                ->icon('heroicon-o-globe-alt')
                ->schema([
                    Repeater::make('markets')
                    ->table([
                        Repeater\TableColumn::make(__('red-jasmine-warehouse::warehouse.fields.market')),
                        Repeater\TableColumn::make(__('red-jasmine-warehouse::warehouse.fields.store')),
                        Repeater\TableColumn::make(__('red-jasmine-warehouse::warehouse.fields.market_is_active')),
                        Repeater\TableColumn::make(__('red-jasmine-warehouse::warehouse.fields.market_is_primary')),
                    ])
                        ->label(__('red-jasmine-warehouse::warehouse.fields.markets'))
                        ->schema([
                            TextInput::make('market')
                                ->label(__('red-jasmine-warehouse::warehouse.fields.market'))
                                ->required()
                                ->maxLength(32)
                                ->columnSpan(1),

                            TextInput::make('store')
                                ->label(__('red-jasmine-warehouse::warehouse.fields.store'))
                                ->required()
                                ->maxLength(32)
                                ->columnSpan(1),

                            Toggle::make('is_active')
                                ->label(__('red-jasmine-warehouse::warehouse.fields.market_is_active'))
                                ->default(true)
                                ->inline()
                                ->columnSpan(1),

                            Toggle::make('is_primary')
                                ->label(__('red-jasmine-warehouse::warehouse.fields.market_is_primary'))
                                ->default(false)
                                ->inline()
                                ->columnSpan(1),
                        ])
                        ->columns(4)
                        ->defaultItems(0)
                        ->collapsible()
                        ->itemLabel(fn(array $state): ?string =>
                            ($state['market'] ?? null) && ($state['store'] ?? null)
                                ? ($state['market'] . '/' . $state['store'])
                                : null
                        )
                        ->columnSpanFull()
                ]),
        ]);
    }
}

