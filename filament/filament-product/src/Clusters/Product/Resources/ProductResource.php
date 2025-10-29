<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\Quantity\Components\Quantity;
use RedJasmine\Ecommerce\Domain\Form\Models\Enums\FieldTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderQuantityLimitTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\CreateProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\EditProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\ListProducts;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource\Pages\ViewProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Stock\StockTableAction;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product as Model;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Tapp\FilamentValueRangeFilter\Filters\ValueRangeFilter;
use Throwable;

class ProductResource extends Resource
{


    use ResourcePageHelper;

    /**
     * @var class-string<ProductApplicationService::class>
     */
    protected static string $service = ProductApplicationService::class;

    protected static ?string $createCommand = ProductCreateCommand::class;
    protected static ?string $updateCommand = ProductUpdateCommand::class;
    protected static ?string $deleteCommand = ProductDeleteCommand::class;


    protected static ?string $cluster = \RedJasmine\FilamentProduct\Clusters\Product::class;

    protected static ?string $model = Model::class;

    protected static ?int $navigationSort = 1;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static bool $onlyOwner = true;


    public static function getPages() : array
    {
        return [
            'index'  => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'view'   => ViewProduct::route('/{record}'),
            'edit'   => EditProduct::route('/{record}/edit'),

        ];
    }

    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        $findQuery->include = ['variants', 'extension', 'extendProductGroups', 'tags'];
        return $findQuery;
    }


    public static function callResolveRecord(Model $model) : Model
    {

        foreach ($model->extension->getAttributes() as $key => $value) {
            $model->setAttribute($key, $model->extension->{$key});
        }

        $model->setAttribute('variants', $model->variants->toArray());
        //$model->setAttribute('extend_product_groups', $model->extendProductGroups?->pluck('id')->toArray());
        return $model;
    }

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product.labels.product');
    }

    public static function form(Schema $form) : Schema
    {

        $schema = [
            Tab::make('basic_info')->label(__('red-jasmine-product::product.labels.basic_info'))->columns(1)->schema(static::basicInfoFields()),
            Tab::make('product_attributes')->label(__('red-jasmine-product::product.labels.product_attributes'))->columns(1)->schema(static::productAttributesFields()),
            //Forms\Components\Tabs\Tab::make('specifications')->label(__('red-jasmine-product::product.labels.specifications'))->columns(1)->inlineLabel()->schema(static::specifications()),
            Tab::make('sale_info')->label(__('red-jasmine-product::product.labels.sale_info'))->columns(1)->schema(static::saleInfoFields()),
            Tab::make('after_sales_services')->label(__('red-jasmine-product::product.labels.after_sales_services'))->columns(1)->schema(static::afterSalesServices()),
            Tab::make('description')->label(__('red-jasmine-product::product.labels.description'))->columns(1)->schema(static::descriptionFields()),
            Tab::make('operate')->label(__('red-jasmine-product::product.labels.operate'))->columns(1)->schema(static::operateFields()),
            Tab::make('seo')->label(__('red-jasmine-product::product.labels.seo'))->columns(1)->schema(static::seoFields()),
            Tab::make('shipping')->label(__('red-jasmine-product::product.labels.shipping'))->columns(1)->schema(static::shippingFields()),
            Tab::make('other')->label(__('red-jasmine-product::product.labels.other'))->columns(1)->schema(static::otherFields()),
            //Forms\Components\Tabs\Tab::make('publish')->label(__('red-jasmine-product::product.labels.publish'))->columns(1)->inlineLabel()->schema(static::publishFields()),

        ];

        return $form
            ->components([
                Tabs::make(__('red-jasmine-product::product.labels.product'))->tabs(
                    $schema
                )->persistTabInQueryString(),
                //Forms\Components\Section::make(__('red-jasmine-product::product.labels.product'))->label(__('red-jasmine-product::product.labels.product'))->schema($schema),
            ])
            ->inlineLabel(true)
            ->columns(1);
    }

    public static function basicInfoFields() : array
    {


        return [
            ...static::ownerFormSchemas(),
            SelectTree::make('category_id')
                      ->label(__('red-jasmine-product::product.fields.category_id'))
                      ->relationship('category', 'name', 'parent_id')
//                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->defaultZero()
                      ->default(0), // 设置可选
            ToggleButtons::make('product_type')
                         ->label(__('red-jasmine-product::product.fields.product_type'))
                         ->required()
                         ->inline()
                         ->live()
                         ->default(ProductTypeEnum::PHYSICAL->value)
                         ->useEnum(ProductTypeEnum::class),
            TextInput::make('title')
                     ->label(__('red-jasmine-product::product.fields.title'))
                     ->required()
                     ->maxLength(60),
            TextInput::make('slogan')
                     ->label(__('red-jasmine-product::product.fields.slogan'))
                     ->maxLength(255),


            SelectTree::make('brand_id')
                      ->prefix(__('red-jasmine-product::product.fields.brand_id'))
                      ->label(__('red-jasmine-product::product.fields.brand_id'))
                      ->relationship('brand', 'name', 'parent_id')
//                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->default(0)
                      ->defaultZero()
            ,
            TextInput::make('model_code')
                     ->prefix(__('red-jasmine-product::product.fields.model_code'))
                     ->label(__('red-jasmine-product::product.fields.model_code'))
                     ->maxLength(60),

            SelectTree::make('product_group_id')
                      ->label(__('red-jasmine-product::product.fields.product_group_id'))
                      ->relationship(relationship: 'productGroup',
                          titleAttribute: 'name',
                          parentAttribute: 'parent_id',
                          modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                          ->where('owner_id', $get('owner_id')),
                          modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                              $get('owner_type'))
                                                                                               ->where('owner_id', $get('owner_id'))
                          ,
                      )
//                      ->enableBranchNode()
                      ->parentNullValue(0)
                      ->independent(false)
                      ->storeResults()
                      ->default(0)
                      ->defaultZero()

            ,

            ...static::specifications(),

            ...static::publishFields(),

        ];
    }

    protected static function specifications() : array
    {


        return [
            Section::make('')->schema([
                Toggle::make('has_variants')
                      ->label(__('red-jasmine-product::product.fields.has_variants'))
                      ->required()
                      ->live()
                      ->inline()
                      ->default(0),

                static::saleAttrs()->visible(fn(Get $get) => $get('has_variants'))
                      ->live()
                      ->afterStateUpdated(function ($state, $old, Get $get, Set $set) {

                          try {
                              $saleAttrs = array_values($get('sale_attrs') ?? []);

                              //dd($saleAttrs);
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


                              $oldSku = collect($oldSku)->keyBy('properties_sequence');

                              $variants = [];
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
                                  $variants[]                 = $sku;
                              }

                              $set('variants', $variants);
                          } catch (Throwable $throwable) {
                              $set('variants', []);
                          }
                      }),

                static::variants()
                      ->deletable(false)
                      ->live()
                      ->visible(fn(Get $get) => $get('has_variants')),
            ]),
            Section::make('')->schema([

                //
                Select::make('currency')
                      ->options(static::getService()::getCurrencies())
                    //->formatStateUsing(fn(Currency $state) =>$state->getCode())
                      ->label(__('red-jasmine-product::product.fields.currency'))
                      ->required()
                ,

                TextInput::make('price')
                         ->numeric()
                         ->label(__('red-jasmine-product::product.fields.price'))
                         ->required()
                ,
                TextInput::make('market_price')
                         ->numeric()
                         ->formatStateUsing(fn($state) => $state['formatted'] ?? null)
                         ->label(__('red-jasmine-product::product.fields.market_price'))

                ,
                TextInput::make('cost_price')
                         ->numeric()
                         ->formatStateUsing(fn($state) => $state['formatted'] ?? null)
                         ->label(__('red-jasmine-product::product.fields.cost_price'))
                ,

                Quantity::make('stock')->label(__('red-jasmine-product::product.fields.stock'))
                        ->required()
                        ->default(100)
                        ->integer()
                ->stacked()
                ,
                Quantity::make('safety_stock')
                        ->label(__('red-jasmine-product::product.fields.safety_stock'))
                        ->numeric()
                        ->minValue(0)
                        ->default(0),


            ])
                   ->columns(3)
                   ->hidden(fn(Get $get
                   ) => $get('has_variants')),
        ];
    }

    protected static function saleAttrs() : Repeater
    {
        return Repeater::make('sale_attrs')
                       ->label(__('red-jasmine-product::product.fields.sale_attrs'))
                       ->schema([

                           Select::make('pid')
                                 ->label(__('red-jasmine-product::product.attrs.pid'))
                                 ->live()
                                 ->columns(1)
                                 ->required()
                                 ->columnSpan(1)
                                 ->disabled(fn($state) => $state)
                                 ->dehydrated()
                                 ->options(ProductAttribute::limit(50)->pluck('name', 'id')->toArray())
                                 ->searchable()
                                 ->getSearchResultsUsing(fn(string $search) : array => ProductAttribute::where('name',
                                     'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                 ->getOptionLabelUsing(fn($value, Get $get) : ?string => $get('name')),

                           Repeater::make('values')
                                   ->label(__('red-jasmine-product::product.attrs.values'))
                                   ->hiddenLabel()
                                   ->schema([
                                       Select::make('vid')
                                             ->label(__('red-jasmine-product::product.attrs.vid'))
                                             ->searchable()
                                             ->required()
                                             ->hiddenLabel()
                                             ->options(fn(Get $get) => ProductAttributeValue::where('pid',
                                                 $get('../../pid'))->limit(50)->pluck('name', 'id')->toArray())
                                             ->getSearchResultsUsing(fn(string $search
                                             ) : array => ProductAttributeValue::where('name', 'like',
                                                 "%{$search}%")->limit(50)->pluck('name',
                                                 'id')->toArray())
                                             ->getOptionLabelUsing(fn(
                                                 $value,
                                                 Get $get
                                             ) : ?string => $get('name'))
                                             ->hidden(fn(Get $get
                                             ) => ProductAttribute::find($get('../../pid'))?->type === ProductAttributeTypeEnum::TEXT),


                                       TextInput::make('name')
                                                ->hiddenLabel()
                                                ->maxLength(30)
                                                ->required()
                                                ->hidden(fn(Get $get
                                                ) => ProductAttribute::find($get('../../pid'))?->type !== ProductAttributeTypeEnum::TEXT),


                                       TextInput::make('alias')
                                                ->label(__('red-jasmine-product::product.attrs.alias'))
                                                ->hiddenLabel()
                                                ->placeholder('请输入别名')
                                                ->maxLength(30)
                                                ->hidden(fn(Get $get
                                                ) => ProductAttribute::find($get('../../pid'))?->type === ProductAttributeTypeEnum::TEXT),


                                   ])
                                   ->grid(4)
                                   ->columns()
                                   ->columnSpanFull()
                                   ->minItems(1)
                                   ->reorderable(false)
                                   ->hidden(fn(Get $get) => !$get('pid')),


                       ])
                       ->default([])
                       ->inlineLabel(false)
                       ->columns(4)
                       ->columnSpan('full')
                       ->reorderable(false);
    }

    protected static function variants()
    {

        return Repeater::make('variants')->table(
            [
                Repeater\TableColumn::make('properties_name'),
                Repeater\TableColumn::make('properties_name'),
                Repeater\TableColumn::make('image'),
                Repeater\TableColumn::make('price'),
                Repeater\TableColumn::make('market_price'),
                Repeater\TableColumn::make('cost_price'),
                Repeater\TableColumn::make('stock'),
                Repeater\TableColumn::make('safety_stock'),
                Repeater\TableColumn::make('status'),
                Repeater\TableColumn::make('barcode'),
                Repeater\TableColumn::make('outer_id'),
                Repeater\TableColumn::make('weight'),
                Repeater\TableColumn::make('size'),
                Repeater\TableColumn::make('length'),
                Repeater\TableColumn::make('height'),
                Repeater\TableColumn::make('width'),


            ]


        )->schema([
            Hidden::make('properties_sequence'),
            TextInput::make('properties_name')->readOnly(),
            FileUpload::make('image')->image()
            ,
            TextInput::make('price')
                     ->formatStateUsing(fn($state) => $state['formatted'] ?? null)
                     ->hiddenLabel(),
            TextInput::make('market_price')
                     ->formatStateUsing(fn($state) => $state['formatted'] ?? null)
                     ->hiddenLabel(),
            TextInput::make('cost_price')
                     ->formatStateUsing(fn($state) => $state['formatted'] ?? null)
                     ->hiddenLabel(),
            TextInput::make('stock')->minValue(0)->integer()->required(),
            TextInput::make('safety_stock')->numeric()->default(0),
            Select::make('status')->selectablePlaceholder(false)->required()->default(ProductStatusEnum::ON_SALE->value)->options(ProductStatusEnum::variantStatus()),
            TextInput::make('barcode')->maxLength(32),
            TextInput::make('outer_id')->maxLength(32),
            TextInput::make('weight')->nullable()->numeric()->maxLength(32),
            TextInput::make('size')->nullable()->numeric()->maxLength(32),
            TextInput::make('length')->nullable()->numeric()->maxLength(32),
            TextInput::make('width')->nullable()->numeric()->maxLength(32),
            TextInput::make('height')->nullable()->numeric()->maxLength(32),

        ])->inlineLabel(false)
                       ->columnSpan('full')
                       ->reorderable(false)
                       ->addable(false)
                       ->default([]);

    }

    public static function table(Table $table) : Table
    {

        return $table
            ->deferLoading()
            ->columns([
                ...static::ownerTableColumns(),
                TextColumn::make('id')
                          ->label(__('red-jasmine-product::product.fields.id'))
                          ->copyable()
                          ->sortable(),
                TextColumn::make('title')
                          ->label(__('red-jasmine-product::product.fields.title'))
                          ->copyable()
                          ->searchable(),


                ImageColumn::make('image')->label(__('red-jasmine-product::product.fields.image')),

                TextColumn::make('productGroup.name')
                          ->label(__('red-jasmine-product::product.fields.product_group_id'))
                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('product_type')
                          ->label(__('red-jasmine-product::product.fields.product_type'))
                          ->useEnum(),


                TextColumn::make('barcode')
                          ->label(__('red-jasmine-product::product.fields.barcode'))
                          ->searchable()
                          ->toggleable(true, true),
                TextColumn::make('outer_id')
                          ->label(__('red-jasmine-product::product.fields.outer_id'))
                          ->searchable()
                          ->toggleable(true, true),
                TextColumn::make('has_variants')
                          ->label(__('red-jasmine-product::product.fields.has_variants'))
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('brand.name')
                          ->label(__('red-jasmine-product::product.fields.brand_id'))
                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                          ->label(__('red-jasmine-product::product.fields.category_id'))
                          ->toggleable(isToggledHiddenByDefault: true),


                TextColumn::make('price')
                          ->label(__('red-jasmine-product::product.fields.price'))
                          ->formatStateUsing(fn($state) => $state?->format())
                ,
                //
                TextColumn::make('cost_price')
                          ->label(__('red-jasmine-product::product.fields.cost_price'))
                          ->numeric()
                          ->formatStateUsing(fn($state) => $state?->format())
                          ->toggleable(true, true),
                TextColumn::make('market_price')
                          ->label(__('red-jasmine-product::product.fields.market_price'))
                          ->numeric()
                          ->formatStateUsing(fn($state) => $state?->format())
                          ->toggleable(true, true),
                TextColumn::make('stock')
                          ->label(__('red-jasmine-product::product.fields.stock'))
                          ->numeric()
                          ->sortable(),


                TextColumn::make('status')
                          ->label(__('red-jasmine-product::product.fields.status'))
                          ->useEnum(),

                TextColumn::make('safety_stock')
                          ->label(__('red-jasmine-product::product.fields.safety_stock'))
                          ->numeric()
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sort')
                          ->label(__('red-jasmine-product::product.fields.sort'))
                          ->numeric()
                          ->toggleable(isToggledHiddenByDefault: true)
                          ->sortable(),
                // Tables\Columns\TextColumn::make('unit')
                //                          ->label(__('red-jasmine-product::product.fields.unit'))
                //                          ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('unit_quantity')
                //                          ->label(__('red-jasmine-product::product.fields.unit_quantity'))
                //                          ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sales')
                          ->label(__('red-jasmine-product::product.fields.sales'))
                          ->numeric()
                          ->sortable(),
                TextColumn::make('views')
                          ->label(__('red-jasmine-product::product.fields.views'))
                          ->numeric()
                          ->sortable(),
                TextColumn::make('on_sale_time')
                          ->sortable()
                          ->label(__('red-jasmine-product::product.fields.on_sale_time'))
                          ->dateTime()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('modified_time')
                          ->sortable()
                          ->label(__('red-jasmine-product::product.fields.modified_time'))
                          ->dateTime()->toggleable(isToggledHiddenByDefault: true),


                ...static::operateTableColumns()

            ])
            ->filters([


                SelectFilter::make('status')
                            ->multiple()
                            ->label(__('red-jasmine-product::product.fields.status'))
                            ->options(ProductStatusEnum::options()),

                SelectFilter::make('product_type')
                            ->multiple()
                            ->label(__('red-jasmine-product::product.fields.product_type'))
                            ->options(ProductTypeEnum::options()),

                // Tables\Filters\TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->recordUrl(null)
            ->recordActions([
                EditAction::make(),
                ActionGroup::make(
                    [
                        ViewAction::make(),
                        StockTableAction::make('stock-edit'),
                        DeleteAction::make(),
                        Action::make('listing-removal')
                              ->label(function (Model $record) {
                                  return $record->status !== ProductStatusEnum::ON_SALE ?

                                      __('red-jasmine-product::product.commands.listing')
                                      :
                                      __('red-jasmine-product::product.commands.removal');
                              })
                              ->successNotificationTitle('ok')
                              ->icon(function (Model $record) {
                                  return $record->status !== ProductStatusEnum::ON_SALE ?

                                      FilamentIcon::resolve('product.commands.listing') ?? 'heroicon-o-arrow-up-circle'
                                      :
                                      FilamentIcon::resolve('product.commands.removal') ?? 'heroicon-o-arrow-down-circle';

                              })
                              ->action(function (Model $record, Action $action) {

                                  $status  = ($record->status === ProductStatusEnum::ON_SALE) ? ProductStatusEnum::STOP_SALE : ProductStatusEnum::ON_SALE;
                                  $command = ProductSetStatusCommand::from(['id' => $record->id, 'status' => $status]);
                                  $service = app(static::getService());

                                  $service->setStatus($command);
                                  $action->success();

                              }),

                    ]
                )->visible(static function (Model $record) : bool {
                    if (method_exists($record, 'trashed')) {
                        return !$record->trashed();
                    }
                    return true;

                }),

                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function publishFields() : array
    {

        return [
            ToggleButtons::make('status')
                         ->label(__('red-jasmine-product::product.fields.status'))
                         ->required()
                         ->inline()
                         ->default(ProductStatusEnum::ON_SALE)
                         ->useEnum(ProductStatusEnum::class)
                         ->options(function ($operation, ?Model $record) {
                             if ($operation == 'edit') {
                                 return $record->status->updatingAllowed();
                             }
                             if ($operation == 'create') {
                                 return ProductStatusEnum::creatingAllowed();
                             }
                             return ProductStatusEnum::options();

                         })->live()
            ,
            DateTimePicker::make('start_sale_time')
                          ->nullable()
                          ->label(__('red-jasmine-product::product.fields.start_sale_time'))
                          ->format('Y-m-d\TH:i:sP')
            ,

            DateTimePicker::make('end_sale_time')
                          ->nullable()
                          ->label(__('red-jasmine-product::product.fields.end_sale_time'))
                          ->format('Y-m-d\TH:i:sP'),
        ];

    }

    public static function productAttributesFields() : array
    {
        return [


            SelectTree::make('extend_product_groups')
                      ->label(__('red-jasmine-product::product.fields.extend_groups'))
                      ->relationship(relationship: 'extendProductGroups',
                          titleAttribute: 'name',
                          parentAttribute: 'parent_id',
                          modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                          ->where('owner_id', $get('owner_id')),
                          modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                              $get('owner_type'))
                                                                                               ->where('owner_id', $get('owner_id'))
                          ,
                      )
                      //->loadStateFromRelationshipsUsing(null) // 不进行从关联中获取数据
                      ->dehydrated()
                      ->saveRelationshipsUsing(null) // 不进行自动保存
                      ->parentNullValue(0)
                      ->default([]),

            Select::make('tags')
                  ->multiple()
                  ->label(__('red-jasmine-product::product.fields.tags'))
                  ->relationship(
                      name: 'tags',
                      titleAttribute: 'name',
                      modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                                                                  $get('owner_type'))
                                                                                      ->where('owner_id',
                                                                                          $get('owner_id')),
                  )
                ->pivotData([

                ])
                  //->loadStateFromRelationshipsUsing(null) // 不进行从关联中获取数据
                  //->dehydrated()
                  ->saveRelationshipsUsing(null) // 不进行自动保存
                  ->dehydrated()
                  ->preload()
                  ->default([]),

            Fieldset::make('basicProps')
                    ->label(__('red-jasmine-product::product.fields.basic_attrs'))
                    ->columns(1)->inlineLabel()
                    ->schema([static::basicProps()->hiddenLabel()]),

            Fieldset::make('customizeProps')
                    ->label(__('red-jasmine-product::product.fields.customize_attrs'))
                    ->columns(1)->inlineLabel()
                    ->schema([static::customizeProps()->hiddenLabel()]),
        ];
    }

    protected static function basicProps() : Repeater
    {

        return Repeater::make('basic_attrs')
                       ->label(__('red-jasmine-product::product.fields.basic_attrs'))
                       ->schema([
                           Select::make('pid')
//                                                           ->hiddenLabel()
                                 ->inlineLabel(false)
                                 ->label(__('red-jasmine-product::product.attrs.pid'))
                                 ->live()
                                 ->columnSpan(1)
                                 ->required()
                                 ->disabled(fn($state) => $state)
                                 ->dehydrated()
                                 ->options(ProductAttribute::limit(50)->pluck('name', 'id')->toArray())
                                 ->searchable()
                                 ->getSearchResultsUsing(fn(string $search
                                 ) : array => ProductAttribute::where('name', 'like',
                                     "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                 ->getOptionLabelUsing(fn(
                                     $value,
                                     Get $get
                                 ) : ?string => $get('name')),


                           Repeater::make('values')
                                   ->label(__('red-jasmine-product::product.attrs.values'))
//                                            ->hiddenLabel()
                                   ->inlineLabel(false)
                                   ->schema([
                                       Select::make('vid')
                                             ->label(__('red-jasmine-product::product.attrs.vid'))
                                             ->searchable()
                                             ->hiddenLabel()
                                             ->required()
                                             ->options(fn(Get $get) => ProductAttributeValue::where('pid',
                                                 $get('../../pid'))->limit(50)->pluck('name', 'id')->toArray())
                                             ->getSearchResultsUsing(fn(string $search
                                             ) : array => ProductAttributeValue::when($search,
                                                 function ($query) use ($search) {
                                                     $query->where('name', 'like', "%{$search}%");
                                                 })->limit(20)->pluck('name', 'id')->toArray())
                                             ->getOptionLabelUsing(fn($value, Get $get) : ?string => $get('name'))
                                             ->hidden(fn(Get $get
                                             ) => ProductAttribute::find($get('../../pid'))?->type === ProductAttributeTypeEnum::TEXT),


                                       TextInput::make('name')
                                                ->maxLength(30)
                                                ->hiddenLabel()
                                                ->required()
                                                ->suffix(fn(Get $get
                                                ) => ProductAttribute::find($get('../../pid'))?->unit)
                                                ->inlineLabel()
                                                ->hidden(fn(Get $get
                                                ) => ProductAttribute::find($get('../../pid'))?->type !== ProductAttributeTypeEnum::TEXT),


                                       TextInput::make('alias')
                                                ->placeholder('请输入别名')
                                                ->maxLength(30)
                                                ->hiddenLabel()
                                                ->hidden(fn(Get $get
                                                ) => ProductAttribute::find($get('../../pid'))?->type === ProductAttributeTypeEnum::TEXT),


                                   ])
                                   ->grid(1)
                                   ->columns(2)
                                   ->columnSpan(2)
                                   ->reorderable(false)
                                   ->deletable(fn($state) => count($state) > 1)
                                   ->minItems(1)
                                   ->maxItems(fn(Get $get
                                   ) => ProductAttribute::find($get('pid'))?->is_allow_multiple ? 30 : 1)
                                   ->hidden(fn(Get $get) => !$get('pid')),


                       ])
                       ->default([])
                       ->inlineLabel(false)
                       ->grid(2)
                       ->columns(3)
                       ->columnSpan('full')
                       ->reorderable(false);
    }

    protected static function customizeProps() : Repeater
    {
        return Repeater::make('customize_attrs')
                       ->label(__('red-jasmine-product::product.fields.customize_attrs'))
                       ->schema([
                           Hidden::make('pid')->default(0),
                           Section::make()
                                  ->hiddenLabel()
                                  ->inlineLabel(false)
                                  ->schema(
                                      [
                                          TextInput::make('name')
                                                   ->label(__('red-jasmine-product::product.attrs.pid'))
                                                   ->placeholder(__('red-jasmine-product::product.attrs.pid'))
                                                   ->hiddenLabel()
                                                   ->inlineLabel(false)
                                                   ->required()
                                                   ->maxLength(32),

                                      ]
                                  )
                                  ->columnSpan(2),

                           Repeater::make('values')
                                   ->label(__('red-jasmine-product::product.attrs.values'))
                                   ->hiddenLabel()
                                   ->inlineLabel(false)
                                   ->schema([
                                       Hidden::make('vid')->default(0),
                                       TextInput::make('name')
                                                ->label(__('red-jasmine-product::product.attrs.vid'))
                                                ->placeholder(__('red-jasmine-product::product.attrs.vid'))
                                                ->inlineLabel(false)
                                                ->hiddenLabel()
                                                ->columnSpan(2)
                                                ->required()->maxLength(32),
                                   ])
                                   ->columns(2)
                                   ->columnSpan(2)
                                   ->reorderable(false)
                                   ->deletable(fn($state) => count($state) > 1)
                                   ->minItems(1)
                                   ->maxItems(1)
                           ,


                       ])
                       ->default([])
                       ->inlineLabel(false)
                       ->grid(4)
                       ->columns(4)
                       ->columnSpan('full')
                       ->reorderable(false);
    }

    public static function saleInfoFields() : array
    {
        return [
            ToggleButtons::make('is_pre_sale')
                         ->label(__('red-jasmine-product::product.fields.is_pre_sale'))
                         ->required()
                         ->inline()
                         ->boolean()
                         ->default(false),
            ToggleButtons::make('is_brand_new')
                         ->label(__('red-jasmine-product::product.fields.is_brand_new'))
                         ->required()
                         ->inline()
                         ->boolean()
                         ->default(true)
            ,
            TextInput::make('unit')
                     ->label(__('red-jasmine-product::product.fields.unit'))
                     ->maxLength(32),
            TextInput::make('unit_quantity')
                     ->label(__('red-jasmine-product::product.fields.unit_quantity'))
                     ->numeric()
                     ->default(1)
                     ->minValue(1),

            TextInput::make('outer_id')
                     ->label(__('red-jasmine-product::product.fields.outer_id'))
                     ->maxLength(255),
            TextInput::make('barcode')
                     ->label(__('red-jasmine-product::product.fields.barcode'))
                     ->maxLength(32),


        ];
    }

    public static function afterSalesServices() : array
    {
        $components = [];

        $components[] = CheckboxList::make('services')
            //->multiple()
                                    ->label(__('red-jasmine-product::product.fields.services'))
                                    ->relationship(
                                        name: 'services',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn(Builder $query) => $query->enable()
                                    )
                                    ->columns(6)
                                    // ->loadStateFromRelationshipsUsing(null) // 不进行从关联中获取数据
                                    ->dehydrated()
                                    ->saveRelationshipsUsing(null) // 不进行自动保存
                                    ->dehydrated()
            //->preload()
                                    ->default([]);

        $components[] = Repeater::make('after_sales_services')
                                ->label(__('red-jasmine-product::product.fields.after_sales_services'))
                                ->columns(4)
                                ->grid(1)
                                ->hiddenLabel()
                                ->inlineLabel(false)
                                ->columnSpanFull()
                                ->reorderable(false)
                                ->addable(false)
                                ->deletable(false)
                                ->default(collect(\RedJasmine\Product\Domain\Product\Data\Product::defaultAfterSalesServices())->toArray())
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

                                    TextInput::make('time_limit')
                                             ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit'))
                                             ->visible(fn(Get $get
                                             ) => $get('allow_stage') !== OrderAfterSaleServiceAllowStageEnum::NEVER->value)
                                    ,
                                    Select::make('time_limit_unit')
                                          ->label(__('red-jasmine-ecommerce::ecommerce.fields.after_sales_service.time_limit_unit'))
                                          ->nullable()
                                          ->visible(fn(Get $get
                                          ) => $get('allow_stage') !== OrderAfterSaleServiceAllowStageEnum::NEVER->value)
                                          ->default(OrderAfterSaleServiceTimeUnit::Hour->value)
                                          ->options(OrderAfterSaleServiceTimeUnit::options()),

                                ]);


        return $components;
        foreach (RefundTypeEnum::baseTypes() as $refundType) {

            $components[] = Fieldset::make($refundType->getLabel())
                                    ->inlineLabel()
                                    ->columns(3)
                                    ->schema([
                                        Select::make('after_sales_services.'.$refundType->name().'.stage')
                                              ->selectablePlaceholder(false)
                                              ->default(OrderAfterSaleServiceAllowStageEnum::NEVER->value)
                                              ->options(OrderAfterSaleServiceAllowStageEnum::options()),

                                        TextInput::make('after_sales_services.'.$refundType->name().'.time_limit')
                                                 ->visible(fn(Get $get
                                                 ) => $get('stage') !== OrderAfterSaleServiceAllowStageEnum::NEVER->value)
                                        ,
                                        Select::make('after_sales_services.'.$refundType->name().'.time_limit_unit')
                                              ->nullable()
                                              ->visible(fn(Get $get
                                              ) => $get('stage') !== OrderAfterSaleServiceAllowStageEnum::NEVER->value)
                                              ->default(OrderAfterSaleServiceTimeUnit::Hour->value)
                                              ->options(OrderAfterSaleServiceTimeUnit::options()),

                                    ]);


        }
        return $components;
    }

    public static function descriptionFields() : array
    {
        return [
            FileUpload::make('image')
                      ->fetchFileInformation(false)
                      ->directory('products')
                      ->label(__('red-jasmine-product::product.fields.image'))->image(),
            FileUpload::make('images')->label(__('red-jasmine-product::product.fields.images'))->image()->multiple(),
            FileUpload::make('videos')->label(__('red-jasmine-product::product.fields.videos'))->image()->multiple(),
            RichEditor::make('detail')->label(__('red-jasmine-product::product.fields.detail')),
        ];
    }

    public static function operateFields() : array
    {

        return [

            TextInput::make('tips')->label(__('red-jasmine-product::product.fields.tips'))->maxLength(255),
            TextInput::make('gift_point')
                     ->label(__('red-jasmine-product::product.fields.gift_point'))
                     ->required()
                     ->numeric()
                     ->default(0),
            Radio::make('is_hot')->label(__('red-jasmine-product::product.fields.is_hot'))->required()->boolean()->inline()->default(0),
            Radio::make('is_new')->label(__('red-jasmine-product::product.fields.is_new'))->required()->boolean()->inline()->default(0),
            Radio::make('is_best')->label(__('red-jasmine-product::product.fields.is_best'))->required()->boolean()->inline()->default(0),
            Radio::make('is_benefit')->label(__('red-jasmine-product::product.fields.is_benefit'))->required()->boolean()->inline()->default(0),


            Radio::make('is_alone_order')
                 ->label(__('red-jasmine-product::product.fields.is_alone_order'))
                 ->required()
                 ->boolean()
                 ->inline()
                 ->default(false),
            Quantity::make('min_limit')
                    ->label(__('red-jasmine-product::product.fields.min_limit'))
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1),
            Quantity::make('max_limit')
                    ->label(__('red-jasmine-product::product.fields.max_limit'))
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            Quantity::make('step_limit')
                    ->label(__('red-jasmine-product::product.fields.step_limit'))
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1),
            Quantity::make('vip')
                    ->label(__('red-jasmine-product::product.fields.vip'))
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->default(0),
            ToggleButtons::make('order_quantity_limit_type')
                         ->label(__('red-jasmine-product::product.fields.order_quantity_limit_type'))
                         ->required()
                         ->live()
                         ->grouped()
                         ->useEnum(OrderQuantityLimitTypeEnum::class)
                         ->default(OrderQuantityLimitTypeEnum::UNLIMITED->value),

            Quantity::make('order_quantity_limit_num')
                    ->label(__('red-jasmine-product::product.fields.order_quantity_limit_num'))
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->suffix('件')
                    ->required(fn(Get $get
                    ) => $get('order_quantity_limit_type') === OrderQuantityLimitTypeEnum::UNLIMITED->value)
                    ->hidden(fn(Get $get
                    ) => $get('order_quantity_limit_type') === OrderQuantityLimitTypeEnum::UNLIMITED->value),

        ];
    }

    public static function seoFields() : array
    {
        return [
            TextInput::make('meta_title')->label(__('red-jasmine-product::product.fields.meta_title'))->maxLength(255),
            TextInput::make('meta_keywords')->label(__('red-jasmine-product::product.fields.meta_keywords'))->maxLength(255),
            TextInput::make('meta_description')->label(__('red-jasmine-product::product.fields.meta_description'))->maxLength(255),
        ];
    }

    public static function shippingFields() : array
    {
        return [

            TextInput::make('delivery_time')
                     ->label(__('red-jasmine-product::product.fields.delivery_time'))
                     ->required()
                     ->numeric()
                     ->default(0),


            ToggleButtons::make('delivery_methods')
                         ->inline()
                         ->multiple()
                         ->label(__('red-jasmine-product::product.fields.delivery_methods'))
                         ->icons(ShippingTypeEnum::icons())
                         ->required(fn(Get $get
                         ) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                         ->visible(fn(Get $get
                         ) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                         ->options(ShippingTypeEnum::deliveryMethods()),

            ToggleButtons::make('freight_payer')
                         ->label(__('red-jasmine-product::product.fields.freight_payer'))
                         ->required()
                         ->default(FreightPayerEnum::SELLER)
                         ->useEnum(FreightPayerEnum::class)
                         ->live()
                         ->visible(fn(Get $get
                         ) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                         ->inline(),
            Select::make('freight_template_id')
                  ->relationship('freightTemplate', 'name', modifyQueryUsing: function ($query, Get $get) {
                      return $query->where('owner_type', $get('owner_type'))->where('owner_id', $get('owner_id'));
                  }
                  )
                  ->formatStateUsing(fn($state) => (string) $state)
                  ->required(fn(Get $get, $state) => $get('freight_payer') === FreightPayerEnum::BUYER)
                  ->visible(fn(Get $get
                  ) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                  ->label(__('red-jasmine-product::product.fields.freight_template_id')),


        ];
    }

    public static function otherFields() : array
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
                    ->schema(

                        [
                            Repeater::make('form.schemas')

//                                                     ->inlineLabel()
                                    ->label(__('red-jasmine-product::product.fields.form'))
                                    ->default(null)
                                    ->schema([

                                        TextInput::make('label')->inlineLabel()->required()->maxLength(32),
                                        TextInput::make('name')->inlineLabel()->required()->maxLength(32),
                                        Select::make('type')->inlineLabel()->required()
                                              ->default(FieldTypeEnum::TEXT)
                                              ->useEnum(FieldTypeEnum::class),
                                        Checkbox::make('is_required')->inlineLabel(),
                                        TextInput::make('default')->inlineLabel(),
                                        TextInput::make('placeholder')->inlineLabel(),
                                        TextInput::make('hint')->inlineLabel(),

                                        Repeater::make('options')->default(null)->schema([
                                            TextInput::make('label')
                                                     ->hiddenLabel()
//                                                                                                                                                 ->inlineLabel()
                                                     ->required()->maxLength(32),
                                            TextInput::make('value')
                                                     ->hiddenLabel()
//                                                                                                                                                 ->inlineLabel()
                                                     ->required()->maxLength(128),
                                        ])
                                                ->columns(2)
                                                ->grid(5)
                                                ->columnSpan('full'),

                                    ])
                                    ->inlineLabel(false)
                                    ->hiddenLabel()
                                    ->columns(7)
                                    ->columnSpan('full')


                        ]


                    ),


            TextInput::make('remarks')
                     ->label(__('red-jasmine-product::product.fields.remarks'))
                     ->maxLength(255),

            TextInput::make('sort')
                     ->label(__('red-jasmine-product::product.fields.sort'))
                     ->required()
                     ->numeric()
                     ->minValue(0)
                     ->default(0),
            ...static::operateFormSchemas()
        ];
    }


}
