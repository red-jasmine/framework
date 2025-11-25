<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use Symfony\Component\Intl\Currencies;

class VariantsRepeater extends Repeater
{


    protected function setUp() : void
    {
        parent::setUp();

        $this->relationship('variants');
        $this->dehydrated();
        $this->saveRelationshipsUsing(null);
        $this->label(__('red-jasmine-product::product.fields.variants'));
        $this->table([
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.image'))->width('100px'),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.attrs_name')),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.sku')),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.price'))->markAsRequired(),
            // Repeater\TableColumn::make(__('red-jasmine-product::product.fields.stock'))->markAsRequired(),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.market_price')),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.cost_price')),
            // Repeater\TableColumn::make(__('red-jasmine-product::product.fields.safety_stock')),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.package_unit')),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.package_quantity'))->markAsRequired(),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.barcode')),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.weight')),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.status'))->width('120px'),
            Repeater\TableColumn::make(__('red-jasmine-product::product.fields.stocks'))->width('40%')->markAsRequired(),


        ]);
        $this->schema([
            Hidden::make('attrs_sequence'),
            FileUpload::make('image')->image()->panelLayout('compact'),
            TextInput::make('attrs_name')->readOnly(),
            TextInput::make('sku'),
            TextInput::make('price')->required()
                     ->prefix(fn(Get $get) => $get('../../currency') ? Currencies::getSymbol($get('../../currency'),
                         app()->getLocale()) : null)
                     ->formatStateUsing(fn($state) => $state['formatted'] ?? null),
            // TextInput::make('stock')->minValue(0)->integer()->required(),

            TextInput::make('market_price')
                     ->prefix(fn(Get $get) => $get('../../currency') ? Currencies::getSymbol($get('../../currency'),
                         app()->getLocale()) : null)
                     ->formatStateUsing(fn($state) => $state['formatted'] ?? null),
            TextInput::make('cost_price')
                     ->prefix(fn(Get $get) => $get('../../currency') ? Currencies::getSymbol($get('../../currency'),
                         app()->getLocale()) : null)
                     ->formatStateUsing(fn($state) => $state['formatted'] ?? null),
            // TextInput::make('safety_stock')->numeric()->default(0),
            TextInput::make('package_unit')
                     ->label(__('red-jasmine-product::product.fields.package_unit'))
                     ->maxLength(32)
                     ->placeholder('SKU的包装单位:件/个/套/箱'),
            TextInput::make('package_quantity')
                     ->label(__('red-jasmine-product::product.fields.package_quantity'))
                     ->integer()
                     ->default(1)
                     ->minValue(1)
                     ->placeholder('每个包装单位包含的数量'),
            TextInput::make('barcode')->maxLength(32),
            TextInput::make('weight')->suffix('KG'),
            Select::make('status')
                  ->selectablePlaceholder(false)
                  ->required()
                  ->default(ProductStatusEnum::AVAILABLE->value)
                  ->options(ProductStatusEnum::variantStatus()),

            Repeater::make('stocks')
                    ->relationship('stocks')
                    ->dehydrated()
                    ->saveRelationshipsUsing(null)
                    ->label(__('red-jasmine-product::product.fields.stocks'))
                    ->schema([
                        FusedGroup::make(
                            [
                                TextInput::make('warehouse_id')
                                         ->distinct()
                                         ->label(__('red-jasmine-product::product-stock.fields.warehouse_id'))
                                         ->prefix(__('red-jasmine-product::product-stock.fields.warehouse_id'))
                                         ->default(0)
                                         ->required(),
                                TextInput::make('stock')
                                         ->prefix(__('red-jasmine-product::product-stock.fields.stock'))
                                         ->minValue(0)
                                         ->integer()
                                         ->required(),
                                TextInput::make('safety_stock')
                                         ->prefix(__('red-jasmine-product::product-stock.fields.safety_stock'))
                                         ->minValue(0)
                                         ->integer()
                                         ->required(),
                                Select::make('is_active')
                                      ->prefix(__('red-jasmine-product::product-stock.fields.is_active'))
                                      ->required()
                                      ->boolean()
                                      ->default(1)


                            ]
                        )->columns(4),
                    ])
                    ->inlineLabel(false)
                    ->columnSpan('full')
                    ->reorderable(false)
                    ->addable(true)
                    ->deletable(false)
                    ->default([]),

        ]);
        $this->inlineLabel(false);
        $this->columnSpan('full');
        $this->reorderable(false);
        $this->addable(false);
        $this->default([]);


    }

}
