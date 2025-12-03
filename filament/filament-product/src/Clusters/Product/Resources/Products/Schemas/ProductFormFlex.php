<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Schemas;


use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Form\Models\Enums\FieldTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\StrategyTypeEnum;
use RedJasmine\FilamentCore\Forms\Components\SelectTree;
use RedJasmine\FilamentCore\Forms\Components\TranslationTabs;
use RedJasmine\FilamentCore\Resources\Schemas\Operators;
use RedJasmine\FilamentCore\Resources\Schemas\Owner;
use RedJasmine\FilamentProduct\Forms\Components\ProductCurrencySelect;
use RedJasmine\FilamentRegion\Forms\Components\CountrySelect;
use RedJasmine\Product\Domain\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Domain\Product\Data\Product;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use Symfony\Component\Intl\Currencies;
use Throwable;

class ProductFormFlex
{

    public static function configure(Schema $form) : Schema
    {
        $form->schema([
            Flex::make([
                Section::make([
                    ...static::baseInfoFields(),
                    ...static::variantsFields(),
                    ...static::mediaFields(),
                    ...static::shippingFields(),
                    ...static::productAttributesFields(),
                    ...static::afterSalesServices(),
                    ...static::otherFields(),
                    ...static::operateFields(),
                ])->columns(1),

                Section::make()
                       ->schema([

                           Fieldset::make()->schema([
                               TextInput::make('slug')
                                        ->label(__('red-jasmine-product::product.fields.slug'))
                                        ->maxLength(255)
                                        ->placeholder('自动生成或手动输入URL友好标识')
                                        ->helperText('用于生成商品URL，留空将自动从标题生成')
                                        ->prefixIcon('heroicon-o-link')
                                        ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, $get) {
                                            return $rule->where('owner_type', $get('owner_type'))
                                                        ->where('owner_id', $get('owner_id'));
                                        })
                                        ->columnSpanFull(),


                               ToggleButtons::make('status')
                                            ->label(__('red-jasmine-product::product.fields.status'))
                                            ->required()
                                            ->inline()
                                            ->grouped()
                                            ->default(ProductStatusEnum::AVAILABLE)
                                            ->icons(ProductStatusEnum::icons())
                                            ->colors(ProductStatusEnum::colors())
                                            ->options(function ($operation, ?Model $record) {
                                                if ($operation == 'edit') {
                                                    return $record->status->updatingAllowed();
                                                }
                                                if ($operation == 'create') {
                                                    return ProductStatusEnum::creatingAllowed();
                                                }
                                                if ($operation == 'view') {
                                                    return [
                                                        $record->status->value => $record->status->label()
                                                    ];
                                                }
                                                return ProductStatusEnum::options();
                                            })
                                            ->live()
                                            ->helperText('选择商品状态：在售-上架销售；待售-保存草稿')
                                            ->columnSpanFull(),

                           ])->columns(1),
                           Fieldset::make()->columns(1)->schema([
                               SelectTree::make('product_group_id')
                                         ->label(__('red-jasmine-product::product.fields.product_group_id'))
                                         ->relationship(
                                             relationship: 'productGroup',
                                             titleAttribute: 'name',
                                             parentAttribute: 'parent_id',
                                             modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                                 ->where('owner_type', $get('owner_type'))
                                                 ->where('owner_id', $get('owner_id')),
                                             modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                                 ->where('owner_type', $get('owner_type'))
                                                 ->where('owner_id', $get('owner_id')),
                                         )
                                         ->parentNullValue(0)
                                         ->independent(false)
                                         ->storeResults()
                                         ->default(0)
                                         ->defaultZero()
                                         ->helperText('选择商品分组，便于商品管理')
                                         ->searchable(),

                           ]),

                           Fieldset::make()->columns(1)->schema([


                               SelectTree::make('brand_id')
                                         ->label(__('red-jasmine-product::product.fields.brand_id'))
                                         ->withTranslation()
                                         ->relationship('brand', 'name', 'parent_id')
                                         ->parentNullValue(0)
                                         ->default(0)
                                         ->defaultZero()
                                         ->helperText('选择商品品牌')
                                         ->searchable(),
                               TextInput::make('model_code')
                                        ->label(__('red-jasmine-product::product.fields.model_code'))
                                        ->maxLength(60)
                                        ->placeholder('请输入商品型号')
                                        ->helperText('商品型号或款式编码')
                                        ->prefixIcon('heroicon-o-identification'),

                           ]),


                           Fieldset::make()->columns(1)->schema([

                               SelectTree::make('extend_product_groups')
                                         ->label(__('red-jasmine-product::product.fields.extend_groups'))
                                         ->relationship(
                                             relationship: 'extendProductGroups',
                                             titleAttribute: 'name',
                                             parentAttribute: 'parent_id',
                                             modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                                 ->where('owner_type', $get('owner_type'))
                                                 ->where('owner_id', $get('owner_id')),
                                             modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                                 ->where('owner_type', $get('owner_type'))
                                                 ->where('owner_id', $get('owner_id')),
                                         )
                                         ->dehydrated()
                                         ->saveRelationshipsUsing(null)
                                         ->parentNullValue(0)
                                         ->default([])
                                         ->helperText('选择商品的扩展分组，支持多选')
                                         ->searchable(),

                               Select::make('tags')
                                     ->multiple()
                                     ->label(__('red-jasmine-product::product.fields.tags'))
                                     ->relationship(
                                         name: 'tags',
                                         titleAttribute: 'name',
                                         modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                             ->where('owner_type', $get('owner_type'))
                                             ->where('owner_id', $get('owner_id')),
                                     )
                                     ->pivotData([])
                                     ->saveRelationshipsUsing(null)
                                     ->dehydrated()
                                     ->preload()
                                     ->default([])
                                     ->helperText('为商品添加标签，便于分类和搜索')
                                     ->searchable(),

                           ]),

                           TextInput::make('remarks')
                                    ->label(__('red-jasmine-product::product.fields.remarks'))
                                    ->maxLength(255),

                           TextInput::make('sort')
                                    ->label(__('red-jasmine-product::product.fields.sort'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),

                           Operators::make(),


                       ])
                       ->grow(false),


            ])->columnSpanFull(),

        ]);

        return $form;
    }

    /**
     * 配置表单
     */


    protected static function baseInfoFields() : array
    {
        return [
            Owner::make(),
            Section::make(__('red-jasmine-product::product.labels.basic_info'))
                   ->icon('heroicon-o-squares-2x2')
                   ->schema([
                       ToggleButtons::make('product_type')
                                    ->label(__('red-jasmine-product::product.fields.product_type'))
                                    ->required()
                                    ->inline()
                                    ->live()
                                    ->default(ProductTypeEnum::VIRTUAL->value)
                                    ->icons(ProductTypeEnum::icons())
                                    ->useEnum(ProductTypeEnum::class)
                                    ->helperText('选择商品类型：实物商品需要物流配送，虚拟商品无需物流')
                                    ->afterStateUpdated(function (Get $get, Set $set, ProductTypeEnum $state) {
                                        $set('freight_templates', []);
                                        // 设置默认的 发货方式
                                        $set('shipping_types', $state->defaultShippingTypes(), shouldCallUpdatedHooks: true);
                                    })
                                    ->columnSpanFull(),
                       SelectTree::make('category_id')
                                 ->withTranslation()
                                 ->label(__('red-jasmine-product::product.fields.category_id'))
                                 ->relationship('category', 'name', 'parent_id')
                                 ->parentNullValue(0)
                                 ->defaultZero()
                                 ->default(0)
                                 ->helperText('选择商品所属类目')
                                 ->searchable(),
                       TranslationTabs::make('translations')
                                      ->schema([

                                          Fieldset::make()->schema([
                                              TextInput::make('title')
                                                       ->inlineLabel(false)
                                                       ->label(__('red-jasmine-product::product.fields.title'))
                                                       ->required()
                                                       ->maxLength(60)
                                                       ->placeholder('请输入商品标题，建议60字以内')
                                                       ->helperText('商品标题将在商品列表和详情页展示')
                                                       ->prefixIcon('heroicon-o-document-text')
                                                       ->columnSpanFull(),
                                              TextInput::make('slogan')
                                                       ->inlineLabel(false)
                                                       ->label(__('red-jasmine-product::product.fields.slogan'))
                                                       ->maxLength(255)
                                                       ->placeholder('请输入商品卖点，吸引买家购买')
                                                       ->helperText('卖点文案，建议突出商品特色和优势')
                                                       ->prefixIcon('heroicon-o-megaphone')
                                              ,
                                              TextInput::make('tips')
                                                       ->inlineLabel(false)
                                                       ->label(__('red-jasmine-product::product.fields.tips'))
                                                       ->maxLength(255)
                                                       ->placeholder('温馨提示或重要说明')
                                                       ->helperText('显示在商品详情页的提示信息')
                                                       ->prefixIcon('heroicon-o-information-circle')
                                              ,
                                          ])->columns(2),
                                          Section::make('SEO')
                                                 ->schema([

                                                     TextInput::make('meta_title')
                                                              ->inlineLabel(false)
                                                              ->label(__('red-jasmine-product::product.fields.meta_title'))
                                                              ->maxLength(255)
                                                              ->placeholder('商品SEO标题')
                                                              ->helperText('搜索引擎显示的标题，建议60字以内')
                                                              ->prefixIcon('heroicon-o-document-text'),

                                                     TextInput::make('meta_keywords')
                                                              ->inlineLabel(false)
                                                              ->label(__('red-jasmine-product::product.fields.meta_keywords'))
                                                              ->maxLength(255)
                                                              ->placeholder('关键词1, 关键词2, 关键词3')
                                                              ->helperText('用逗号分隔多个关键词，有助于搜索引擎收录')
                                                              ->prefixIcon('heroicon-o-hashtag'),

                                                     Textarea::make('meta_description')
                                                             ->inlineLabel(false)
                                                             ->label(__('red-jasmine-product::product.fields.meta_description'))
                                                             ->maxLength(200)
                                                             ->placeholder('商品简短描述')
                                                             ->helperText('显示在搜索结果中的描述，建议120-160字'),
                                                 ])
                                                 ->collapsed(true)
                                                 ->collapsible(),


                                          RichEditor::make('description')
                                                    ->inlineLabel(false)
                                                    ->label(__('red-jasmine-product::product.fields.description'))
                                                    ->columnSpanFull(),

                                      ])
                                      ->columnSpanFull(),


                   ]),
        ];
    }

    /**
     * 发货字段
     */
    protected static function shippingFields() : array
    {
        return [
            Section::make('发货设置')
                   ->description('配置商品的发货和物流相关信息')
                   ->icon('heroicon-o-truck')
                   ->columns(2)
                   ->schema([
                       TextInput::make('delivery_time')
                                ->label(__('red-jasmine-product::product.fields.delivery_time'))
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->helperText('承诺发货时间（小时），0表示24小时内发货')
                                ->prefixIcon('heroicon-o-clock')
                                ->suffix('小时'),

                       ToggleButtons::make('shipping_types')
                                    ->label(__('red-jasmine-product::product.fields.shipping_types'))
                                    ->inline()
                                    ->multiple()
                                    ->icons(ShippingTypeEnum::icons())
                                    ->default([ShippingTypeEnum::DUMMY])
                                    ->options(fn(Get $get) => collect(ShippingTypeEnum::options())->only(array_map(function ($type) {
                                        return $type->value;
                                    }, ProductTypeEnum::tryFrom($get('product_type')?->value)->shippingTypes()))->toArray())
                                    ->required()
                                    ->live()
                                    ->helperText('选择支持的发货方式')
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {

                                        $freightTemplates = $get('freight_templates');
                                        $freightTemplates = collect($freightTemplates)->keyBy('shipping_type');
                                        foreach ($state as $shippingTypeString) {
                                            $shippingType = ShippingTypeEnum::from($shippingTypeString);
                                            if (!$shippingType->requiresFreight()) {
                                                $freightTemplates->pull($shippingTypeString);
                                            } else {
                                                if (!isset($freightTemplates[$shippingTypeString])) {
                                                    $freightTemplates->put($shippingTypeString,
                                                        [
                                                            'shipping_type'       => $shippingTypeString,
                                                            'freight_payer'       => FreightPayerEnum::SELLER,
                                                            'freight_template_id' => null,
                                                        ]);

                                                }
                                            }
                                        }

                                        foreach ($freightTemplates as $key => $freightTemplate) {
                                            if (!in_array($key, $state)) {
                                                $freightTemplates->pull($key);
                                            }
                                        }
                                        $set('freight_templates', $freightTemplates->values()->toArray());


                                    })
                                    ->columnSpanFull(),

                       Repeater::make('freight_templates')
                               ->label(__('red-jasmine-product::product.fields.freight_templates'))
                               ->addable(false)
                               ->deletable(false)
                               ->inlineLabel(false)
                               ->reorderable(false)
                               ->default([])
                               ->columnSpanFull()
                               ->table([
                                   Repeater\TableColumn::make(__('red-jasmine-product::product.fields.shipping_type')),
                                   Repeater\TableColumn::make(__('red-jasmine-product::product.fields.freight_payer')),
                                   Repeater\TableColumn::make(__('red-jasmine-product::product.fields.freight_template_id')),
                               ])
                               ->schema([
                                   Select::make('shipping_type')
                                         ->useEnum(ShippingTypeEnum::class)
                                         ->disabled() // 存在BUG TODO
                                         ->visible(true)
                                         ->distinct(),
                                   Select::make('freight_payer')
                                         ->label(__('red-jasmine-product::product.fields.freight_payer'))
                                         ->required()
                                         ->default(FreightPayerEnum::SELLER)
                                         ->useEnum(FreightPayerEnum::class)
                                         ->live()
                                         ->columnSpanFull(),


                                   Select::make('freight_template_id')
                                         ->label(__('red-jasmine-product::product.fields.freight_template_id'))
                                         ->relationship('freightTemplate', 'name', modifyQueryUsing: function ($query, Get $get) {
                                             return $query->where('owner_type', $get('owner_type'))->where('owner_id', $get('owner_id'));
                                         })
                                         ->formatStateUsing(fn($state) => (string) $state)
                                         ->required(fn(Get $get, $state) => $get('freight_payer') === FreightPayerEnum::BUYER)
                                         ->visible(fn(Get $get, $state) => $get('freight_payer') === FreightPayerEnum::BUYER)
                                         ->searchable()
                                         ->preload(),

                               ]),


                   ]),
        ];
    }

    /**
     * 规格配置
     */
    protected static function variantsFields() : array
    {
        return [
            Section::make('商品规格')
                   ->description('设置商品的多规格配置')
                   ->icon('heroicon-o-squares-2x2')
                   ->schema([
                       ProductCurrencySelect::make('currency')
                                            ->live()
                                            ->label(__('red-jasmine-product::product.fields.currency')),
                       Toggle::make('has_variants')
                             ->label(__('red-jasmine-product::product.fields.has_variants'))
                             ->required()
                             ->live()
                             ->partiallyRenderComponentsAfterStateUpdated(['sale_attrs', 'variants'])
                             ->inline()
                             ->default(false)
                             ->onIcon('heroicon-o-check-circle')
                             ->offIcon('heroicon-o-x-circle')
                             ->onColor('success')
                             ->offColor('gray')
                             ->helperText('开启后可以设置商品的多个规格（如颜色、尺码等）')
                             ->afterStateUpdated(function ($state, $old, Get $get, Set $set) {
                                 if ($state === false) {
                                     $set('sale_attrs', []);
                                     $set('variantsFields', [static::defaultVariant()]);
                                 } else {
                                     $set('variantsFields', []);
                                     $set('sale_attrs', []);
                                 }
                             })
                       ,


                       SaleAttrsRepeater::make('sale_attrs')
                                        ->partiallyRenderComponentsAfterStateUpdated(['variants'])
                                        ->visible(fn(Get $get) => $get('has_variants'))
                                        ->live()
                                        ->afterStateUpdated(function ($state, $old, Get $get, Set $set) {
                                            try {
                                                $saleAttrs = array_values($get('sale_attrs') ?? []);

                                                $saleAttrs = array_map(function ($item) {
                                                    $item['values'] = array_values($item['values'] ?? []);
                                                    return $item;
                                                }, $saleAttrs);


                                                $oldSku = $get('variantsFields') ?? [];
                                                if ($oldSku === null) {
                                                    $oldSku = [];
                                                }
                                                $service   = app(ProductAttributeValidateService::class);
                                                $crossJoin = $service->crossJoin($saleAttrs);

                                                $oldSku = collect($oldSku)->keyBy('attrs_sequence');

                                                $variants       = [];
                                                $defaultVariant = static::defaultVariant();
                                                foreach ($crossJoin as $properties => $propertyName) {
                                                    $sku                   = $oldSku[$properties] ?? $defaultVariant;
                                                    $sku['attrs_sequence'] = $properties;
                                                    $sku['attrs_name']     = $propertyName;
                                                    $variants[]            = $sku;
                                                }

                                                $set('variantsFields', $variants, shouldCallUpdatedHooks: true);
                                            } catch (Throwable $throwable) {
                                                $set('variantsFields', [], shouldCallUpdatedHooks: true);
                                            }
                                        }),

                       VariantsRepeater::make('variants')->default([
                           static::defaultVariant(),
                       ])
                                       ->deletable(false),


                   ]),
        ];
    }

    public static function defaultVariant() : array
    {
        return [
            'attrs_sequence' => '',
            'attrs_name'     => '',
            'image'          => null,
            'price'          => null,
            'market_price'   => null,
            'cost_price'     => null,
            'stocks'         => [
                [
                    'warehouse_id' => 0,
                    'stock'        => 0,
                    'safety_stock' => 0,
                    'is_active'    => 1,
                    'priority'     => 0,
                ]
            ],

            'status' => ProductStatusEnum::AVAILABLE->value,
        ];
    }


    /**
     * 商品属性字段
     */
    protected static function productAttributesFields() : array
    {
        return [


            Section::make(__('red-jasmine-product::product.fields.attrs'))
                   ->description('设置商品的基础属性和自定义属性')
                   ->icon('heroicon-o-list-bullet')
                   ->columns(2)
                   ->schema([
                       BasicAttrsRepeater::make('basic_attrs')
                                         ->label(__('red-jasmine-product::product.fields.basic_attrs'))
                                         ->inlineLabel(false)
                                         ->columnSpan(1)

                       ,
                       CustomizeAttrsRepeater::make('customize_attrs')
                                             ->label(__('red-jasmine-product::product.fields.customize_attrs'))
                                             ->inlineLabel(false)
                                             ->inlineLabel(false)
                                             ->columnSpan(1),
                       CountrySelect::make('origin_country')
                                    ->label(__('red-jasmine-product::product.fields.origin_country')),

                   ]),
        ];
    }

    /**
     * 销售信息字段
     */
    protected static function saleInfoFields() : array
    {
        return [
            Section::make('销售设置')
                   ->description('配置商品的销售相关属性')
                   ->icon('heroicon-o-shopping-cart')
                   ->columns(2)
                   ->schema([
                       ToggleButtons::make('is_pre_sale')
                                    ->label(__('red-jasmine-product::product.fields.is_pre_sale'))
                                    ->required()
                                    ->inline()
                                    ->boolean()
                                    ->default(false)
                                    ->icons([
                                        true  => 'heroicon-o-clock',
                                        false => 'heroicon-o-check-circle',
                                    ])
                                    ->colors([
                                        true  => 'warning',
                                        false => 'success',
                                    ])
                                    ->helperText('预售商品需要等待一段时间后发货'),

                       ToggleButtons::make('is_brand_new')
                                    ->label(__('red-jasmine-product::product.fields.is_brand_new'))
                                    ->required()
                                    ->inline()
                                    ->boolean()
                                    ->default(true)
                                    ->icons([
                                        true  => 'heroicon-o-sparkles',
                                        false => 'heroicon-o-archive-box',
                                    ])
                                    ->colors([
                                        true  => 'success',
                                        false => 'gray',
                                    ])
                                    ->helperText('标识商品是否为全新商品'),
                   ]),
        ];
    }

    /**
     * 售后服务字段
     */
    protected static function afterSalesServices() : array
    {

        return [
            Section::make('售后服务')
                   ->icon('heroicon-o-shopping-cart')
                   ->columns(2)
                   ->schema([
                       CheckboxList::make('services')
                                   ->label(__('red-jasmine-product::product.fields.services'))
                                   ->relationship(
                                       name: 'services',
                                       titleAttribute: 'name',
                                       modifyQueryUsing: fn(Builder $query) => $query->enable()
                                   )
                                   ->columns(6)
                                   ->columnSpanFull()
                                   ->dehydrated()
                                   ->saveRelationshipsUsing(null)
                                   ->dehydrated()
                                   ->default([]),

                       Repeater::make('after_sales_services')
                               ->label(__('red-jasmine-product::product.fields.after_sales_services'))
                               // ->table([
                               //     Repeater\TableColumn::make(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.refund_type')),
                               //     Repeater\TableColumn::make(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.allow_stage')),
                               //     Repeater\TableColumn::make(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit')),
                               // ])
                               ->columnSpan(1)
                               ->inlineLabel(false)
                               ->reorderable(false)
                               ->addable(false)
                               ->deletable(false)
                               ->default(collect(Product::defaultAfterSalesServices())->toArray())
                               ->schema([
                                   Select::make('refund_type')
                                         ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.refund_type'))
                                         ->selectablePlaceholder(false)
                                         ->disabled()
                                         ->dehydrated()
                                         ->distinct()
                                         ->fixIndistinctState()
                                         ->options(RefundTypeEnum::options()),

                                   ToggleButtons::make('is_allowed')
                                                ->label(__('red-jasmine-product::product.fields.is_alone_order'))
                                                ->required()
                                                ->boolean()
                                                ->inline()
                                                ->default(false)
                                                ->icons([
                                                    true  => 'heroicon-o-shopping-cart',
                                                    false => 'heroicon-o-minus-circle',
                                                ])
                                                ->colors([
                                                    true  => 'warning',
                                                    false => 'gray',
                                                ])
                                                ->helperText('开启后，此商品需单独下单，不能与其他商品一起购买')
                                   ,

                                   Repeater::make('strategies')
                                       ->reorderable(false)
                                           ->schema([

                                               Select::make('type')
                                                     ->required()
                                                     ->label('策略类型')
                                                     ->selectablePlaceholder(false)
                                                     ->default(StrategyTypeEnum::ALLOWED->value)
                                                     ->options(StrategyTypeEnum::options()),
                                               Select::make('start')
                                                     ->required()
                                                     ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.allow_stage'))
                                                     ->selectablePlaceholder(false)
                                                     ->default(OrderAfterSaleServiceAllowStageEnum::NEVER->value)
                                                     ->options(OrderAfterSaleServiceAllowStageEnum::options()),
                                               Select::make('end')
                                                     ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.allow_stage'))
                                                     ->selectablePlaceholder(false)
                                                     ->default(OrderAfterSaleServiceAllowStageEnum::NEVER->value)
                                                     ->options(OrderAfterSaleServiceAllowStageEnum::options()),


                                               FusedGroup::make([
                                                   TextInput::make('time_limit')
                                                            ->hiddenLabel()
                                                            ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit'))
                                                   ,
                                                   Select::make('time_limit_unit')
                                                         ->hiddenLabel()
                                                         ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit_unit'))
                                                         ->nullable()
                                                         ->default(OrderAfterSaleServiceTimeUnit::Day->value)
                                                         ->options(OrderAfterSaleServiceTimeUnit::options()),
                                               ])
                                                         ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit'))
                                                         ->columns(2)
                                           ]),





                               ])
                   ])
        ];


    }

    /**
     * 商品描述字段
     */
    protected static function mediaFields() : array
    {
        return [

            Section::make('媒体')
                   ->description('上传商品主图、轮播图和视频')
                   ->icon('heroicon-o-photo')
                   ->columns(1)
                   ->schema([
                       FileUpload::make('media')
                                 ->inlineLabel(false)
                                 ->label(__('red-jasmine-product::product.fields.images'))
                                 ->saveRelationshipsUsing(null)
                                 ->afterStateHydrated(function (
                                     FileUpload $component,
                                     $state,
                                     ?\RedJasmine\Product\Domain\Product\Models\Product $record
                                 ) {
                                     $component->state($record ? $record->media->sortBy('position')->pluck('path')->toArray() : []);
                                 })
                                 ->dehydrateStateUsing(function ($state) {

                                     $media = [];
                                     foreach ($state as $index => $item) {
                                         $media[] = [
                                             'path'       => $item,
                                             'position'   => $index,
                                             'is_primary' => $index === 0,
                                             'is_enabled' => true,
                                         ];
                                     }
                                     return $media;
                                 })
                                 ->directory('products')
                                 ->multiple()
                                 ->maxFiles(30)
                                 ->reorderable()
                                 ->imageEditor()
                                 ->maxSize(2048)
                                 ->panelLayout('grid')
                                 ->helperText('商品轮播图，第一张为商品主图')
                                 ->appendFiles()
                                 ->columnSpanFull(),

                   ]),

        ];
    }

    /**
     * 运营字段
     */
    protected static function operateFields() : array
    {
        return [
            Section::make('运营标签')
                   ->description('设置商品的运营标签，用于商品推荐和营销')
                   ->icon('heroicon-o-flag')
                   ->columns(2)
                   ->schema([
                       TextInput::make('gift_point')
                                ->label(__('red-jasmine-product::product.fields.gift_point'))
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->helperText('购买商品赠送的积分')
                                ->prefixIcon('heroicon-o-gift')
                                ->suffix('积分'),

                       TextInput::make('vip')
                                ->label(__('red-jasmine-product::product.fields.vip'))
                                ->required()
                                ->integer()
                                ->minValue(0)
                                ->maxValue(10)
                                ->default(0)
                                ->helperText('购买需要的VIP等级，0表示无需VIP')
                                ->suffix('级'),
                   ]),

            Section::make('购买限制')
                   ->description('设置商品的购买数量限制')
                   ->icon('heroicon-o-shield-check')
                   ->columns(3)
                   ->schema([
                       ToggleButtons::make('is_alone_order')
                                    ->label(__('red-jasmine-product::product.fields.is_alone_order'))
                                    ->required()
                                    ->boolean()
                                    ->inline()
                                    ->default(false)
                                    ->icons([
                                        true  => 'heroicon-o-shopping-cart',
                                        false => 'heroicon-o-minus-circle',
                                    ])
                                    ->colors([
                                        true  => 'warning',
                                        false => 'gray',
                                    ])
                                    ->helperText('开启后，此商品需单独下单，不能与其他商品一起购买')
                                    ->columnSpanFull(),

                       TextInput::make('min_limit')
                                ->label(__('red-jasmine-product::product.fields.min_limit'))
                                ->required()
                                ->integer()
                                ->minValue(1)
                                ->default(1)
                                ->helperText('最少购买数量')
                                ->suffix('件'),

                       TextInput::make('max_limit')
                                ->label(__('red-jasmine-product::product.fields.max_limit'))
                                ->required()
                                ->integer()
                                ->minValue(0)
                                ->default(0)
                                ->helperText('最多购买数量，0表示不限制')
                                ->suffix('件'),

                       TextInput::make('step_limit')
                                ->label(__('red-jasmine-product::product.fields.step_limit'))
                                ->required()
                                ->integer()
                                ->minValue(1)
                                ->default(1)
                                ->helperText('购买数量必须是此值的倍数')
                                ->suffix('件'),
                   ]),
        ];
    }

    /**
     * 其他字段
     */
    protected static function otherFields() : array
    {
        return [
            Section::make('其他')->schema([
                Radio::make('is_customized')
                     ->label(__('red-jasmine-product::product.fields.is_customized'))
                     ->required()
                     ->boolean()
                     ->inline()
                     ->default(0),

                Fieldset::make('表单')
                        ->columns(1)
                        ->inlineLabel()
                        ->schema([
                            Repeater::make('form.schemas')
                                    ->label(__('red-jasmine-product::product.fields.form'))
                                    ->default(null)
                                    ->schema([
                                        TextInput::make('label')->inlineLabel()->required()->maxLength(32),
                                        TextInput::make('name')->inlineLabel()->required()->maxLength(32),
                                        Select::make('type')
                                              ->inlineLabel()
                                              ->required()
                                              ->default(FieldTypeEnum::TEXT)
                                              ->useEnum(FieldTypeEnum::class),
                                        Checkbox::make('is_required')->inlineLabel(),
                                        TextInput::make('default')->inlineLabel(),
                                        TextInput::make('placeholder')->inlineLabel(),
                                        TextInput::make('hint')->inlineLabel(),

                                        Repeater::make('options')->default(null)->schema([
                                            TextInput::make('label')
                                                     ->hiddenLabel()
                                                     ->required()
                                                     ->maxLength(32),
                                            TextInput::make('value')
                                                     ->hiddenLabel()
                                                     ->required()
                                                     ->maxLength(128),
                                        ])
                                                ->columns(2)
                                                ->grid(5)
                                                ->columnSpan('full'),
                                    ])
                                    ->inlineLabel(false)
                                    ->hiddenLabel()
                                    ->columns(7)
                                    ->columnSpan('full')
                        ]),


            ]),

        ];
    }

}
