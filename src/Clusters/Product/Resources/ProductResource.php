<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductResource\RelationManagers;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderQuantityLimitTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\CreateProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\EditProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\ListProducts;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\ViewProduct;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductDeleteCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Property\Services\PropertyValidateService;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Throwable;

class ProductResource extends Resource
{


    use ResourcePageHelper;

    protected static ?string $commandService = ProductCommandService::class;
    protected static ?string $queryService   = ProductQueryService::class;
    protected static ?string $createCommand  = ProductCreateCommand::class;
    protected static ?string $updateCommand  = ProductUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductDeleteCommand::class;


    protected static ?string $cluster = \RedJasmine\FilamentProduct\Clusters\Product::class;

    protected static ?string $model = Product::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static bool $onlyOwner = true;


    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = [ 'skus', 'info' ];
        return $findQuery;
    }


    public static function callResolveRecord(Model $model) : Model
    {

        foreach ($model->info->getAttributes() as $key => $value) {
            $model->setAttribute($key, $model->info->{$key});
        }
        $model->setAttribute('skus', $model->skus->toArray());
        return $model;
    }

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product.labels.product');
    }

    public static function form(Form $form) : Form
    {

        $schema = [
            Forms\Components\Fieldset::make('basic_info')->label(__('red-jasmine-product::product.labels.basic_info'))->columns(1)->inlineLabel()->schema(static::basicInfoFields()),
            Forms\Components\Fieldset::make('product_attributes')->label(__('red-jasmine-product::product.labels.product_attributes'))->columns(1)->inlineLabel()->schema(static::productAttributesFields()),
            Forms\Components\Fieldset::make('specifications')->label(__('red-jasmine-product::product.labels.specifications'))->columns(1)->inlineLabel()->schema(static::specifications()),
            Forms\Components\Fieldset::make('sale_info')->label(__('red-jasmine-product::product.labels.sale_info'))->columns(1)->inlineLabel()->schema(static::saleInfoFields()),
            Forms\Components\Fieldset::make('description')->label(__('red-jasmine-product::product.labels.description'))->columns(1)->inlineLabel()->schema(static::descriptionFields()),
            Forms\Components\Fieldset::make('operate')->label(__('red-jasmine-product::product.labels.operate'))->columns(1)->inlineLabel()->schema(static::operateFields()),
            Forms\Components\Fieldset::make('seo')->label(__('red-jasmine-product::product.labels.seo'))->columns(1)->inlineLabel()->schema(static::seoFields()),
            Forms\Components\Fieldset::make('shipping')->label(__('red-jasmine-product::product.labels.shipping'))->columns(1)->inlineLabel()->schema(static::shippingFields()),
            Forms\Components\Fieldset::make('supplier')->label(__('red-jasmine-product::product.labels.supplier'))->columns(1)->inlineLabel()->schema(static::supplierFields()),
            Forms\Components\Fieldset::make('other')->label(__('red-jasmine-product::product.labels.other'))->columns(1)->inlineLabel()->schema(static::otherFields()),

        ];

        return $form
            ->schema([
                         Forms\Components\Section::make(__('red-jasmine-product::product.labels.product'))->label(__('red-jasmine-product::product.labels.product'))->schema($schema),
                     ])
            ->columns(1);
    }

    public static function basicInfoFields() : array
    {
        return [
            ...static::ownerFormSchemas(),
            Forms\Components\TextInput::make('title')
                                      ->label(__('red-jasmine-product::product.fields.title'))
                                      ->required()
                                      ->maxLength(60),
            Forms\Components\TextInput::make('slogan')
                                      ->label(__('red-jasmine-product::product.fields.slogan'))
                                      ->maxLength(255),
            Forms\Components\ToggleButtons::make('product_type')
                                          ->label(__('red-jasmine-product::product.fields.product_type'))
                                          ->required()
                                          ->inline()
                                          ->default(ProductTypeEnum::GOODS)
                                          ->useEnum(ProductTypeEnum::class),
            Forms\Components\ToggleButtons::make('shipping_type')
                                          ->label(__('red-jasmine-product::product.fields.shipping_type'))
                                          ->required()
                                          ->inline()
                                          ->default(ShippingTypeEnum::EXPRESS)
                                          ->useEnum(ShippingTypeEnum::class),

            Forms\Components\ToggleButtons::make('status')
                                          ->label(__('red-jasmine-product::product.fields.status'))
                                          ->required()
                                          ->inline()
                                          ->default(ProductStatusEnum::ON_SALE)
                                          ->useEnum(ProductStatusEnum::class),


        ];
    }

    public static function productAttributesFields() : array
    {
        return [
            SelectTree::make('category_id')
                      ->label(__('red-jasmine-product::product.fields.category_id'))
                      ->relationship('category', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0), // 设置可选
            SelectTree::make('brand_id')
                      ->label(__('red-jasmine-product::product.fields.brand_id'))
                      ->relationship('brand', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0),
            Forms\Components\TextInput::make('product_model')
                                      ->label(__('red-jasmine-product::product.fields.product_model'))
                                      ->maxLength(60),
            SelectTree::make('product_group_id')
                      ->label(__('red-jasmine-product::product.fields.product_group_id'))
                      ->relationship(relationship: 'productGroup',
                          titleAttribute:          'name',
                          parentAttribute:         'parent_id',
                          modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                ->where('owner_id', $get('owner_id')),
                          modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                     ->where('owner_id', $get('owner_id'))
                          ,
                      )
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0),

            SelectTree::make('extend_product_groups')
                      ->label(__('red-jasmine-product::product.fields.extend_groups'))
                      ->relationship(relationship: 'extendProductGroups',
                          titleAttribute:          'name',
                          parentAttribute:         'parent_id',
                          modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                ->where('owner_id', $get('owner_id')),
                          modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                     ->where('owner_id', $get('owner_id'))
                          ,
                      )

//                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default([]),

            Forms\Components\Fieldset::make('basicProps')
                                     ->label(__('red-jasmine-product::product.fields.basic_props'))
                                     ->columns(1)->inlineLabel()->schema([ static::basicProps()->hiddenLabel() ]),
        ];
    }

    protected static function basicProps() : Repeater
    {

        return Repeater::make('basic_props')
                       ->label(__('red-jasmine-product::product.fields.basic_props'))
                       ->schema([
                                    Forms\Components\Select::make('pid')
                                                           ->hiddenLabel()
                                                           ->inlineLabel()
                                                           ->label(__('red-jasmine-product::product.props.pid'))
                                                           ->live()
                                                           ->columnSpan(2)
                                                           ->required()
                                                           ->options(ProductProperty::limit(50)->pluck('name', 'id')->toArray())
                                                           ->searchable()
                                                           ->getSearchResultsUsing(fn(string $search
                                                           ) : array => ProductProperty::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                                           ->getOptionLabelUsing(fn(
                                                               $value,
                                                               Forms\Get $get
                                                           ) : ?string => $get('name')),


                                    Repeater::make('values')
                                            ->label(__('red-jasmine-product::product.props.values'))
                                            ->hiddenLabel()
                                            ->inlineLabel()
                                            ->schema([
                                                         Forms\Components\Select::make('vid')
                                                                                ->label(__('red-jasmine-product::product.props.vid'))
                                                                                ->searchable()
                                                                                ->hiddenLabel()
                                                                                ->required()
                                                                                ->options(fn(Forms\Get $get) => ProductPropertyValue::where('pid', $get('../../pid'))->limit(50)->pluck('name', 'id')->toArray())
                                                                                ->getSearchResultsUsing(fn(string $search) : array => ProductPropertyValue::when($search, function ($query) use ($search) {
                                                                                    $query->where('name', 'like', "%{$search}%");
                                                                                })->limit(20)->pluck('name', 'id')->toArray())
                                                                                ->getOptionLabelUsing(fn($value, Forms\Get $get) : ?string => $get('name'))
                                                                                ->hidden(fn(Forms\Get $get) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                                         Forms\Components\TextInput::make('name')
                                                                                   ->maxLength(30)
                                                                                   ->hiddenLabel()
                                                                                   ->required()
                                                                                   ->suffix(fn(Forms\Get $get
                                                                                   ) => ProductProperty::find($get('../../pid'))?->unit)
                                                                                   ->inlineLabel()
                                                                                   ->hidden(fn(Forms\Get $get
                                                                                   ) => ProductProperty::find($get('../../pid'))?->type !== PropertyTypeEnum::TEXT),


                                                         Forms\Components\TextInput::make('alias')
                                                                                   ->placeholder('请输入别名')
                                                                                   ->maxLength(30)
                                                                                   ->hiddenLabel()
                                                                                   ->hidden(fn(Forms\Get $get
                                                                                   ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                                     ])
                                            ->columns()
                                            ->columnSpan(2)
                                            ->reorderable(false)
                                            ->deletable(fn($state) => count($state) > 1)
                                            ->minItems(1)
                                            ->maxItems(fn(Forms\Get $get
                                            ) => ProductProperty::find($get('pid'))?->is_allow_multiple ? 30 : 1)
                                            ->hidden(fn(Forms\Get $get) => !$get('pid')),


                                ])
                       ->default([])
                       ->inlineLabel(false)
                       ->columns(4)
                       ->columnSpan('full')
                       ->reorderable(false);
    }


    protected static function specifications() : array
    {
        return [ Forms\Components\ToggleButtons::make('is_multiple_spec')
                                               ->label(__('red-jasmine-product::product.fields.is_multiple_spec'))
                                               ->required()
                                               ->boolean()
                                               ->live()
                                               ->inline()
                                               ->default(0),


                 static::saleProps()->visible(fn(Forms\Get $get) => $get('is_multiple_spec'))
                       ->live()
                       ->afterStateUpdated(function ($state, $old, Forms\Get $get, Forms\Set $set) {

                           try {
                               $saleProps = array_values($get('sale_props') ?? []);

                               $saleProps = array_map(function ($item) {
                                   $item['values'] = array_values($item['values'] ?? []);
                                   return $item;
                               }, $saleProps);
                               $service   = app(PropertyValidateService::class);
                               $crossJoin = $service->crossJoin($saleProps);

                               $oldSku = $get('skus') ?? [];
                               $oldSku = collect($oldSku)->keyBy('properties_sequence');

                               $skus = [];
                               foreach ($crossJoin as $properties => $propertyName) {

                                   $sku                    = $oldSku[$properties] ?? [
                                       'properties_sequence' => $properties,
                                       'properties_name'     => $propertyName,
                                       'price'               => null,
                                       'market_price'        => null,
                                       'cost_price'          => null,
                                       'stock'               => null,
                                       'safety_stock'        => 0,
                                       'status'              => ProductStatusEnum::ON_SALE->value,

                                   ];
                                   $sku['properties_name'] = $propertyName;
                                   $skus[]                 = $sku;
                               }

                               $set('skus', $skus);
                           } catch (Throwable $throwable) {
                               $set('skus', []);
                           }
                       }),

                 static::skus()
                       ->deletable(false)
                       ->live()
                       ->visible(fn(Forms\Get $get) => $get('is_multiple_spec')),

                 Forms\Components\Section::make('')->schema([


                                                                Forms\Components\TextInput::make('price')
                                                                                          ->label(__('red-jasmine-product::product.fields.price'))
                                                                                          ->required()
                                                                                          ->numeric()
                                                                                          ->formatStateUsing(fn($state
                                                                                          ) => is_object($state) ? $state->value() : $state)
                                                                ,
                                                                Forms\Components\TextInput::make('stock')
                                                                                          ->label(__('red-jasmine-product::product.fields.stock'))
                                                                                          ->required()
                                                                                          ->numeric()
                                                                ,

                                                                Forms\Components\TextInput::make('market_price')
                                                                                          ->label(__('red-jasmine-product::product.fields.market_price'))
                                                                                          ->numeric()
                                                                                          ->formatStateUsing(fn($state
                                                                                          ) => is_object($state) ? $state->value() : $state)

                                                                ,
                                                                Forms\Components\TextInput::make('cost_price')
                                                                                          ->label(__('red-jasmine-product::product.fields.cost_price'))
                                                                                          ->numeric()
                                                                                          ->formatStateUsing(fn($state
                                                                                          ) => is_object($state) ? $state->value() : $state)
                                                                ,
                                                                Forms\Components\TextInput::make('safety_stock')
                                                                                          ->label(__('red-jasmine-product::product.fields.safety_stock'))
                                                                                          ->numeric()
                                                                                          ->default(0),


                                                            ])->hidden(fn(Forms\Get $get
                 ) => $get('is_multiple_spec')), ];
    }


    public static function saleInfoFields() : array
    {
        return [


            Forms\Components\TextInput::make('unit')
                                      ->label(__('red-jasmine-product::product.fields.unit'))
                                      ->maxLength(32),
            Forms\Components\TextInput::make('unit_quantity')
                                      ->label(__('red-jasmine-product::product.fields.unit_quantity'))
                                      ->numeric()
                                      ->default(1)
                                      ->minValue(1),

            Forms\Components\TextInput::make('outer_id')
                                      ->label(__('red-jasmine-product::product.fields.outer_id'))
                                      ->maxLength(255),
            Forms\Components\TextInput::make('barcode')
                                      ->label(__('red-jasmine-product::product.fields.barcode'))
                                      ->maxLength(32),

            Forms\Components\TextInput::make('min_limit')
                                      ->label(__('red-jasmine-product::product.fields.min_limit'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\TextInput::make('max_limit')
                                      ->label(__('red-jasmine-product::product.fields.max_limit'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\TextInput::make('step_limit')
                                      ->label(__('red-jasmine-product::product.fields.step_limit'))
                                      ->required()
                                      ->numeric()
                                      ->default(1),
            Forms\Components\TextInput::make('vip')
                                      ->label(__('red-jasmine-product::product.fields.vip'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),

            Forms\Components\ToggleButtons::make('order_quantity_limit_type')
                                          ->label(__('red-jasmine-product::product.fields.order_quantity_limit_type'))
                                          ->required()
                                          ->live()
                                          ->grouped()
                                          ->useEnum(OrderQuantityLimitTypeEnum::class)
                                          ->default(OrderQuantityLimitTypeEnum::UNLIMITED),

            Forms\Components\TextInput::make('order_quantity_limit_num')
                                      ->label(__('red-jasmine-product::product.fields.order_quantity_limit_num'))
                                      ->required()
                                      ->numeric()
                                      ->default(0)
                                      ->visible(fn(Forms\Get $get) => $get('order_quantity_limit_type') !== OrderQuantityLimitTypeEnum::UNLIMITED->value)
            ,
        ];
    }

    protected static function saleProps() : Repeater
    {
        return Repeater::make('sale_props')
                       ->label(__('red-jasmine-product::product.fields.sale_props'))
                       ->schema([

                                    Forms\Components\Select::make('pid')
                                                           ->label(__('red-jasmine-product::product.props.pid'))
                                                           ->live()
                                                           ->columns(1)
                                                           ->required()
                                                           ->columnSpan(1)
                                                           ->options(ProductProperty::limit(50)->pluck('name', 'id')->toArray())
                                                           ->searchable()
                                                           ->getSearchResultsUsing(fn(string $search) : array => ProductProperty::where('name',
                                                                                                                                        'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                                           ->getOptionLabelUsing(fn($value, Forms\Get $get) : ?string => $get('name')),

                                    Repeater::make('values')
                                            ->label(__('red-jasmine-product::product.props.values'))
                                            ->hiddenLabel()
                                            ->schema([
                                                         Forms\Components\Select::make('vid')
                                                                                ->label(__('red-jasmine-product::product.props.vid'))
                                                                                ->searchable()
                                                                                ->required()
                                                                                ->hiddenLabel()
                                                                                ->options(fn(Forms\Get $get) => ProductPropertyValue::where('pid', $get('../../pid'))->limit(50)->pluck('name', 'id')->toArray())
                                                                                ->getSearchResultsUsing(fn(string $search
                                                                                ) : array => ProductPropertyValue::where('name', 'like',
                                                                                                                         "%{$search}%")->limit(50)->pluck('name',
                                                                                                                                                          'id')->toArray())
                                                                                ->getOptionLabelUsing(fn(
                                                                                    $value,
                                                                                    Forms\Get $get
                                                                                ) : ?string => $get('name'))
                                                                                ->hidden(fn(Forms\Get $get
                                                                                ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                                         Forms\Components\TextInput::make('name')
                                                                                   ->hiddenLabel()
                                                                                   ->maxLength(30)
                                                                                   ->required()
                                                                                   ->hidden(fn(Forms\Get $get
                                                                                   ) => ProductProperty::find($get('../../pid'))?->type !== PropertyTypeEnum::TEXT),


                                                         Forms\Components\TextInput::make('alias')
                                                                                   ->label(__('red-jasmine-product::product.props.alias'))
                                                                                   ->hiddenLabel()
                                                                                   ->placeholder('请输入别名')
                                                                                   ->maxLength(30)
                                                                                   ->hidden(fn(Forms\Get $get
                                                                                   ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                                     ])
                                            ->grid(4)
                                            ->columns()
                                            ->columnSpanFull()
                                            ->minItems(1)
                                            ->reorderable(false)
                                            ->hidden(fn(Forms\Get $get) => !$get('pid')),


                                ])
                       ->default([])
                       ->inlineLabel(false)
                       ->columns(4)
                       ->columnSpan('full')
                       ->reorderable(false);
    }

    protected static function skus() : TableRepeater
    {
        return TableRepeater::make('skus')
                            ->label(__('red-jasmine-product::product.fields.skus'))
                            ->headers([
                                          Header::make('properties_name')->label(__('red-jasmine-product::product.fields.properties_name')),
                                          Header::make('image')->label(__('red-jasmine-product::product.fields.image')),
                                          Header::make('price')->label(__('red-jasmine-product::product.fields.price'))->markAsRequired(),
                                          Header::make('stock')->label(__('red-jasmine-product::product.fields.stock'))->markAsRequired(),
                                          Header::make('market_price')->label(__('red-jasmine-product::product.fields.market_price')),
                                          Header::make('cost_price')->label(__('red-jasmine-product::product.fields.cost_price')),
                                          Header::make('safety_stock')->label(__('red-jasmine-product::product.fields.safety_stock')),
                                          Header::make('barcode')->label(__('red-jasmine-product::product.fields.barcode')),
                                          Header::make('outer_id')->label(__('red-jasmine-product::product.fields.outer_id')),
                                          Header::make('supplier_sku_id')->label(__('red-jasmine-product::product.fields.supplier_sku_id')),
                                          Header::make('status')->label(__('red-jasmine-product::product.fields.status')),
                                      ])
                            ->schema([
                                         Forms\Components\Hidden::make('properties_sequence'),
                                         Forms\Components\TextInput::make('properties_name')->readOnly(),
                                         Forms\Components\FileUpload::make('image')->image(),
                                         Forms\Components\TextInput::make('price')->required()->numeric()->formatStateUsing(fn(
                                             $state
                                         ) => is_object($state) ? $state->value() : $state),
                                         Forms\Components\TextInput::make('stock')->required()->maxLength(32),
                                         Forms\Components\TextInput::make('market_price')->numeric()->formatStateUsing(fn(
                                             $state
                                         ) => is_object($state) ? $state->value() : $state),
                                         Forms\Components\TextInput::make('cost_price')->numeric()->formatStateUsing(fn(
                                             $state
                                         ) => is_object($state) ? $state->value() : $state),

                                         Forms\Components\TextInput::make('safety_stock')
                                                                   ->numeric()
                                                                   ->default(0),
                                         Forms\Components\TextInput::make('barcode')->maxLength(32),
                                         Forms\Components\TextInput::make('outer_id')->maxLength(32),
                                         Forms\Components\TextInput::make('supplier_sku_id')->maxLength(32),
                                         Forms\Components\Select::make('status')->required()
                                                                ->default(ProductStatusEnum::ON_SALE->value)
                                                                ->options(ProductStatusEnum::skusStatus()),


                                     ])->inlineLabel(false)
                            ->columnSpan('full')
                            ->streamlined()
                            ->reorderable(false)
                            ->addable(false);
    }

    public static function descriptionFields() : array
    {
        return [
            Forms\Components\FileUpload::make('image')->label(__('red-jasmine-product::product.fields.image'))->image(),
            Forms\Components\FileUpload::make('images')->label(__('red-jasmine-product::product.fields.images'))->image()->multiple(),
            Forms\Components\FileUpload::make('videos')->label(__('red-jasmine-product::product.fields.videos'))->image()->multiple(),
            Forms\Components\RichEditor::make('detail')->label(__('red-jasmine-product::product.fields.detail')),
        ];
    }

    public static function operateFields() : array
    {

        return [
            Forms\Components\TextInput::make('tips')->label(__('red-jasmine-product::product.fields.tips'))->maxLength(255),
            Forms\Components\TextInput::make('points')
                                      ->label(__('red-jasmine-product::product.fields.points'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\Radio::make('is_hot')->label(__('red-jasmine-product::product.fields.is_hot'))->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_new')->label(__('red-jasmine-product::product.fields.is_new'))->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_best')->label(__('red-jasmine-product::product.fields.is_best'))->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_benefit')->label(__('red-jasmine-product::product.fields.is_benefit'))->required()->inline()->boolean()->default(0),
            Forms\Components\TextInput::make('sort')
                                      ->label(__('red-jasmine-product::product.fields.sort'))
                                      ->required()
                                      ->numeric()
                                      ->minValue(0)
                                      ->default(0),
        ];
    }

    public static function seoFields() : array
    {
        return [
            Forms\Components\TextInput::make('keywords')->label(__('red-jasmine-product::product.fields.keywords'))->maxLength(255),
            Forms\Components\TextInput::make('description')->label(__('red-jasmine-product::product.fields.description'))->maxLength(255),
        ];
    }

    public static function shippingFields() : array
    {
        return [
            Forms\Components\Radio::make('freight_payer')
                                  ->label(__('red-jasmine-product::product.fields.freight_payer'))
                                  ->required()
                                  ->default(FreightPayerEnum::SELLER->value)
                                  ->inline()->options(FreightPayerEnum::options()),
            Forms\Components\TextInput::make('postage_id')->label(__('red-jasmine-product::product.fields.postage_id'))->numeric(),
            Forms\Components\TextInput::make('delivery_time')
                                      ->label(__('red-jasmine-product::product.fields.delivery_time'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),

        ];
    }

    public static function supplierFields() : array
    {
        return [
            Forms\Components\TextInput::make('supplier_type')
                                      ->label(__('red-jasmine-product::product.fields.supplier_type'))
                                      ->maxLength(255),
            Forms\Components\TextInput::make('supplier_id')
                                      ->label(__('red-jasmine-product::product.fields.supplier_id'))
                                      ->numeric(),
            Forms\Components\TextInput::make('supplier_product_id')
                                      ->label(__('red-jasmine-product::product.fields.supplier_product_id'))
                                      ->numeric(),
        ];
    }

    public static function otherFields() : array
    {
        return [
            Forms\Components\Radio::make('is_customized')
                                  ->label(__('red-jasmine-product::product.fields.is_customized'))
                                  ->required()
                                  ->boolean()
                                  ->inline()
                                  ->default(0),

            Forms\Components\TextInput::make('remarks')
                                      ->label(__('red-jasmine-product::product.fields.remarks'))
                                      ->maxLength(255),

            ...static::operateFormSchemas()
        ];
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->deferLoading()
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine-product::product.fields.id'))
                                                   ->sortable(),
                          ...static::ownerTableColumns(),
                          Tables\Columns\TextColumn::make('title')
                                                   ->label(__('red-jasmine-product::product.fields.title'))
                                                   ->searchable(),
                          Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image')),

                          Tables\Columns\TextColumn::make('product_type')
                                                   ->label(__('red-jasmine-product::product.fields.product_type'))
                                                   ->enum(),
                          Tables\Columns\TextColumn::make('shipping_type')
                                                   ->label(__('red-jasmine-product::product.fields.shipping_type'))
                                                   ->enum(),
                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine-product::product.fields.status'))
                                                   ->enum(),


                          Tables\Columns\TextColumn::make('barcode')
                                                   ->label(__('red-jasmine-product::product.fields.barcode'))
                                                   ->searchable()
                                                   ->toggleable(true, true),
                          Tables\Columns\TextColumn::make('outer_id')
                                                   ->label(__('red-jasmine-product::product.fields.outer_id'))
                                                   ->searchable()
                                                   ->toggleable(true, true),
                          Tables\Columns\TextColumn::make('is_multiple_spec')
                                                   ->label(__('red-jasmine-product::product.fields.is_multiple_spec'))
                                                   ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('brand.name')
                                                   ->label(__('red-jasmine-product::product.fields.brand_id'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('category.name')
                                                   ->label(__('red-jasmine-product::product.fields.category_id'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('sellerCategory.name')
                                                   ->label(__('red-jasmine-product::product.fields.seller_category_id'))
                                                   ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('price')
                                                   ->label(__('red-jasmine-product::product.fields.price'))
                                                   ->money()
                          ,

                          Tables\Columns\TextColumn::make('cost_price')
                                                   ->label(__('red-jasmine-product::product.fields.cost_price'))
                                                   ->numeric()
                                                   ->toggleable(true, true),
                          Tables\Columns\TextColumn::make('market_price')
                                                   ->label(__('red-jasmine-product::product.fields.market_price'))
                                                   ->numeric()
                                                   ->toggleable(true, true),
                          Tables\Columns\TextColumn::make('stock')
                                                   ->label(__('red-jasmine-product::product.fields.stock'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('safety_stock')
                                                   ->label(__('red-jasmine-product::product.fields.safety_stock'))
                                                   ->numeric()
                                                   ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('sort')
                                                   ->label(__('red-jasmine-product::product.fields.sort'))
                                                   ->numeric()
                                                   ->toggleable(isToggledHiddenByDefault: true)
                                                   ->sortable(),


                          Tables\Columns\TextColumn::make('unit')
                                                   ->label(__('red-jasmine-product::product.fields.unit'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('unit_quantity')
                                                   ->label(__('red-jasmine-product::product.fields.unit_quantity'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('sales')
                                                   ->label(__('red-jasmine-product::product.fields.sales'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('views')
                                                   ->label(__('red-jasmine-product::product.fields.views'))
                                                   ->numeric()
                                                   ->sortable(),

                          Tables\Columns\TextColumn::make('modified_time')
                                                   ->label(__('red-jasmine-product::product.fields.modified_time'))
                                                   ->dateTime()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('version')
                                                   ->label(__('red-jasmine-product::product.fields.version'))
                                                   ->dateTime()->toggleable(isToggledHiddenByDefault: true),


                          ...static::operateTableColumns()

                      ])
            ->filters([


                          Tables\Filters\SelectFilter::make('status')
                                                     ->multiple()
                                                     ->label(__('red-jasmine-product::product.fields.status'))
                                                     ->options(ProductStatusEnum::options()),

                          Tables\Filters\SelectFilter::make('product_type')
                                                     ->multiple()
                                                     ->label(__('red-jasmine-product::product.fields.product_type'))
                                                     ->options(ProductTypeEnum::options()),

                          Tables\Filters\SelectFilter::make('shipping_type')
                                                     ->multiple()
                                                     ->label(__('red-jasmine-product::product.fields.shipping_type'))
                                                     ->options(ShippingTypeEnum::options()),

                          Tables\Filters\TrashedFilter::make(),
                      ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->recordUrl(null)
            ->actions([
                          Tables\Actions\ViewAction::make(),
                          Tables\Actions\EditAction::make(),
                          Tables\Actions\DeleteAction::make(),
                          Tables\Actions\RestoreAction::make(),
                          Tables\Actions\ForceDeleteAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                   ]),
                          ]);
    }

    public static function getPages() : array
    {
        return [
            'index'  => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view'   => ViewProduct::route('/{record}'),
            'edit'   => EditProduct::route('/{record}/edit'),

        ];
    }
}
