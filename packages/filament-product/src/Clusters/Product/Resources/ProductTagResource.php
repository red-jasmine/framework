<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\Pages;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\RelationManagers;
use RedJasmine\Product\Application\Tag\Services\ProductTagCommandService;
use RedJasmine\Product\Application\Tag\Services\ProductTagQueryService;
use RedJasmine\Product\Application\Tag\UserCases\Commands\ProductTagCreateCommand;
use RedJasmine\Product\Application\Tag\UserCases\Commands\ProductTagDeleteCommand;
use RedJasmine\Product\Application\Tag\UserCases\Commands\ProductTagUpdateCommand;
use RedJasmine\Product\Domain\Tag\Models\Enums\TagStatusEnum;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductTagResource extends Resource
{
    protected static ?string $model = ProductTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster        = Product::class;
    protected static ?int    $navigationSort = 5;

    use ResourcePageHelper;

    protected static ?string $commandService = ProductTagCommandService::class;
    protected static ?string $queryService   = ProductTagQueryService::class;
    protected static ?string $createCommand  = ProductTagCreateCommand::class;
    protected static ?string $updateCommand  = ProductTagUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductTagDeleteCommand::class;
    protected static bool    $onlyOwner      = true;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-tag.labels.tag');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->columns(1)
            ->schema([
                         ...static::ownerFormSchemas(),
                         Forms\Components\TextInput::make('name')
                                                   ->label(__('red-jasmine-product::product-tag.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')
                                                   ->label(__('red-jasmine-product::product-tag.fields.description'))
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('icon')
                                                   ->label(__('red-jasmine-product::product-tag.fields.icon'))
                                                   ->maxLength(255),
                         Forms\Components\ColorPicker::make('color')
                                                     ->label(__('red-jasmine-product::product-tag.fields.color'))
                         ,
                         Forms\Components\TextInput::make('cluster')
                                                   ->label(__('red-jasmine-product::product-tag.fields.cluster'))
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('sort')
                                                   ->label(__('red-jasmine-product::product-tag.fields.sort'))
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0),
                         Forms\Components\Radio::make('is_show')
                                               ->label(__('red-jasmine-product::product-tag.fields.is_show'))
                                               ->required()
                                               ->boolean()->inline()
                                               ->default(1),
                         Forms\Components\Radio::make('is_public')
                                               ->label(__('red-jasmine-product::product-tag.fields.is_public'))
                                               ->required()
                                               ->boolean()
                                               ->inline()
                                               ->default(0),
                         Forms\Components\ToggleButtons::make('status')
                                                       ->label(__('red-jasmine-product::product-tag.fields.status'))
                                                       ->required()
                                                       ->grouped()
                                                       ->default(TagStatusEnum::ENABLE)
                                                       ->useEnum(TagStatusEnum::class),
                         ...static::operateFormSchemas(),
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          ...static::ownerTableColumns(),
                          Tables\Columns\TextColumn::make('name')
                                                   ->label(__('red-jasmine-product::product-tag.fields.name'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('description')
                                                   ->label(__('red-jasmine-product::product-tag.fields.description'))
                                                   ->searchable(),
                          Tables\Columns\TextColumn::make('icon')
                                                   ->label(__('red-jasmine-product::product-tag.fields.icon'))
                          ,
                          Tables\Columns\ColorColumn::make('color')
                                                    ->label(__('red-jasmine-product::product-tag.fields.color'))
                          ,
                          Tables\Columns\TextColumn::make('cluster')
                                                   ->label(__('red-jasmine-product::product-tag.fields.cluster'))
                                                   ->searchable(),

                          Tables\Columns\IconColumn::make('is_show')
                                                   ->label(__('red-jasmine-product::product-tag.fields.is_show'))
                                                   ->boolean()
                          ,
                          Tables\Columns\IconColumn::make('is_public')
                                                   ->label(__('red-jasmine-product::product-tag.fields.is_public'))
                                                   ->boolean()
                          ,
                          Tables\Columns\TextColumn::make('sort')
                                                   ->label(__('red-jasmine-product::product-tag.fields.sort'))
                                                   ->numeric()
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine-product::product-tag.fields.status'))
                                                   ->useEnum(),

                          ...static::operateTableColumns()
                      ])
            ->filters([
                          Tables\Filters\SelectFilter::make('status')
                                                     ->label(__('red-jasmine-product::product-tag.fields.status'))
                                                     ->options(TagStatusEnum::options()),
                          Tables\Filters\TernaryFilter::make('is_show')
                                                      ->label(__('red-jasmine-product::product-tag.fields.is_show'))
                                                      ->boolean(true),
                          Tables\Filters\TernaryFilter::make('is_public')
                                                      ->label(__('red-jasmine-product::product-tag.fields.is_public'))
                                                      ->boolean(true),

                      ])
            ->actions([
                          Tables\Actions\EditAction::make(),
                      ])
            ->bulkActions([
                              Tables\Actions\BulkActionGroup::make([
                                                                       Tables\Actions\DeleteBulkAction::make(),
                                                                       Tables\Actions\ForceDeleteBulkAction::make(),
                                                                       Tables\Actions\RestoreBulkAction::make(),
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
            'index'  => Pages\ListProductTags::route('/'),
            'create' => Pages\CreateProductTag::route('/create'),
            'edit'   => Pages\EditProductTag::route('/{record}/edit'),
        ];
    }


}
