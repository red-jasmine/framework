<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\FilamentProduct\Forms\Components\ProductCurrencySelect;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Product\Domain\Product\Models\Product;
use Symfony\Component\Intl\Currencies;

class ProductPriceForm
{
    /**
     * 配置表单
     */
    public static function configure(Schema $form) : Schema
    {
        return $form
            ->components([
                Section::make(__('red-jasmine-product::product-price.labels.basic_info'))
                       ->description(__('red-jasmine-product::product-price.labels.basic_info_desc'))
                       ->icon('heroicon-o-information-circle')
                       ->columns(2)
                       ->schema([
                           Select::make('product_id')
                                 ->label(__('red-jasmine-product::product-price.fields.product'))
                                 ->required()
                                 ->searchable()
                                 ->preload()
                                 ->relationship(
                                     'product',
                                     'title',
                                     fn(Builder $query) => $query->whereHas('variants')
                                 )
                                 ->live()
                                 ->afterStateUpdated(function (Get $get, Set $set) {
                                     static::reloadVariants($get, $set);
                                 })
                                 ->helperText(__('red-jasmine-product::product-price.helpers.product')),

                           Select::make('market')
                                 ->label(__('red-jasmine-product::product-price.fields.market'))
                                 ->required()
                                 ->default('*')
                                 ->live()
                                 ->afterStateUpdated(function (Get $get, Set $set) {
                                     static::reloadVariants($get, $set);
                                 })
                                 ->options([
                                     '*'  => __('red-jasmine-product::product-price.market.all'),
                                     'cn' => __('red-jasmine-product::product-price.market.cn'),
                                     'us' => __('red-jasmine-product::product-price.market.us'),
                                     'de' => __('red-jasmine-product::product-price.market.de'),
                                 ])
                                 ->helperText(__('red-jasmine-product::product-price.helpers.market')),

                           Select::make('store')
                                 ->label(__('red-jasmine-product::product-price.fields.store'))
                                 ->required()
                                 ->default('*')
                                 ->live()
                                 ->afterStateUpdated(function (Get $get, Set $set) {
                                     static::reloadVariants($get, $set);
                                 })
                                 ->options([
                                     '*'       => __('red-jasmine-product::product-price.store.all'),
                                     'default' => __('red-jasmine-product::product-price.store.default'),
                                 ])
                                 ->helperText(__('red-jasmine-product::product-price.helpers.store')),

                           Select::make('user_level')
                                 ->label(__('red-jasmine-product::product-price.fields.user_level'))
                                 ->required()
                                 ->default('*')
                                 ->live()
                                 ->afterStateUpdated(function (Get $get, Set $set) {
                                     static::reloadVariants($get, $set);
                                 })
                                 ->options([
                                     '*'        => __('red-jasmine-product::product-price.user_level.all'),
                                     'default'  => __('red-jasmine-product::product-price.user_level.default'),
                                     'vip'      => __('red-jasmine-product::product-price.user_level.vip'),
                                     'gold'     => __('red-jasmine-product::product-price.user_level.gold'),
                                     'platinum' => __('red-jasmine-product::product-price.user_level.platinum'),
                                 ])
                                 ->helperText(__('red-jasmine-product::product-price.helpers.user_level')),

                           ProductCurrencySelect::make('currency')
                                                ->label(__('red-jasmine-product::product-price.fields.currency'))
                                                ->required()
                                                ->live()
                                                ->afterStateUpdated(function (Get $get, Set $set) {
                                                    static::reloadVariants($get, $set);
                                                })
                                                ->default(function (Get $get) {
                                                    $productId = $get('product_id');
                                                    if ($productId) {
                                                        $product = Product::find($productId);
                                                        return $product?->currency?->getCode();
                                                    }
                                                    return null;
                                                }),

                           TextInput::make('quantity')
                                    ->label(__('red-jasmine-product::product-price.fields.quantity'))
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->helperText(__('red-jasmine-product::product-price.helpers.quantity')),
                       ]),

                Section::make(__('red-jasmine-product::product-price.labels.variants_price'))
                       ->description(__('red-jasmine-product::product-price.labels.variants_price_desc'))
                       ->icon('heroicon-o-currency-dollar')
                       ->schema([
                           Repeater::make('variants')
                                   ->label(__('red-jasmine-product::product-price.fields.variants'))
                                   ->schema([
                                       Hidden::make('variant_id'),
                                       Hidden::make('currency'),
                                       TextInput::make('attrs_name')
                                                ->label(__('red-jasmine-product::product-price.fields.variant'))
                                                ->disabled()
                                                ->dehydrated(false),
                                       TextInput::make('price')
                                                ->label(__('red-jasmine-product::product-price.fields.price'))
                                                ->required()
                                                ->numeric()
                                                ->prefix(fn(Get $get
                                                ) => $get('../../currency') ? Currencies::getSymbol($get('../../currency')) : '')
                                                ->step(0.01)
                                                ->minValue(0),
                                       TextInput::make('market_price')
                                                ->label(__('red-jasmine-product::product-price.fields.market_price'))
                                                ->numeric()
                                                ->prefix(fn(Get $get
                                                ) => $get('../../currency') ? Currencies::getSymbol($get('../../currency')) : '')
                                                ->step(0.01)
                                                ->minValue(0),
                                       TextInput::make('cost_price')
                                                ->label(__('red-jasmine-product::product-price.fields.cost_price'))
                                                ->numeric()
                                                ->prefix(fn(Get $get
                                                ) => $get('../../currency') ? Currencies::getSymbol($get('../../currency')) : '')
                                                ->step(0.01)
                                                ->minValue(0),
                                   ])
                                   ->table([
                                       Repeater\TableColumn::make(__('red-jasmine-product::product-price.fields.variant')),

                                       Repeater\TableColumn::make(__('red-jasmine-product::product-price.fields.price'))
                                                           ->markAsRequired(),
                                       Repeater\TableColumn::make(__('red-jasmine-product::product-price.fields.market_price'))
                                       ,
                                       Repeater\TableColumn::make(__('red-jasmine-product::product-price.fields.cost_price'))
                                       ,
                                   ])
                                   ->inlineLabel(false)
                                   ->columnSpan('full')
                                   ->reorderable(false)
                                   ->addable(false)
                                   ->deletable(false)
                                   ->defaultItems(0)
                                   ->visible(fn(Get $get) => !empty($get('product_id'))),
                       ]),
            ])
            ->inlineLabel(true)
            ->columns(1);
    }

    /**
     * 重新加载变体价格数据
     */
    protected static function reloadVariants(Get $get, Set $set) : void
    {
        $productId = $get('product_id');
        if (!$productId) {
            return;
        }

        $product = Product::find($productId);
        if (!$product) {
            return;
        }

        $market    = $get('market') ?? '*';
        $store     = $get('store') ?? '*';
        $userLevel = $get('user_level') ?? '*';
        $currency  = $get('currency');

        // 获取该商品在该维度下的所有变体价格
        $query = ProductVariantPrice::query()
                                    ->where('product_id', $productId)
                                    ->byDimensions($market, $store, $userLevel);

        // 如果已选择货币，则只查询该货币的价格
        if ($currency) {
            $query->where('currency', $currency);
        }

        $allPrices = $query->get()->keyBy('variant_id');

        // 初始化变体数据
        $variants = [];
        foreach ($product->variants as $variant) {
            $existingPrice = $allPrices->get($variant->id);

            $variants[] = [
                'variant_id'   => $variant->id,
                'currency'     => $variant->currency->getCode(),
                'attrs_name'   => $variant->attrs_name,
                'price'        => $existingPrice
                    ? ($existingPrice->price?->getAmount() / 100)
                    : null,
                'market_price' => $existingPrice
                    ? ($existingPrice->market_price?->getAmount() / 100)
                    :null,
                'cost_price'   => $existingPrice
                    ? ($existingPrice->cost_price?->getAmount() / 100)
                    : null,
            ];

        }


        $set('variants', $variants, shouldCallUpdatedHooks: true);
    }
}
