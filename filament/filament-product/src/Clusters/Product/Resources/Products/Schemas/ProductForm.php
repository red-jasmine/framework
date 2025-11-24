<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Schemas;

use CodeWithDennis\FilamentSelectTree\SelectTree;
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
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Quantity\Components\Quantity;
use RedJasmine\Ecommerce\Domain\Form\Models\Enums\FieldTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderQuantityLimitTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
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

class ProductForm
{
    /**
     * 配置表单
     */
    public static function configure(Schema $form) : Schema
    {
        $schema = [
            Tab::make('basic_info')->label(__('red-jasmine-product::product.labels.basic_info'))->columns(1)->schema(static::basicInfoFields()),
            Tab::make('product_attributes')->label(__('red-jasmine-product::product.labels.product_attributes'))->columns(1)->schema(static::productAttributesFields()),
            Tab::make('sale_info')->label(__('red-jasmine-product::product.labels.sale_info'))->columns(1)->schema(static::saleInfoFields()),
            Tab::make('after_sales_services')->label(__('red-jasmine-product::product.labels.after_sales_services'))->columns(1)->schema(static::afterSalesServices()),
            Tab::make('description')->label(__('red-jasmine-product::product.labels.description'))->columns(1)->schema(static::descriptionFields()),
            Tab::make('operate')->label(__('red-jasmine-product::product.labels.operate'))->columns(1)->schema(static::operateFields()),
            Tab::make('seo')->label(__('red-jasmine-product::product.labels.seo'))->columns(1)->schema(static::seoFields()),
            //Tab::make('shipping')->label(__('red-jasmine-product::product.labels.shipping'))->columns(1)->schema(static::shippingFields()),
            Tab::make('other')->label(__('red-jasmine-product::product.labels.other'))->columns(1)->schema(static::otherFields()),
        ];

        return $form
            ->components([
                Tabs::make(__('red-jasmine-product::product.labels.product'))
                    ->tabs($schema)
                    ->persistTabInQueryString(),
            ])
            ->inlineLabel(true)
            ->columns(1);
    }

    /**
     * 基础信息字段
     */
    protected static function basicInfoFields() : array
    {
        return [
            Owner::make(),

            Section::make('商品基础信息')
                   ->description('设置商品的基本信息和分类')
                   ->icon('heroicon-o-information-circle')
                   ->columns(2)
                   ->schema([
                       ToggleButtons::make('product_type')
                                    ->label(__('red-jasmine-product::product.fields.product_type'))
                                    ->required()
                                    ->inline()
                                    ->live()
                                    ->default(ProductTypeEnum::PHYSICAL->value)
                                    ->icons(ProductTypeEnum::icons())
                                    ->useEnum(ProductTypeEnum::class)
                                    ->helperText('选择商品类型：实物商品需要物流配送，虚拟商品无需物流')
                                    ->afterStateUpdated(function (Get $get, Set $set, ProductTypeEnum $state) {
                                        $set('freight_templates', []);
                                        // 设置默认的 发货方式
                                        $set('shipping_types', $state->defaultShippingTypes(), shouldCallUpdatedHooks: true);
                                    })
                                    ->columnSpanFull(),

                       TextInput::make('title')
                                ->label(__('red-jasmine-product::product.fields.title'))
                                ->required()
                                ->maxLength(60)
                                ->placeholder('请输入商品标题，建议60字以内')
                                ->helperText('商品标题将在商品列表和详情页展示')
                                ->prefixIcon('heroicon-o-document-text')
                                ->columnSpanFull(),

                       TextInput::make('slogan')
                                ->label(__('red-jasmine-product::product.fields.slogan'))
                                ->maxLength(255)
                                ->placeholder('请输入商品卖点，吸引买家购买')
                                ->helperText('卖点文案，建议突出商品特色和优势')
                                ->prefixIcon('heroicon-o-megaphone')
                                ->columnSpanFull(),
                   ]),

            Section::make('分类与品牌')
                   ->description('设置商品的分类、品牌和分组')
                   ->icon('heroicon-o-tag')
                   ->columns(2)
                   ->schema([
                       SelectTree::make('category_id')
                                 ->label(__('red-jasmine-product::product.fields.category_id'))
                                 ->relationship('category', 'name', 'parent_id')
                                 ->parentNullValue(0)
                                 ->defaultZero()
                                 ->default(0)
                                 ->helperText('选择商品所属类目')
                                 ->searchable(),

                       SelectTree::make('brand_id')
                                 ->label(__('red-jasmine-product::product.fields.brand_id'))
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
            ...static::shippingFields(),

            ...static::specifications(),
            ...static::publishFields(),
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
                                    ->options(fn(Get $get) => collect(ShippingTypeEnum::options())->only(array_map(function ($type) {
                                            return $type->value;
                                        }, ProductTypeEnum::tryFrom($get('product_type')?->value)->shippingTypes())
                                    )->toArray()
                                    )
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
                                       // ->disabled( ) // 存在BUG TODO
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
    protected static function specifications() : array
    {
        return [
            Section::make('商品规格')
                   ->description('设置商品的多规格配置')
                   ->icon('heroicon-o-squares-2x2')
                   ->schema([
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
                                     $set('variants', [static::defaultVariant()]);
                                 } else {
                                     $set('variants', []);
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


                                                $oldSku = $get('variants') ?? [];
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

                                                $set('variants', $variants, shouldCallUpdatedHooks: true);
                                            } catch (Throwable $throwable) {
                                                $set('variants', [], shouldCallUpdatedHooks: true);
                                            }
                                        }),

                       ProductCurrencySelect::make('currency')
                                            ->live()
                                            ->label(__('red-jasmine-product::product.fields.currency')),

                       static::variants()
                             ->default([
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
                ]
            ],

            'status' => ProductStatusEnum::AVAILABLE->value,
        ];
    }

    /**
     * SKU 变体
     */
    protected static function variants() : Repeater
    {
        return VariantsRepeater::make('variants');

    }

    /**
     * 发布状态字段
     */
    protected static function publishFields() : array
    {
        return [
            Section::make('发布状态')
                   ->description('设置商品的发布状态')
                   ->icon('heroicon-o-rocket-launch')
                   ->schema([
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
                                        return ProductStatusEnum::options();
                                    })
                                    ->live()
                                    ->helperText('选择商品状态：在售-上架销售；待售-保存草稿')
                                    ->columnSpanFull(),
                   ]),
        ];
    }

    /**
     * 商品属性字段
     */
    protected static function productAttributesFields() : array
    {
        return [
            Section::make('商品分类')
                   ->description('设置商品的扩展分组和标签')
                   ->icon('heroicon-o-folder-open')
                   ->columns(1)
                   ->schema([
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
                               ->table([
                                   Repeater\TableColumn::make(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.refund_type')),
                                   Repeater\TableColumn::make(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.allow_stage')),
                                   Repeater\TableColumn::make(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit')),
                               ])
                               ->columnSpanFull()
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
                                   Select::make('allow_stage')
                                         ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.allow_stage'))
                                         ->selectablePlaceholder(false)
                                         ->live()
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
                                             ->default(OrderAfterSaleServiceTimeUnit::Hour->value)
                                             ->options(OrderAfterSaleServiceTimeUnit::options()),
                                   ])
                                             ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit'))
                                             ->columns(2)
                                             ->visible(fn(Get $get
                                             ) => $get('allow_stage') !== OrderAfterSaleServiceAllowStageEnum::NEVER->value),

                               ])
                   ])
        ];


    }

    /**
     * 商品描述字段
     */
    protected static function descriptionFields() : array
    {
        return [
            Section::make('商品图片')
                   ->description('上传商品主图、轮播图和视频')
                   ->icon('heroicon-o-photo')
                   ->columns(1)
                   ->schema([
                       FileUpload::make('image')
                                 ->label(__('red-jasmine-product::product.fields.image'))
                                 ->image()
                                 ->directory('products')
                                 ->fetchFileInformation(false)
                                 ->imageEditor()
                                 ->imageEditorAspectRatios([
                                     '1:1',
                                     '4:3',
                                     '16:9',
                                 ])
                                 ->maxSize(2048)
                                 ->helperText('建议尺寸：800x800像素，支持JPG、PNG格式，大小不超过2MB')
                                 ->columnSpanFull(),

                       FileUpload::make('images')
                                 ->label(__('red-jasmine-product::product.fields.images'))
                                 ->image()
                                 ->multiple()
                                 ->maxFiles(10)
                                 ->reorderable()
                                 ->imageEditor()
                                 ->maxSize(2048)
                                 ->helperText('商品轮播图，最多上传10张')
                                 ->columnSpanFull(),

                       FileUpload::make('videos')
                                 ->label(__('red-jasmine-product::product.fields.videos'))
                                 ->acceptedFileTypes(['video/mp4', 'video/mpeg', 'video/quicktime'])
                                 ->multiple()
                                 ->maxFiles(3)
                                 ->maxSize(20480)
                                 ->helperText('商品视频，最多上传3个，单个视频不超过20MB')
                                 ->columnSpanFull(),
                   ]),

            Section::make(__('red-jasmine-product::product.fields.detail'))
                   ->description('编写商品的详细描述')
                   ->icon('heroicon-o-document-text')
                   ->schema([
                       RichEditor::make('detail')
                                 ->inlineLabel(false)
                                 ->hiddenLabel()
                                 ->label(__('red-jasmine-product::product.fields.detail'))
                                 ->toolbarButtons([
                                     'bold',
                                     'italic',
                                     'underline',
                                     'strike',
                                     'h2',
                                     'h3',
                                     'bulletList',
                                     'orderedList',
                                     'link',
                                     'blockquote',
                                     'codeBlock',
                                 ])
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
                   ->columns(4)
                   ->schema([
                       ToggleButtons::make('is_hot')
                                    ->label(__('red-jasmine-product::product.fields.is_hot'))
                                    ->required()
                                    ->boolean()
                                    ->inline()
                                    ->default(0)
                                    ->icons([
                                        true  => 'heroicon-o-fire',
                                        false => 'heroicon-o-minus-circle',
                                    ])
                                    ->colors([
                                        true  => 'danger',
                                        false => 'gray',
                                    ]),

                       ToggleButtons::make('is_new')
                                    ->label(__('red-jasmine-product::product.fields.is_new'))
                                    ->required()
                                    ->boolean()
                                    ->inline()
                                    ->default(0)
                                    ->icons([
                                        true  => 'heroicon-o-sparkles',
                                        false => 'heroicon-o-minus-circle',
                                    ])
                                    ->colors([
                                        true  => 'success',
                                        false => 'gray',
                                    ]),

                       ToggleButtons::make('is_best')
                                    ->label(__('red-jasmine-product::product.fields.is_best'))
                                    ->required()
                                    ->boolean()
                                    ->inline()
                                    ->default(0)
                                    ->icons([
                                        true  => 'heroicon-o-star',
                                        false => 'heroicon-o-minus-circle',
                                    ])
                                    ->colors([
                                        true  => 'warning',
                                        false => 'gray',
                                    ]),

                       ToggleButtons::make('is_benefit')
                                    ->label(__('red-jasmine-product::product.fields.is_benefit'))
                                    ->required()
                                    ->boolean()
                                    ->inline()
                                    ->default(0)
                                    ->icons([
                                        true  => 'heroicon-o-gift',
                                        false => 'heroicon-o-minus-circle',
                                    ])
                                    ->colors([
                                        true  => 'info',
                                        false => 'gray',
                                    ]),

                       TextInput::make('tips')
                                ->label(__('red-jasmine-product::product.fields.tips'))
                                ->maxLength(255)
                                ->placeholder('温馨提示或重要说明')
                                ->helperText('显示在商品详情页的提示信息')
                                ->prefixIcon('heroicon-o-information-circle')
                                ->columnSpanFull(),

                       TextInput::make('gift_point')
                                ->label(__('red-jasmine-product::product.fields.gift_point'))
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->helperText('购买商品赠送的积分')
                                ->prefixIcon('heroicon-o-gift')
                                ->suffix('积分'),

                       Quantity::make('vip')
                               ->label(__('red-jasmine-product::product.fields.vip'))
                               ->required()
                               ->numeric()
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

                       Quantity::make('min_limit')
                               ->label(__('red-jasmine-product::product.fields.min_limit'))
                               ->required()
                               ->numeric()
                               ->minValue(1)
                               ->default(1)
                               ->helperText('最少购买数量')
                               ->suffix('件'),

                       Quantity::make('max_limit')
                               ->label(__('red-jasmine-product::product.fields.max_limit'))
                               ->required()
                               ->numeric()
                               ->minValue(0)
                               ->default(0)
                               ->helperText('最多购买数量，0表示不限制')
                               ->suffix('件'),

                       Quantity::make('step_limit')
                               ->label(__('red-jasmine-product::product.fields.step_limit'))
                               ->required()
                               ->numeric()
                               ->minValue(1)
                               ->default(1)
                               ->helperText('购买数量必须是此值的倍数')
                               ->suffix('件'),

                       ToggleButtons::make('order_quantity_limit_type')
                                    ->label(__('red-jasmine-product::product.fields.order_quantity_limit_type'))
                                    ->required()
                                    ->live()
                                    ->grouped()
                                    ->useEnum(OrderQuantityLimitTypeEnum::class)
                                    ->default(OrderQuantityLimitTypeEnum::UNLIMITED->value)
                                    ->columnSpanFull(),

                       Quantity::make('order_quantity_limit_num')
                               ->label(__('red-jasmine-product::product.fields.order_quantity_limit_num'))
                               ->numeric()
                               ->minValue(1)
                               ->default(1)
                               ->suffix('件')
                               ->helperText('单次下单限购数量')
                               ->required(fn(Get $get
                               ) => $get('order_quantity_limit_type') !== OrderQuantityLimitTypeEnum::UNLIMITED->value)
                               ->hidden(fn(Get $get) => $get('order_quantity_limit_type') === OrderQuantityLimitTypeEnum::UNLIMITED->value),
                   ]),
        ];
    }

    /**
     * SEO 字段
     */
    protected static function seoFields() : array
    {
        return [
            Section::make('搜索引擎优化')
                   ->description('优化商品在搜索引擎中的展示效果')
                   ->icon('heroicon-o-magnifying-glass')
                   ->columns(1)
                   ->schema([
                       TextInput::make('meta_title')
                                ->label(__('red-jasmine-product::product.fields.meta_title'))
                                ->maxLength(255)
                                ->placeholder('商品SEO标题')
                                ->helperText('搜索引擎显示的标题，建议60字以内')
                                ->prefixIcon('heroicon-o-document-text'),

                       TextInput::make('meta_keywords')
                                ->label(__('red-jasmine-product::product.fields.meta_keywords'))
                                ->maxLength(255)
                                ->placeholder('关键词1, 关键词2, 关键词3')
                                ->helperText('用逗号分隔多个关键词，有助于搜索引擎收录')
                                ->prefixIcon('heroicon-o-hashtag'),

                       Textarea::make('meta_description')
                               ->label(__('red-jasmine-product::product.fields.meta_description'))
                               ->maxLength(200)
                               ->placeholder('商品简短描述')
                               ->helperText('显示在搜索结果中的描述，建议120-160字')
                       ,
                   ]),
        ];
    }

    /**
     * 其他字段
     */
    protected static function otherFields() : array
    {
        return [
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
        ];
    }

    protected static function sizes()
    {
        return Repeater::make('variants')
                       ->relationship('variants')
                       ->dehydrated()
                       ->saveRelationshipsUsing(null)
                       ->label(__('red-jasmine-product::product.fields.variants'))
                       ->table([
                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.attrs_name')),
                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.sku')),

                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.weight')),
                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.weight_unit')),
                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.length')),
                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.width')),
                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.height')),
                           Repeater\TableColumn::make(__('red-jasmine-product::product.fields.dimension_unit')),

                       ])
                       ->schema([
                           Hidden::make('attrs_sequence'),

                           TextInput::make('attrs_name')->readOnly(),
                           TextInput::make('sku')->readOnly(),
                           TextInput::make('weight')->suffix('KG'),
                           TextInput::make('weight_unit'),
                           TextInput::make('length'),
                           TextInput::make('width'),
                           TextInput::make('height'),
                           TextInput::make('dimension_unit'),

                           TextInput::make('price')->required()
                                    ->prefix(fn(Get $get) => $get('../../currency') ? Currencies::getSymbol($get('../../currency'),
                                        app()->getLocale()) : null)
                                    ->formatStateUsing(fn($state) => $state['formatted'] ?? null)->hidden(),
                           TextInput::make('stock')->minValue(0)->integer()->required(),
                           TextInput::make('market_price')
                                    ->hidden()
                                    ->prefix(fn(Get $get) => $get('../../currency') ? Currencies::getSymbol($get('../../currency'),
                                        app()->getLocale()) : null)
                                    ->formatStateUsing(fn($state) => $state['formatted'] ?? null),
                           TextInput::make('cost_price')
                                    ->hidden()
                                    ->prefix(fn(Get $get) => $get('../../currency') ? Currencies::getSymbol($get('../../currency'),
                                        app()->getLocale()) : null)
                                    ->formatStateUsing(fn($state) => $state['formatted'] ?? null),
                       ])
                       ->inlineLabel(false)
                       ->columnSpan('full')
                       ->reorderable(false)
                       ->addable(false);
    }

}
