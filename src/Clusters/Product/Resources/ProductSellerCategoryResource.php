<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductSellerCategoryResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource\Pages\CreateProductSellerCategory;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource\Pages\EditProductSellerCategory;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductSellerCategoryResource\Pages\ListProductSellerCategories;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;

class ProductSellerCategoryResource extends Resource
{

    use ResourcePageHelper;
    protected static ?int    $navigationSort = 4;
    protected static ?string $cluster        = Product::class;
    protected static ?string $model          = ProductSellerCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $commandService = ProductSellerCategoryCommandService::class;



    protected static ?string $queryService  = ProductSellerCategoryQueryService::class;
    protected static ?string $createCommand = ProductSellerCategoryCreateCommand::class;
    protected static ?string $updateCommand = ProductSellerCategoryUpdateCommand::class;
    protected static ?string $deleteCommand = ProductSellerCategoryDeleteCommand::class;
    protected static bool    $onlyOwner     = true;

    public function __construct()
    {
    }

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-seller-category.labels.product-seller-category');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                        ...static::ownerFormSchemas(),

                         SelectTree::make('parent_id')
                                   ->label(__('red-jasmine-product::product-seller-category.fields.parent_id'))
                                   ->relationship(relationship: 'parent',
                                       titleAttribute:          'name',
                                       parentAttribute:         'parent_id',
                                       modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                             ->where('owner_id', $get('owner_id'))
                                                                                                             ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                       modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->where('owner_type', $get('owner_type'))
                                                                                                                  ->where('owner_id', $get('owner_id'))
                                                                                                                  ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                   )
                             // ->required()
                                   ->searchable()
                                   ->default(0)
                                   ->enableBranchNode()
                                   ->parentNullValue(0),
                         Forms\Components\TextInput::make('name')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.description'))
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('group_name')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.group_name'))
                                                   ->maxLength(255),
                         Forms\Components\FileUpload::make('image')
                                                    ->label(__('red-jasmine-product::product-seller-category.fields.image'))
                                                    ->image(),
                         Forms\Components\TextInput::make('sort')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.sort'))
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0),
                         Forms\Components\Radio::make('is_leaf')
                                               ->label(__('red-jasmine-product::product-seller-category.fields.is_leaf'))
                                               ->default(false)->boolean()->inline()->inlineLabel(false)->required(),
                         Forms\Components\Radio::make('is_show')
                                               ->label(__('red-jasmine-product::product-seller-category.fields.is_show'))
                                               ->default(true)->boolean()->inline()->inlineLabel(false)->required(),

                         Forms\Components\Radio::make('status')
                                               ->label(__('red-jasmine-product::product-seller-category.fields.status'))
                                               ->required()
                                               ->default(CategoryStatusEnum::ENABLE)
                                               ->options(CategoryStatusEnum::options())
                                               ->inline()->inlineLabel(false)->required(),


                        ... static::operateFormSchemas()
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.id'))
                                                   ->label('ID')
                                                   ->sortable(),
                        ...static::ownerTableColumns(),
                          Tables\Columns\TextColumn::make('parent.name')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.parent_id'))
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('name')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.name'))
                                                   ->searchable(),

                          Tables\Columns\TextColumn::make('group_name')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.group_name'))
                                                   ->searchable(),
                          Tables\Columns\ImageColumn::make('image')
                                                    ->label(__('red-jasmine-product::product-seller-category.fields.image'))
                          ,
                          Tables\Columns\TextColumn::make('sort')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.sort'))
                                                   ->sortable(),
                          Tables\Columns\IconColumn::make('is_leaf')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.is_leaf'))
                                                   ->boolean(),
                          Tables\Columns\IconColumn::make('is_allow_alias')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.is_allow_alias'))
                                                   ->boolean(),
                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine-product::product-seller-category.fields.status'))
                                                   ->enum(),
                          ...static::operateTableColumns()

                      ])
            ->filters([
                          //
                      ])
            ->actions([
                          Tables\Actions\EditAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                   ]),
                          ]);
    }

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index'  => ListProductSellerCategories::route('/'),
            'create' => CreateProductSellerCategory::route('/create'),
            'edit'   => EditProductSellerCategory::route('/{record}/edit'),
        ];
    }
}
