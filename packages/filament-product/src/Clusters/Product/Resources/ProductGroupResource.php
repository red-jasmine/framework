<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductGroupResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Filters\TreeParent;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroupResource\Pages\CreateProductGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroupResource\Pages\EditProductGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroupResource\Pages\ListProductGroups;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Domain\Group\Models\Enums\GroupStatusEnum;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;

class ProductGroupResource extends Resource
{



    protected static ?int    $navigationSort = 4;
    protected static ?string $cluster        = Product::class;
    protected static ?string $model          = ProductGroup::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    use ResourcePageHelper;
    protected static ?string $service = ProductGroupApplicationService::class;
    protected static ?string $commandService = ProductGroupApplicationService::class;

    protected static ?string $createCommand  = ProductGroupCreateCommand::class;
    protected static ?string $updateCommand  = ProductGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductGroupDeleteCommand::class;
    protected static bool    $onlyOwner      = true;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-group.labels.group');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->columns(1)
            ->schema([
                         ...static::ownerFormSchemas(),

                         SelectTree::make('parent_id')
                                   ->label(__('red-jasmine-product::product-group.fields.parent_id'))
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
                                                   ->label(__('red-jasmine-product::product-group.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('description')
                                                   ->label(__('red-jasmine-product::product-group.fields.description'))
                                                   ->maxLength(255),
                         Forms\Components\TextInput::make('cluster')
                                                   ->label(__('red-jasmine-product::product-group.fields.cluster'))
                                                   ->maxLength(255),
                         Forms\Components\FileUpload::make('image')
                                                    ->label(__('red-jasmine-product::product-group.fields.image'))
                                                    ->image(),
                         Forms\Components\TextInput::make('sort')
                                                   ->label(__('red-jasmine-product::product-group.fields.sort'))
                                                   ->required()
                                                   ->numeric()
                                                   ->default(0),
                         Forms\Components\Radio::make('is_leaf')
                                               ->label(__('red-jasmine-product::product-group.fields.is_leaf'))
                                               ->default(false)->boolean()->inline()->required(),
                         Forms\Components\Radio::make('is_show')
                                               ->label(__('red-jasmine-product::product-group.fields.is_show'))
                                               ->default(true)->boolean()->inline()->required(),

                         Forms\Components\ToggleButtons::make('status')
                                               ->label(__('red-jasmine-product::product-group.fields.status'))
                                               ->required()
                                                ->grouped()
                                               ->default(GroupStatusEnum::ENABLE)
                                               ->useEnum(GroupStatusEnum::class)
                                               ,


                         ... static::operateFormSchemas()
                     ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('id')
                                                   ->label(__('red-jasmine-product::product-group.fields.id'))
                                                   ->label('ID')
                                                   ->sortable(),
                          ...static::ownerTableColumns(),
                          Tables\Columns\TextColumn::make('parent.name')
                                                   ->label(__('red-jasmine-product::product-group.fields.parent_id'))
                                                   ->sortable(),
                          Tables\Columns\TextColumn::make('name')
                                                   ->label(__('red-jasmine-product::product-group.fields.name'))
                                                   ->searchable(),

                          Tables\Columns\TextColumn::make('cluster')
                                                   ->label(__('red-jasmine-product::product-group.fields.cluster'))
                                                   ->searchable(),
                          Tables\Columns\ImageColumn::make('image')
                                                    ->label(__('red-jasmine-product::product-group.fields.image'))
                          ,
                          Tables\Columns\TextColumn::make('sort')
                                                   ->label(__('red-jasmine-product::product-group.fields.sort'))
                                                   ->sortable(),
                          Tables\Columns\IconColumn::make('is_leaf')
                                                   ->label(__('red-jasmine-product::product-group.fields.is_leaf'))
                                                   ->boolean(),

                          Tables\Columns\TextColumn::make('status')
                                                   ->label(__('red-jasmine-product::product-group.fields.status'))
                                                   ->useEnum(),
                          ...static::operateTableColumns()

                      ])
            ->filters([
                          TreeParent::make('parents')->label(__('red-jasmine-product::product-group.fields.parent_id')),
                          Tables\Filters\SelectFilter::make('status')
                                                     ->label(__('red-jasmine-product::product-group.fields.status'))
                                                     ->options(GroupStatusEnum::options()),
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
            'index'  => ListProductGroups::route('/'),
            'create' => CreateProductGroup::route('/create'),
            'edit'   => EditProductGroup::route('/{record}/edit'),
        ];
    }
}
