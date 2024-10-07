<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductCategoryResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentCore\FilamentResource\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategoryResource\Pages\CreateProductCategory;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategoryResource\Pages\EditProductCategory;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategoryResource\Pages\ListProductCategories;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;

class ProductCategoryResource extends Resource
{
    protected static ?int    $navigationSort = 3;
    protected static ?string $cluster = Product::class;
    protected static ?string $model   = ProductCategory::class;

    use ResourcePageHelper;

    protected static ?string $commandService = ProductCategoryCommandService::class;
    protected static ?string $queryService   = ProductCategoryQueryService::class;
    protected static ?string $createCommand  = ProductCategoryCreateCommand::class;
    protected static ?string $updateCommand  = ProductCategoryUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductCategoryDeleteCommand::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function getModelLabel() : string
    {
        return __('red-jasmine.product::product-category.labels.product-category');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([

                         SelectTree::make('parent_id')
                             ->label(__('red-jasmine.product::product-category.fields.parent_id'))
                                   ->relationship(relationship: 'parent', titleAttribute: 'name', parentAttribute: 'parent_id',
                                       modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                       modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                   )
                             // ->required()
                                   ->searchable()
                                   ->default(0)
                                   ->enableBranchNode()
                                   ->parentNullValue(0)
                         ,
                         Forms\Components\TextInput::make('name')
                                                   ->label(__('red-jasmine.product::product-category.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')
                                                   ->label(__('red-jasmine.product::product-category.fields.description'))->maxLength(255),
                         Forms\Components\FileUpload::make('image')
                                                    ->label(__('red-jasmine.product::product-category.fields.image'))
                                                    ->image(),
                         Forms\Components\TextInput::make('group_name')
                                                   ->label(__('red-jasmine.product::product-category.fields.group_name'))
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('sort')
                                                   ->label(__('red-jasmine.product::product-category.fields.sort'))
                                                   ->required()
                                                   ->default(0),
                         Forms\Components\Toggle::make('is_leaf')
                                                ->label(__('red-jasmine.product::product-category.fields.is_leaf'))
                                                ->required()
                                                ->default(0),
                         Forms\Components\Toggle::make('is_show')
                                                ->label(__('red-jasmine.product::product-category.fields.is_show'))
                                                ->required()
                                                ->default(1),
                         Forms\Components\Radio::make('status')
                                               ->label(__('red-jasmine.product::product-category.fields.status'))
                                               ->required()
                                               ->default(CategoryStatusEnum::ENABLE)
                                               ->options(CategoryStatusEnum::options()),
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label('ID')
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('parent.name')
                                                   ->label(__('red-jasmine.product::product-category.fields.parent_id'))
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('name')
                                                   ->label(__('red-jasmine.product::product-category.fields.name'))
                                                   ->searchable(),
                          Tables\Columns\ImageColumn::make('image')
                                                    ->label(__('red-jasmine.product::product-category.fields.image'))
                          ,
                          Tables\Columns\TextColumn::make('group_name')
                                                   ->label(__('red-jasmine.product::product-category.fields.group_name'))
                                                   ->searchable(),

                          Tables\Columns\IconColumn::make('is_leaf')
                                                   ->label(__('red-jasmine.product::product-category.fields.is_leaf'))
                                                   ->boolean(),
                          Tables\Columns\IconColumn::make('is_show')
                                                   ->label(__('red-jasmine.product::product-category.fields.is_show'))
                                                   ->boolean(),
                          Tables\Columns\TextColumn::make('sort')
                                                   ->label(__('red-jasmine.product::product-category.fields.sort'))
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine.product::product-category.fields.status'))
                                                   ->badge()->formatStateUsing(fn($state) => $state->label())->color(fn($state) => $state->color()),

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
            'index'  => ListProductCategories::route('/'),
            'create' => CreateProductCategory::route('/create'),
            'edit'   => EditProductCategory::route('/{record}/edit'),
        ];
    }
}
