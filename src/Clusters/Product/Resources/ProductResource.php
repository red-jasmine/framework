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
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
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


    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product.labels.product');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                         Forms\Components\Section::make('商品')
                                                 ->schema([
                                                              Forms\Components\Fieldset::make('basicInfoFields')->label('基本信息')->columns(1)->inlineLabel()->schema(static::basicInfoFields()),
                                                              Forms\Components\Fieldset::make('productInfoFields')->label('商品属性')->columns(1)->inlineLabel()->schema(static::productInfoFields()),
                                                              Forms\Components\Fieldset::make('saleInfoFields')->label('销售信息')->columns(1)->inlineLabel()->schema(static::saleInfoFields()),
                                                              Forms\Components\Fieldset::make('descriptionFields')->label('商品描述')->columns(1)->inlineLabel()->schema(static::descriptionFields()),
                                                              Forms\Components\Fieldset::make('operateFields')->label('运营')->columns(1)->inlineLabel()->schema(static::operateFields()),
                                                              Forms\Components\Fieldset::make('seoFields')->label('SEO')->columns(1)->inlineLabel()->schema(static::seoFields()),
                                                              Forms\Components\Fieldset::make('shippingFields')->label('发货服务')->columns(1)->inlineLabel()->schema(static::shippingFields()),
                                                              Forms\Components\Fieldset::make('supplierFields')->label('供应商')->columns(1)->inlineLabel()->schema(static::supplierFields()),
                                                              Forms\Components\Fieldset::make('otherFields')->label('其他')->columns(1)->inlineLabel()->schema(static::otherFields()),

                                                          ]),
                     ])
            ->columns(1);
    }

    public static function basicInfoFields() : array
    {
        return [
            Forms\Components\TextInput::make('owner_type')
                                      ->label(__('red-jasmine.product::product.fields.owner_type'))
                                      ->required()
                                      ->maxLength(255),
            Forms\Components\TextInput::make('owner_id')
                                      ->label(__('red-jasmine.product::product.fields.owner_id'))
                                      ->required()
                                      ->numeric(),
            Forms\Components\TextInput::make('title')
                                      ->label(__('red-jasmine.product::product.fields.title'))
                                      ->required()
                                      ->maxLength(60),
            Forms\Components\TextInput::make('slogan')
                                      ->label(__('red-jasmine.product::product.fields.slogan'))
                                      ->maxLength(255),
            Forms\Components\Radio::make('product_type')
                                  ->label(__('red-jasmine.product::product.fields.product_type'))
                                  ->required()
                                  ->default(ProductTypeEnum::GOODS)->inline()
                                  ->options(ProductTypeEnum::options()),
            Forms\Components\Radio::make('shipping_type')
                                  ->label(__('red-jasmine.product::product.fields.shipping_type'))
                                  ->required()
                                  ->inline()
                                  ->default(ShippingTypeEnum::EXPRESS)
                                  ->options(ShippingTypeEnum::options()),
            Forms\Components\Radio::make('is_customized')
                                  ->label(__('red-jasmine.product::product.fields.is_customized'))
                                  ->required()
                                  ->boolean()
                                  ->inline()
                                  ->default(0),
            Forms\Components\Radio::make('status')
                                  ->label(__('red-jasmine.product::product.fields.status'))
                                  ->required()
                                  ->inline()
                                  ->default(ProductStatusEnum::ON_SALE)
                                  ->options(ProductStatusEnum::options()),


        ];
    }

    public static function productInfoFields() : array
    {
        return [
            SelectTree::make('category_id')
                      ->label(__('red-jasmine.product::product.fields.category_id'))
                      ->relationship('category', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0), // 设置可选
            SelectTree::make('brand_id')
                      ->label(__('red-jasmine.product::product.fields.brand_id'))
                      ->relationship('brand', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0),
            Forms\Components\TextInput::make('product_model')
                                      ->label(__('red-jasmine.product::product.fields.product_model'))
                                      ->maxLength(60),
            SelectTree::make('seller_category_id')
                      ->label(__('red-jasmine.product::product.fields.seller_category_id'))
                      ->relationship('sellerCategory', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0),

            Forms\Components\Fieldset::make('basicInfoFields')->label('基础属性')->columns(1)->inlineLabel()->schema([ static::basicProps()->hiddenLabel() ]),
        ];
    }

    protected static function basicProps() : Repeater
    {

        return Repeater::make('basic_props')
                       ->label(__('red-jasmine.product::product.fields.basic_props'))
                       ->schema([


                                    Forms\Components\Select::make('pid')
                                                           ->hiddenLabel()
                                                           ->inlineLabel()
                                                           ->label(__('red-jasmine.product::product.props.pid'))
                                                           ->live()
                                                           ->columnSpan(2)
                                                           ->required()
                                                           ->searchable()
                                                           ->getSearchResultsUsing(fn(string $search
                                                           ) : array => ProductProperty::where('name',
                                                                                               'like', "%{$search}%")->limit(50)->pluck('name',
                                                                                                                                        'id')->toArray())
                                                           ->getOptionLabelUsing(fn(
                                                               $value,
                                                               Forms\Get $get
                                                           ) : ?string => $get('name')),


                                    Repeater::make('values')
                                            ->label(__('red-jasmine.product::product.props.values'))
                                            ->hiddenLabel()
                                            ->inlineLabel()
                                            ->schema([
                                                         Forms\Components\Select::make('vid')
                                                                                ->label(__('red-jasmine.product::product.props.vid'))
                                                                                ->searchable()
                                                                                ->hiddenLabel()
                                                                                ->required()
                                                                                ->getSearchResultsUsing(fn(string $search
                                                                                ) : array => ProductPropertyValue::when($search, function ($query) use ($search) {
                                                                                    $query->where('name', 'like', "%{$search}%");
                                                                                })->limit(20)->pluck('name', 'id')->toArray())
                                                                                ->getOptionLabelUsing(fn(
                                                                                    $value,
                                                                                    Forms\Get $get
                                                                                ) : ?string => $get('name'))
                                                                                ->hidden(fn(Forms\Get $get
                                                                                ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


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

    public static function saleInfoFields() : array
    {
        return [
            Forms\Components\Radio::make('is_multiple_spec')
                                  ->label(__('red-jasmine.product::product.fields.is_multiple_spec'))
                                  ->required()->boolean()->live()->inline()->default(0),


            static::saleProps()->visible(fn(Forms\Get $get) => $get('is_multiple_spec'))->live()
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
                          $oldSku = collect($oldSku)->keyBy('properties');

                          $skus = [];
                          foreach ($crossJoin as $properties => $propertyName) {


                              $sku                    = $oldSku[$properties] ?? [
                                  'properties'      => $properties,
                                  'properties_name' => $propertyName,
                                  'price'           => null,
                                  'market_price'    => 0,
                                  'cost_price'      => 0,
                                  'stock'           => 0,
                                  'safety_stock'    => 0,
                                  'status'          => ProductStatusEnum::ON_SALE->value,

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
            Forms\Components\TextInput::make('stock')
                                      ->label(__('red-jasmine.product::product.fields.stock'))
                                      ->required()
                                      ->numeric()
                                      ->default(0)
                                      ->hidden(fn(Forms\Get $get
                                      ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('price')
                                      ->label(__('red-jasmine.product::product.fields.price'))
                                      ->required()
                                      ->numeric()
                                      ->default(0.00)
                                      ->formatStateUsing(fn($state
                                      ) => is_object($state) ? $state->value() : $state)
                                      ->hidden(fn(Forms\Get $get
                                      ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('market_price')
                                      ->label(__('red-jasmine.product::product.fields.market_price'))
                                      ->required()
                                      ->numeric()
                                      ->formatStateUsing(fn($state
                                      ) => is_object($state) ? $state->value() : $state)
                                      ->default(0.00)->hidden(fn(Forms\Get $get
                ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('cost_price')
                                      ->label(__('red-jasmine.product::product.fields.cost_price'))
                                      ->required()
                                      ->numeric()
                                      ->formatStateUsing(fn($state
                                      ) => is_object($state) ? $state->value() : $state)
                                      ->default(0.00)->hidden(fn(Forms\Get $get
                ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('unit')
                                      ->label(__('red-jasmine.product::product.fields.unit'))
                                      ->required()
                                      ->numeric()
                                      ->default(1),
            Forms\Components\TextInput::make('unit_name')
                                      ->label(__('red-jasmine.product::product.fields.unit_name'))
                                      ->maxLength(32),
            Forms\Components\TextInput::make('barcode')
                                      ->label(__('red-jasmine.product::product.fields.barcode'))
                                      ->maxLength(32),
            Forms\Components\TextInput::make('outer_id')
                                      ->label(__('red-jasmine.product::product.fields.outer_id'))
                                      ->maxLength(255),

            Forms\Components\TextInput::make('safety_stock')
                                      ->label(__('red-jasmine.product::product.fields.safety_stock'))
                                      ->numeric()
                                      ->default(0),

            Forms\Components\TextInput::make('min_limit')
                                      ->label(__('red-jasmine.product::product.fields.min_limit'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\TextInput::make('max_limit')
                                      ->label(__('red-jasmine.product::product.fields.max_limit'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\TextInput::make('step_limit')
                                      ->label(__('red-jasmine.product::product.fields.step_limit'))
                                      ->required()
                                      ->numeric()
                                      ->default(1),
            Forms\Components\TextInput::make('vip')
                                      ->label(__('red-jasmine.product::product.fields.vip'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),

        ];
    }

    protected static function saleProps() : Repeater
    {
        return Repeater::make('sale_props')
                       ->label(__('red-jasmine.product::product.fields.sale_props'))
                       ->schema([

                                    Forms\Components\Select::make('pid')
                                                           ->label(__('red-jasmine.product::product.props.pid'))
                                                           ->live()
                                                           ->columns(1)
                                                           ->required()
                                                           ->columnSpan(1)
                                                           ->searchable()
                                                           ->getSearchResultsUsing(fn(string $search) : array => ProductProperty::where('name',
                                                                                                                                        'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                                           ->getOptionLabelUsing(fn($value, Forms\Get $get) : ?string => $get('name')),

                                    Repeater::make('values')
                                            ->label(__('red-jasmine.product::product.props.values'))
                                            ->hiddenLabel()
                                            ->schema([
                                                         Forms\Components\Select::make('vid')
                                                                                ->label(__('red-jasmine.product::product.props.vid'))
                                                                                ->searchable()
                                                                                ->required()
                                                                                ->hiddenLabel()
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
                                                                                   ->label(__('red-jasmine.product::product.props.alias'))
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
                            ->label(__('red-jasmine.product::product.fields.skus'))
                            ->headers([
                                          Header::make('properties_name')->label(__('red-jasmine.product::product.fields.properties_name')),
                                          Header::make('image')->label(__('red-jasmine.product::product.fields.image')),
                                          Header::make('price')->label(__('red-jasmine.product::product.fields.price')),
                                          Header::make('market_price')->label(__('red-jasmine.product::product.fields.market_price')),
                                          Header::make('cost_price')->label(__('red-jasmine.product::product.fields.cost_price')),
                                          Header::make('stock')->label(__('red-jasmine.product::product.fields.stock')),
                                          Header::make('safety_stock')->label(__('red-jasmine.product::product.fields.safety_stock')),
                                          Header::make('barcode')->label(__('red-jasmine.product::product.fields.barcode')),
                                          Header::make('outer_id')->label(__('red-jasmine.product::product.fields.outer_id')),
                                          Header::make('supplier_sku_id')->label(__('red-jasmine.product::product.fields.supplier_sku_id')),
                                          Header::make('status')->label(__('red-jasmine.product::product.fields.status')),
                                      ])
                            ->schema([
                                         Forms\Components\TextInput::make('properties_name')->readOnly(),
                                         Forms\Components\Hidden::make('properties'),
                                         Forms\Components\FileUpload::make('image')->image(),
                                         Forms\Components\TextInput::make('price')->required()->numeric()->default(0.00)->formatStateUsing(fn(
                                             $state
                                         ) => is_object($state) ? $state->value() : $state),
                                         Forms\Components\TextInput::make('market_price')->required()->numeric()->default(0.00)->formatStateUsing(fn(
                                             $state
                                         ) => is_object($state) ? $state->value() : $state),
                                         Forms\Components\TextInput::make('cost_price')->required()->numeric()->default(0.00)->formatStateUsing(fn(
                                             $state
                                         ) => is_object($state) ? $state->value() : $state),
                                         Forms\Components\TextInput::make('stock')->maxLength(32),
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
            Forms\Components\FileUpload::make('image')->label(__('red-jasmine.product::product.fields.image'))->image(),
            Forms\Components\FileUpload::make('images')->label(__('red-jasmine.product::product.fields.images'))->image()->multiple(),
            Forms\Components\FileUpload::make('videos')->label(__('red-jasmine.product::product.fields.videos'))->image()->multiple(),
            Forms\Components\RichEditor::make('detail')->label(__('red-jasmine.product::product.fields.detail')),
        ];
    }

    public static function operateFields() : array
    {

        return [
            Forms\Components\TextInput::make('tips')->label(__('red-jasmine.product::product.fields.tips'))->maxLength(255),
            Forms\Components\TextInput::make('points')
                                      ->label(__('red-jasmine.product::product.fields.points'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\Radio::make('is_hot')->label(__('red-jasmine.product::product.fields.is_hot'))->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_new')->label(__('red-jasmine.product::product.fields.is_new'))->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_best')->label(__('red-jasmine.product::product.fields.is_best'))->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_benefit')->label(__('red-jasmine.product::product.fields.is_benefit'))->required()->inline()->boolean()->default(0),
            Forms\Components\TextInput::make('sort')
                                      ->label(__('red-jasmine.product::product.fields.sort'))
                                      ->required()
                                      ->numeric()
                                      ->minValue(0)
                                      ->default(0),
        ];
    }

    public static function seoFields() : array
    {
        return [
            Forms\Components\TextInput::make('keywords')->label(__('red-jasmine.product::product.fields.keywords'))->maxLength(255),
            Forms\Components\TextInput::make('description')->label(__('red-jasmine.product::product.fields.description'))->maxLength(255),
        ];
    }

    public static function shippingFields() : array
    {
        return [

            Forms\Components\Radio::make('freight_payer')
                                  ->label(__('red-jasmine.product::product.fields.freight_payer'))
                                  ->required()
                                  ->default(FreightPayerEnum::SELLER->value)
                                  ->inline()->options(FreightPayerEnum::options()),
            Forms\Components\TextInput::make('postage_id')->label(__('red-jasmine.product::product.fields.postage_id'))->numeric(),
            Forms\Components\TextInput::make('delivery_time')
                                      ->label(__('red-jasmine.product::product.fields.delivery_time'))
                                      ->required()
                                      ->numeric()
                                      ->default(0),

        ];
    }

    public static function supplierFields() : array
    {
        return [
            Forms\Components\TextInput::make('supplier_type')
                                      ->label(__('red-jasmine.product::product.fields.supplier_type'))
                                      ->maxLength(255),
            Forms\Components\TextInput::make('supplier_id')
                                      ->label(__('red-jasmine.product::product.fields.supplier_id'))
                                      ->numeric(),
            Forms\Components\TextInput::make('supplier_product_id')
                                      ->label(__('red-jasmine.product::product.fields.supplier_product_id'))
                                      ->numeric(),
        ];
    }

    public static function otherFields() : array
    {
        return [
            Forms\Components\TextInput::make('remarks')
                                      ->label(__('red-jasmine.product::product.fields.remarks'))
                                      ->maxLength(255),
        ];
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->deferLoading()
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine.product::product.fields.id'))
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('owner_type')
                                                   ->label(__('red-jasmine.product::product.fields.owner_type'))
                          ,
                          Tables\Columns\TextColumn::make('owner_id')
                                                   ->label(__('red-jasmine.product::product.fields.owner_id'))
                                                   ->numeric()
                          ,
                          Tables\Columns\TextColumn::make('title')
                                                   ->label(__('red-jasmine.product::product.fields.title'))
                                                   ->searchable(),
                          Tables\Columns\ImageColumn::make('image')->label(__('red-jasmine.product::product.fields.image')),

                          Tables\Columns\TextColumn::make('product_type')
                                                   ->label(__('red-jasmine.product::product.fields.product_type'))
                                                   ->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('shipping_type')
                                                   ->label(__('red-jasmine.product::product.fields.shipping_type'))
                                                   ->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine.product::product.fields.status'))
                                                   ->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),


                          Tables\Columns\TextColumn::make('barcode')
                                                   ->label(__('red-jasmine.product::product.fields.barcode'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('outer_id')
                                                   ->label(__('red-jasmine.product::product.fields.outer_id'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('is_multiple_spec')
                                                   ->label(__('red-jasmine.product::product.fields.is_multiple_spec'))
                                                   ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('brand.name')
                                                   ->label(__('red-jasmine.product::product.fields.brand_id'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('category.name')
                                                   ->label(__('red-jasmine.product::product.fields.category_id'))
                                                   ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('sellerCategory.name')
                                                   ->label(__('red-jasmine.product::product.fields.seller_category_id'))
                                                   ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('price')
                                                   ->label(__('red-jasmine.product::product.fields.price'))
                                                   ->money()
                          ,

                          Tables\Columns\TextColumn::make('cost_price')
                                                   ->label(__('red-jasmine.product::product.fields.cost_price'))
                                                   ->numeric()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('market_price')
                                                   ->label(__('red-jasmine.product::product.fields.market_price'))
                                                   ->numeric()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('stock')
                                                   ->label(__('red-jasmine.product::product.fields.stock'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('safety_stock')
                                                   ->label(__('red-jasmine.product::product.fields.safety_stock'))
                                                   ->numeric()
                                                   ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('sort')
                                                   ->label(__('red-jasmine.product::product.fields.sort'))
                                                   ->numeric()
                                                   ->toggleable(isToggledHiddenByDefault: true)
                                                   ->sortable(),

                          Tables\Columns\TextColumn::make('sales')
                                                   ->label(__('red-jasmine.product::product.fields.sales'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('views')
                                                   ->label(__('red-jasmine.product::product.fields.views'))
                                                   ->numeric()
                                                   ->sortable(),

                          Tables\Columns\TextColumn::make('created_at')
                                                   ->label(__('red-jasmine.product::product.fields.created_at'))
                                                   ->dateTime()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('modified_time')
                                                   ->label(__('red-jasmine.product::product.fields.modified_time'))
                                                   ->dateTime()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('version')
                                                   ->label(__('red-jasmine.product::product.fields.version'))
                                                   ->dateTime()->toggleable(isToggledHiddenByDefault: true),


                      ])
            ->filters([
                          Tables\Filters\TrashedFilter::make(),
                      ])
            ->recordUrl(null)
            ->actions([
                          Tables\Actions\ViewAction::make(),
                          Tables\Actions\EditAction::make(),
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
