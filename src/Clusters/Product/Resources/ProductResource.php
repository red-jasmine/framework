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
                                      ->required()
                                      ->maxLength(255),
            Forms\Components\TextInput::make('owner_id')
                                      ->required()
                                      ->numeric(),
            Forms\Components\TextInput::make('title')
                                      ->required()
                                      ->maxLength(60),
            Forms\Components\TextInput::make('slogan')
                                      ->maxLength(255),
            Forms\Components\Radio::make('product_type')
                                  ->required()
                                  ->default(ProductTypeEnum::GOODS)->inline()
                                  ->options(ProductTypeEnum::options()),
            Forms\Components\Radio::make('shipping_type')
                                  ->required()
                                  ->inline()
                                  ->default(ShippingTypeEnum::EXPRESS)
                                  ->options(ShippingTypeEnum::options()),
            Forms\Components\Radio::make('is_customized')
                                  ->required()
                                  ->boolean()
                                  ->inline()
                                  ->default(0),
            Forms\Components\Radio::make('status')
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
                      ->relationship('category', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0), // 设置可选
            SelectTree::make('brand_id')
                      ->relationship('brand', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0),
            Forms\Components\TextInput::make('product_model')->maxLength(60),
            SelectTree::make('seller_category_id')
                      ->relationship('sellerCategory', 'name', 'parent_id')
                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0), // 设置可选

            static::basicProps()->columnSpan('full'),
        ];
    }

    protected static function basicProps() : Repeater
    {

        return Repeater::make('basic_props')->schema([

                                                         Forms\Components\Section::make()
                                                                                 ->schema([

                                                                                              Forms\Components\Select::make('pid')
                                                                                                                     ->live()
                                                                                                                     ->columnSpan(1)
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

                                                                                          ])->columnSpan(1),


                                                         Repeater::make('values')
                                                                 ->hiddenLabel()
                                                                 ->schema([
                                                                              Forms\Components\Select::make('vid')
                                                                                                     ->searchable()
                                                                                                     ->hiddenLabel()
                                                                                                     ->required()
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
                                                                                                        ->maxLength(30)
                                                                                                        ->hiddenLabel()
                                                                                                        ->required()
                                                                                                        ->suffix(fn(Forms\Get $get
                                                                                                        ) => ProductProperty::find($get('../../pid'))?->unit)
                                                                                                        ->inlineLabel()
                                                                                                        ->hidden(fn(Forms\Get $get
                                                                                                        ) => ProductProperty::find($get('../../pid'))?->type !== PropertyTypeEnum::TEXT),


                                                                              Forms\Components\TextInput::make('alias')->maxLength(30)
                                                                                                        ->hiddenLabel()
                                                                                                        ->hidden(fn(Forms\Get $get
                                                                                                        ) => ProductProperty::find($get('../../pid'))?->type === PropertyTypeEnum::TEXT),


                                                                          ])
                                                                 ->columns()
                                                                 ->columnSpan(3)
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
            Forms\Components\Radio::make('is_multiple_spec')->required()->boolean()->live()->inline()->default(0),


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
                                      ->required()
                                      ->numeric()
                                      ->default(0)
                                      ->hidden(fn(Forms\Get $get
                                      ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('price')
                                      ->required()
                                      ->numeric()
                                      ->default(0.00)
                                      ->formatStateUsing(fn($state
                                      ) => is_object($state) ? $state->value() : $state)
                                      ->hidden(fn(Forms\Get $get
                                      ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('market_price')
                                      ->required()
                                      ->numeric()
                                      ->formatStateUsing(fn($state
                                      ) => is_object($state) ? $state->value() : $state)
                                      ->default(0.00)->hidden(fn(Forms\Get $get
                ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('cost_price')
                                      ->required()
                                      ->numeric()
                                      ->formatStateUsing(fn($state
                                      ) => is_object($state) ? $state->value() : $state)
                                      ->default(0.00)->hidden(fn(Forms\Get $get
                ) => $get('is_multiple_spec')),
            Forms\Components\TextInput::make('unit')
                                      ->required()
                                      ->numeric()
                                      ->default(1),
            Forms\Components\TextInput::make('unit_name')
                                      ->maxLength(32),
            Forms\Components\TextInput::make('barcode')->maxLength(32),
            Forms\Components\TextInput::make('outer_id')->maxLength(255),

            Forms\Components\TextInput::make('safety_stock')
                                      ->numeric()
                                      ->default(0),

            Forms\Components\TextInput::make('min_limit')
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\TextInput::make('max_limit')
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\TextInput::make('step_limit')
                                      ->required()
                                      ->numeric()
                                      ->default(1),
            Forms\Components\TextInput::make('vip')
                                      ->required()
                                      ->numeric()
                                      ->default(0),

        ];
    }

    protected static function saleProps() : Repeater
    {
        return Repeater::make('sale_props')->schema([

                                                        Forms\Components\Select::make('pid')
                                                                               ->live()
                                                                               ->columns(1)
                                                                               ->required()
                                                                               ->columnSpan(1)
                                                                               ->searchable()
                                                                               ->getSearchResultsUsing(fn(string $search) : array => ProductProperty::where('name',
                                                                                                                                                            'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                                                               ->getOptionLabelUsing(fn($value, Forms\Get $get) : ?string => $get('name')),

                                                        Repeater::make('values')
                                                                ->hiddenLabel()
                                                                ->schema([
                                                                             Forms\Components\Select::make('vid')
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
                            ->headers([
                                          Header::make('properties_name'),
                                          Header::make('image'),
                                          Header::make('price'),
                                          Header::make('market_price'),
                                          Header::make('cost_price'),
                                          Header::make('stock'),
                                          Header::make('safety_stock'),
                                          Header::make('barcode'),
                                          Header::make('outer_id'),
                                          Header::make('supplier_sku_id'),
                                          Header::make('status'),
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
            Forms\Components\FileUpload::make('image')->image(),
            Forms\Components\FileUpload::make('images')->image()->multiple(),
            Forms\Components\FileUpload::make('videos')->image()->multiple(),
            Forms\Components\RichEditor::make('detail'),
        ];
    }

    public static function operateFields() : array
    {

        return [
            Forms\Components\TextInput::make('tips')->maxLength(255),
            Forms\Components\TextInput::make('points')
                                      ->required()
                                      ->numeric()
                                      ->default(0),
            Forms\Components\Radio::make('is_hot')->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_new')->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_best')->required()->inline()->boolean()->default(0),
            Forms\Components\Radio::make('is_benefit')->required()->inline()->boolean()->default(0),
            Forms\Components\TextInput::make('sort')
                                      ->required()
                                      ->numeric()
                                      ->minValue(0)
                                      ->default(0),
        ];
    }

    public static function seoFields() : array
    {
        return [
            Forms\Components\TextInput::make('keywords')->maxLength(255),
            Forms\Components\TextInput::make('description')->maxLength(255),
        ];
    }

    public static function shippingFields() : array
    {
        return [

            Forms\Components\Radio::make('freight_payer')->required()
                                  ->default(FreightPayerEnum::SELLER->value)
                                  ->inline()->options(FreightPayerEnum::options()),
            Forms\Components\TextInput::make('postage_id')->numeric(),
            Forms\Components\TextInput::make('delivery_time')
                                      ->required()
                                      ->numeric()
                                      ->default(0),

        ];
    }

    public static function supplierFields() : array
    {
        return [
            Forms\Components\TextInput::make('supplier_type')
                                      ->maxLength(255),
            Forms\Components\TextInput::make('supplier_id')
                                      ->numeric(),
            Forms\Components\TextInput::make('supplier_product_id')
                                      ->numeric(),
        ];
    }

    public static function otherFields() : array
    {
        return [
            Forms\Components\TextInput::make('remarks')->maxLength(255),
        ];
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->deferLoading()
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label('ID')
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('owner_type')
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('owner_id')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('title')
                                                   ->searchable(),
                          Tables\Columns\ImageColumn::make('image'),

                          Tables\Columns\TextColumn::make('product_type')->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('shipping_type')->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),
                          Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),


                          Tables\Columns\TextColumn::make('barcode')->searchable(),
                          Tables\Columns\TextColumn::make('outer_id')->searchable(),
                          Tables\Columns\TextColumn::make('is_multiple_spec')->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('brand.name')
                                                   ->numeric()
                              ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('category.name')
                                                   ->numeric()
                              ->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('sellerCategory.name')
                                                   ->numeric()
                              ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('price')
                                                   ->money()
                          ,

                          Tables\Columns\TextColumn::make('cost_price')->numeric()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('market_price')->numeric()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('stock')
                                                   ->numeric()
                                                   ->sortable(0),

                          Tables\Columns\TextColumn::make('safety_stock')
                                                   ->numeric()
                                                   ->toggleable(isToggledHiddenByDefault: true),

                          Tables\Columns\TextColumn::make('sort')
                                                   ->numeric()
                                                   ->toggleable(isToggledHiddenByDefault: true)
                                                   ->sortable(),

                          Tables\Columns\TextColumn::make('sales')
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('views')
                                                   ->numeric()
                                                   ->sortable(),

                          Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('modified_time')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                          Tables\Columns\TextColumn::make('version')->dateTime()->toggleable(isToggledHiddenByDefault: true),


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
