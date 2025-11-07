<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductResource\RelationManagers;
use BackedEnum;
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
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
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
use RedJasmine\FilamentRegion\Forms\Components\CountrySelect;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValidateService;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;
use RedJasmine\Product\Domain\Product\Data\Product;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Product as Model;
use RedJasmine\Product\Domain\Product\Models\ProductVariant;
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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

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
        //dd($model->variants->first()->toArray());
        //$model->setAttribute('variants', $model->variants->toArray());

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
                                 ->relationship(relationship: 'productGroup',
                                     titleAttribute: 'name',
                                     parentAttribute: 'parent_id',
                                     modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                         $get('owner_type'))
                                                                                                     ->where('owner_id', $get('owner_id')),
                                     modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                         $get('owner_type'))
                                                                                                          ->where('owner_id',
                                                                                                              $get('owner_id'))
                                     ,
                                 )
                                 ->parentNullValue(0)
                                 ->independent(false)
                                 ->storeResults()
                                 ->default(0)
                                 ->defaultZero()
                                 ->helperText('选择商品分组，便于商品管理')
                                 ->searchable(),
                   ]),

            ...static::specifications(),

            ...static::publishFields(),

        ];
    }

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
                             ->inline()
                             ->default(0)
                             ->onIcon('heroicon-o-check-circle')
                             ->offIcon('heroicon-o-x-circle')
                             ->onColor('success')
                             ->offColor('gray')
                             ->helperText('开启后可以设置商品的多个规格（如颜色、尺码等）'),


                       static::saleAttrs()
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

                                     $variants = [];
                                     foreach ($crossJoin as $properties => $propertyName) {

                                         $sku               = $oldSku[$properties] ?? [
                                             'attrs_sequence' => $properties,
                                             'attrs_name'     => $propertyName,
                                             'image'          => null,
                                             'price'          => null,
                                             'market_price'   => null,
                                             'cost_price'     => null,
                                             'stock'          => null,
                                             'safety_stock'   => 0,
                                             'status'         => ProductStatusEnum::AVAILABLE->value,

                                         ];
                                         $sku['attrs_name'] = $propertyName;
                                         $variants[]        = $sku;
                                     }

                                     $set('variants', $variants, shouldCallUpdatedHooks: true);
                                 } catch (Throwable $throwable) {
                                     $set('variants', [], shouldCallUpdatedHooks: true);
                                 }
                             }),


                       static::variants()
                             ->deletable(false)
                             ->live(),
                   ]),

        ];
    }

    protected static function saleAttrs() : Repeater
    {
        return Repeater::make('sale_attrs')
                       ->table([
                           Repeater\TableColumn::make('属性名称')->width('200px'),
                           Repeater\TableColumn::make('属性值')->width('800px'),

                       ])
                       ->label(__('red-jasmine-product::product.fields.sale_attrs'))
                       ->schema([
                           Hidden::make('aid')
                                 ->label(__('red-jasmine-product::product.attrs.aid'))
                                 ->required()
                                 ->dehydrated()
                           ,

                           TextInput::make('name')
                                    ->label(__('red-jasmine-product::product.attrs.aid'))
                                    ->readOnly()
                                    ->required()
                                    ->columnSpan(1),

                           Repeater::make('values')
                                   ->table([
                                       Repeater\TableColumn::make('属性值'),
                                       Repeater\TableColumn::make('别名'),
                                   ])
                                   ->label(__('red-jasmine-product::product.attrs.values'))
                                   ->schema([
                                       Hidden::make('vid')
                                             ->label(__('red-jasmine-product::product.attrs.alias'))
                                       ,
                                       TextInput::make('name')
                                                ->label(__('red-jasmine-product::product.attrs.alias'))
                                                ->readOnly(),
                                       TextInput::make('alias')
                                                ->label(__('red-jasmine-product::product.attrs.alias'))
                                                ->hiddenLabel()
                                                ->placeholder('请输入别名')
                                                ->maxLength(30)
                                   ])
                                   ->hiddenLabel()
                                   ->addAction(function (Action $action, Get $get, Set $set, $state) {

                                       $action->icon(Heroicon::Envelope)
                                              ->schema([
                                                  CheckboxList::make('vid')
                                                              ->columns(6)
                                                              ->label(__('red-jasmine-product::product.attrs.vid'))
                                                              ->required()
                                                              ->hiddenLabel()
                                                              ->options(fn() => ProductAttributeValue::where('aid', $get('aid'))
                                                                                                     ->pluck('name', 'id')
                                                                                                     ->toArray())
                                                  ,
                                              ])
                                              ->action(function (array $data, array $arguments, Repeater $component) use (
                                                  $set,
                                                  $get,
                                                  $state
                                              ) : void {
                                                  $vidList = $data['vid'] ?? [];
                                                  $vidList = ProductAttributeValue::select(['name', 'id'])->find($vidList);
                                                  $items   = [];
                                                  foreach ($vidList as $attributeValue) {
                                                      $items[] = [
                                                          'vid'   => (string) $attributeValue->id,
                                                          'alias' => '',
                                                          'name'  => $attributeValue->name,
                                                      ];
                                                  }
                                                  $values = $get('values') ?? [];
                                                  // 确保是数组类型
                                                  if (!is_array($values)) {
                                                      $values = [];
                                                  }
                                                  array_push($values, ...$items);
                                                  // 重新索引并过滤空值
                                                  $values = array_values(array_filter($values, function ($item) {
                                                      return is_array($item) && isset($item['vid']) && !empty($item['vid']);
                                                  }));

                                                  $set('values', $values, shouldCallUpdatedHooks: true);
                                              });

                                   })
                                   ->columnSpanFull()
                                   ->deletable(true)
                                   ->minItems(1)
                                   ->grid(4)
                                   ->default([])
                                   ->reorderable(false)

                           ,


                       ])
                       ->addAction(function (Action $action, Get $get, Set $set) {
                           $action->icon(Heroicon::Plus)
                                  ->label('快速添加销售属性')
                                  ->schema([
                                      Select::make('aid')
                                            ->label(__('red-jasmine-product::product.attrs.aid'))
                                            ->live()
                                            ->required()
                                            ->options(ProductAttribute::limit(10)->pluck('name', 'id')->toArray())
                                            ->searchable()
                                            ->getSearchResultsUsing(fn(string $search) : array => ProductAttribute::where('name', 'like',
                                                "%{$search}%")),

                                      CheckboxList::make('vids')
                                                  ->label(__('red-jasmine-product::product.attrs.values'))
                                                  ->columns(6)
                                                  ->required()
                                                  ->options(fn(Get $get) => $get('aid')
                                                      ? ProductAttributeValue::where('aid', $get('aid'))
                                                                             ->pluck('name', 'id')
                                                                             ->toArray()
                                                      : []
                                                  )
                                                  ->hidden(fn(Get $get) => !$get('aid')),
                                  ])
                                  ->action(function (array $data) use ($get, $set) : void {
                                      $aid  = $data['aid'] ?? null;
                                      $vids = $data['vids'] ?? [];

                                      if ($aid && !empty($vids)) {
                                          $attribute = ProductAttribute::find($aid);
                                          if ($attribute) {
                                              // 获取选中的属性值
                                              $attributeValues = ProductAttributeValue::select(['id', 'name'])
                                                                                      ->whereIn('id', $vids)
                                                                                      ->get();

                                              // 构建属性值列表
                                              $values = [];
                                              foreach ($attributeValues as $attrValue) {
                                                  $values[] = [
                                                      'vid'   => (string) $attrValue->id,
                                                      'name'  => $attrValue->name,
                                                      'alias' => '',
                                                  ];
                                              }

                                              // 添加到销售属性列表
                                              $saleAttrs = $get('sale_attrs') ?? [];
                                              // 确保是数组类型
                                              if (!is_array($saleAttrs)) {
                                                  $saleAttrs = [];
                                              }
                                              $saleAttrs[] = [
                                                  'aid'    => (string) $attribute->id,
                                                  'name'   => $attribute->name,
                                                  'values' => $values,
                                              ];
                                              // 重新索引并过滤空值
                                              $saleAttrs = array_values(array_filter($saleAttrs, function ($item) {
                                                  return is_array($item) && isset($item['aid']) && !empty($item['aid']);
                                              }));
                                              $set('sale_attrs', $saleAttrs, shouldCallUpdatedHooks: true);
                                          }
                                      }
                                  });
                       })
                       ->deletable(true)
                       ->default([])
                       ->inlineLabel(false)
                       ->columnSpan('full')
                       ->reorderable(false);
    }

    public static function table(Table $table) : Table
    {

        return $table
            ->deferLoading()
            ->columns([
                ...static::ownerTableColumns(),

                // 商品信息组
                ImageColumn::make('image')
                           ->label(__('red-jasmine-product::product.fields.image'))
                           ->circular()
                           ->size(60)
                           ->defaultImageUrl(url('/images/placeholder.png')),

                TextColumn::make('id')
                          ->label(__('red-jasmine-product::product.fields.id'))
                          ->copyable()
                          ->sortable()
                          ->searchable()
                          ->icon('heroicon-o-identification')
                          ->color('gray')
                          ->size('xs'),

                TextColumn::make('title')
                          ->label(__('red-jasmine-product::product.fields.title'))
                          ->copyable()
                          ->searchable()
                          ->limit(30)
                          ->tooltip(fn($record) => $record->title)
                          ->weight('bold')
                          ->description(fn($record) => $record->slogan),

                // 分类品牌
                TextColumn::make('category.name')
                          ->label(__('red-jasmine-product::product.fields.category_id'))
                          ->badge()
                          ->color('info')
                          ->icon('heroicon-o-tag')
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('brand.name')
                          ->label(__('red-jasmine-product::product.fields.brand_id'))
                          ->badge()
                          ->color('warning')
                          ->icon('heroicon-o-star')
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('productGroup.name')
                          ->label(__('red-jasmine-product::product.fields.product_group_id'))
                          ->badge()
                          ->color('primary')
                          ->toggleable(isToggledHiddenByDefault: true),

                // 商品类型
                TextColumn::make('product_type')
                          ->label(__('red-jasmine-product::product.fields.product_type'))
                          ->badge()
                          ->useEnum(),
                // 规格信息
                IconColumn::make('has_variants')
                          ->label(__('red-jasmine-product::product.fields.has_variants'))
                          ->boolean()
                          ->trueIcon('heroicon-o-squares-2x2')
                          ->falseIcon('heroicon-o-square-2-stack')
                          ->trueColor('success')
                          ->falseColor('gray')
                          ->toggleable(isToggledHiddenByDefault: true),

                // 价格信息
                TextColumn::make('price')
                          ->label(__('red-jasmine-product::product.fields.price'))
                          ->formatStateUsing(fn($state) => $state?->format())
                          ->color('danger')
                          ->weight('bold')
                          ->sortable(),

                TextColumn::make('market_price')
                          ->label(__('red-jasmine-product::product.fields.market_price'))
                          ->formatStateUsing(fn($state) => $state?->format())
                          ->color('success')
                          ->weight('bold')
                          ->sortable()
                          ->toggleable(true, true),
                TextColumn::make('cost_price')
                          ->label(__('red-jasmine-product::product.fields.cost_price'))
                          ->formatStateUsing(fn($state) => $state?->format())
                          ->color('danger')
                          ->weight('bold')
                          ->sortable()
                          ->toggleable(true, true),
                // 库存信息
                TextColumn::make('stock')
                          ->label(__('red-jasmine-product::product.fields.stock'))
                          ->numeric()
                          ->sortable()
                          ->icon('heroicon-o-archive-box')
                          ->color(fn($state, $record) => match (true) {
                              $state <= 0 => 'danger',
                              $state <= $record->safety_stock => 'warning',
                              default => 'success'
                          })
                          ->badge()
                          ->description(fn($record) => $record->safety_stock > 0 ? "安全库存: {$record->safety_stock}" : null),

                // 状态
                TextColumn::make('status')
                          ->label(__('red-jasmine-product::product.fields.status'))
                          ->badge()
                          ->useEnum(),

                // 销售数据
                TextColumn::make('sales')
                          ->label(__('red-jasmine-product::product.fields.sales'))
                          ->numeric()
                          ->sortable()
                          ->icon('heroicon-o-chart-bar')
                          ->color('success')
                          ->toggleable(),

                TextColumn::make('views')
                          ->label(__('red-jasmine-product::product.fields.views'))
                          ->numeric()
                          ->sortable()
                          ->icon('heroicon-o-eye')
                          ->color('info')
                          ->toggleable(),


                TextColumn::make('spu')
                          ->label(__('red-jasmine-product::product.fields.spu'))
                          ->searchable()
                          ->copyable()
                          ->icon('heroicon-o-hashtag')
                          ->toggleable(true, true),

                // 时间信息
                TextColumn::make('available_at')
                          ->sortable()
                          ->label(__('red-jasmine-product::product.fields.available_at'))
                          ->dateTime('Y-m-d H:i')
                          ->icon('heroicon-o-calendar')
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('modified_at')
                          ->sortable()
                          ->label(__('red-jasmine-product::product.fields.modified_time'))
                          ->dateTime('Y-m-d H:i')
                          ->since()
                          ->icon('heroicon-o-clock')
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sort')
                          ->label(__('red-jasmine-product::product.fields.sort'))
                          ->numeric()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('safety_stock')
                          ->label(__('red-jasmine-product::product.fields.safety_stock'))
                          ->numeric()
                          ->toggleable(isToggledHiddenByDefault: true),

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
                                  return $record->status !== ProductStatusEnum::AVAILABLE ?

                                      __('red-jasmine-product::product.commands.listing')
                                      :
                                      __('red-jasmine-product::product.commands.removal');
                              })
                              ->successNotificationTitle('ok')
                              ->icon(function (Model $record) {
                                  return $record->status !== ProductStatusEnum::AVAILABLE ?

                                      FilamentIcon::resolve('product.commands.listing') ?? 'heroicon-o-arrow-up-circle'
                                      :
                                      FilamentIcon::resolve('product.commands.removal') ?? 'heroicon-o-arrow-down-circle';

                              })
                              ->action(function (Model $record, Action $action) {

                                  $status  = ($record->status === ProductStatusEnum::AVAILABLE) ? ProductStatusEnum::UNAVAILABLE : ProductStatusEnum::AVAILABLE;
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

    protected static function variants()
    {

        return Repeater::make('variants')
                       ->relationship('variants')
                       ->dehydrated()
                       ->saveRelationshipsUsing(null)
                       ->label(__('red-jasmine-product::product.fields.variants'))
                       ->table(
                           [
                               // Repeater\TableColumn::make('attrs_sequence'),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.image'))->width('100px'),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.attrs_name')),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.sku')),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.price'))->markAsRequired(),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.market_price')),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.cost_price')),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.stock'))->markAsRequired(),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.safety_stock')),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.package_unit')),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.package_quantity'))->markAsRequired(),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.barcode')),
                               Repeater\TableColumn::make(__('red-jasmine-product::product.fields.status'))->markAsRequired()->width('120px'),
                               // Repeater\TableColumn::make(__('red-jasmine-product::product.fields.weight')),
                               // Repeater\TableColumn::make(__('red-jasmine-product::product.fields.size')),
                               // Repeater\TableColumn::make(__('red-jasmine-product::product.fields.length')),
                               // Repeater\TableColumn::make(__('red-jasmine-product::product.fields.height')),
                               // Repeater\TableColumn::make(__('red-jasmine-product::product.fields.width')),

                           ]

                       )
                       ->schema([
                           FileUpload::make('image')->image()->panelLayout('compact'),
                           TextInput::make('attrs_name')->readOnly(),
                           TextInput::make('sku'),
                           TextInput::make('price')->required()->formatStateUsing(fn($state) => $state['formatted'] ?? null),
                           TextInput::make('market_price')->formatStateUsing(fn($state) => $state['formatted'] ?? null),
                           TextInput::make('cost_price')->formatStateUsing(fn($state) => $state['formatted'] ?? null),
                           TextInput::make('stock')->minValue(0)->integer()->required(),
                           TextInput::make('safety_stock')->numeric()->default(0),
                           TextInput::make('package_unit')
                                    ->label(__('red-jasmine-product::product.fields.package_unit'))
                                    ->maxLength(32)
                                    ->placeholder('SKU的包装单位:件/个/套/箱')
                           ,
                           TextInput::make('package_quantity')
                                    ->label(__('red-jasmine-product::product.fields.package_quantity'))
                                    ->integer()
                                    ->default(1)
                                    ->minValue(1)
                                    ->placeholder('每个包装单位包含的数量'),
                           TextInput::make('barcode')->maxLength(32),
                           Select::make('status')->selectablePlaceholder(false)->required()->default(ProductStatusEnum::AVAILABLE->value)->options(ProductStatusEnum::variantStatus()),
                           Hidden::make('attrs_sequence'),

                           // TextInput::make('weight')->nullable()->numeric()->maxLength(32),
                           // TextInput::make('size')->nullable()->numeric()->maxLength(32),
                           // TextInput::make('length')->nullable()->numeric()->maxLength(32),
                           // TextInput::make('width')->nullable()->numeric()->maxLength(32),
                           // TextInput::make('height')->nullable()->numeric()->maxLength(32),

                       ])
                       ->inlineLabel(false)
                       ->columnSpan('full')
                       ->reorderable(false)
                       ->addable(false);

    }

    public static function publishFields() : array
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

    public static function productAttributesFields() : array
    {
        return [
            Section::make('商品分类')
                   ->description('设置商品的扩展分组和标签')
                   ->icon('heroicon-o-folder-open')
                   ->columns(1)
                   ->schema([
                       SelectTree::make('extend_product_groups')
                                 ->label(__('red-jasmine-product::product.fields.extend_groups'))
                                 ->relationship(relationship: 'extendProductGroups',
                                     titleAttribute: 'name',
                                     parentAttribute: 'parent_id',
                                     modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                         $get('owner_type'))
                                                                                                     ->where('owner_id', $get('owner_id')),
                                     modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                         $get('owner_type'))
                                                                                                          ->where('owner_id',
                                                                                                              $get('owner_id'))
                                     ,
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
                                 modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query->where('owner_type',
                                     $get('owner_type'))
                                                                                                 ->where('owner_id',
                                                                                                     $get('owner_id')),
                             )
                             ->pivotData([])
                             ->saveRelationshipsUsing(null)
                             ->dehydrated()
                             ->preload()
                             ->default([])
                             ->helperText('为商品添加标签，便于分类和搜索')
                             ->searchable(),
                   ]),

            Section::make('商品属性')
                   ->description('设置商品的基础属性和自定义属性')
                   ->icon('heroicon-o-list-bullet')
                   ->collapsible()
                   ->schema([

                       CountrySelect::make('origin_country')
                                    ->label(__('red-jasmine-product::product.fields.origin_country')),

                       Fieldset::make('basicProps')
                               ->label(__('red-jasmine-product::product.fields.basic_attrs'))
                               ->columns(1)
                               ->inlineLabel()
                               ->schema([static::basicProps()->hiddenLabel()]),

                       Fieldset::make('customizeProps')
                               ->label(__('red-jasmine-product::product.fields.customize_attrs'))
                               ->columns(1)
                               ->inlineLabel()
                               ->schema([static::customizeProps()->hiddenLabel()]),
                   ]),
        ];
    }

    protected static function basicProps() : Repeater
    {

        return Repeater::make('basic_attrs')
                       ->label(__('red-jasmine-product::product.fields.basic_attrs'))
                       ->schema([
                           Select::make('aid')
//                                                           ->hiddenLabel()
                                 ->inlineLabel(false)
                                 ->label(__('red-jasmine-product::product.attrs.aid'))
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
                                             ->options(fn(Get $get) => ProductAttributeValue::where('aid',
                                                 $get('../../aid'))->limit(50)->pluck('name', 'id')->toArray())
                                             ->getSearchResultsUsing(fn(string $search
                                             ) : array => ProductAttributeValue::when($search,
                                                 function ($query) use ($search) {
                                                     $query->where('name', 'like', "%{$search}%");
                                                 })->limit(20)->pluck('name', 'id')->toArray())
                                             ->getOptionLabelUsing(fn($value, Get $get) : ?string => $get('name'))
                                             ->hidden(fn(Get $get
                                             ) => ProductAttribute::find($get('../../aid'))?->type === ProductAttributeTypeEnum::TEXT),


                                       TextInput::make('name')
                                                ->maxLength(30)
                                                ->hiddenLabel()
                                                ->required()
                                                ->suffix(fn(Get $get
                                                ) => ProductAttribute::find($get('../../aid'))?->unit)
                                                ->inlineLabel()
                                                ->hidden(fn(Get $get
                                                ) => ProductAttribute::find($get('../../aid'))?->type !== ProductAttributeTypeEnum::TEXT),


                                       TextInput::make('alias')
                                                ->placeholder('请输入别名')
                                                ->maxLength(30)
                                                ->hiddenLabel()
                                                ->hidden(fn(Get $get
                                                ) => ProductAttribute::find($get('../../aid'))?->type === ProductAttributeTypeEnum::TEXT),


                                   ])
                                   ->grid(1)
                                   ->columns(2)
                                   ->columnSpan(2)
                                   ->reorderable(false)
                                   ->deletable(fn($state) => count($state) > 1)
                                   ->minItems(1)
                                   ->maxItems(fn(Get $get
                                   ) => ProductAttribute::find($get('aid'))?->is_allow_multiple ? 30 : 1)
                                   ->hidden(fn(Get $get) => !$get('aid')),


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
                           Hidden::make('aid')->default(0),
                           Section::make()
                                  ->hiddenLabel()
                                  ->inlineLabel(false)
                                  ->schema(
                                      [
                                          TextInput::make('name')
                                                   ->label(__('red-jasmine-product::product.attrs.aid'))
                                                   ->placeholder(__('red-jasmine-product::product.attrs.aid'))
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

    public static function operateFields() : array
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

    public static function seoFields() : array
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

                       TextInput::make('meta_description')
                                ->label(__('red-jasmine-product::product.fields.meta_description'))
                                ->maxLength(255)
                                ->placeholder('商品简短描述')
                                ->helperText('显示在搜索结果中的描述，建议120-160字')
                                ->prefixIcon('heroicon-o-document-text'),
                   ]),
        ];
    }

    public static function shippingFields() : array
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

                       ToggleButtons::make('delivery_methods')
                                    ->label(__('red-jasmine-product::product.fields.delivery_methods'))
                                    ->inline()
                                    ->multiple()
                                    ->icons(ShippingTypeEnum::icons())
                                    ->options(ShippingTypeEnum::deliveryMethods())
                                    ->required(fn(Get $get
                                    ) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                                    ->visible(fn(Get $get
                                    ) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                                    ->helperText('选择支持的配送方式')
                                    ->columnSpanFull(),

                       ToggleButtons::make('freight_payer')
                                    ->label(__('red-jasmine-product::product.fields.freight_payer'))
                                    ->required()
                                    ->default(FreightPayerEnum::SELLER)
                                    ->useEnum(FreightPayerEnum::class)
                                    ->live()
                                    ->inline()
                                    ->icons(FreightPayerEnum::icons())
                                    ->visible(fn(Get $get
                                    ) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                                    ->helperText('选择谁承担运费')
                                    ->columnSpanFull(),

                       Select::make('freight_template_id')
                             ->label(__('red-jasmine-product::product.fields.freight_template_id'))
                             ->relationship('freightTemplate', 'name', modifyQueryUsing: function ($query, Get $get) {
                                 return $query->where('owner_type', $get('owner_type'))->where('owner_id', $get('owner_id'));
                             })
                             ->formatStateUsing(fn($state) => (string) $state)
                             ->required(fn(Get $get, $state) => $get('freight_payer') === FreightPayerEnum::BUYER)
                             ->visible(fn(Get $get) => ProductTypeEnum::tryFrom($get('product_type')?->value) === ProductTypeEnum::PHYSICAL)
                             ->helperText('选择运费模板，买家承担运费时必选')
                             ->prefixIcon('heroicon-o-document-text')
                             ->searchable()
                             ->preload(),
                   ]),
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
