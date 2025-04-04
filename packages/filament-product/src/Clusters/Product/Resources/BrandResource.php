<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\BrandResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource\Pages\CreateBrand;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource\Pages\EditBrand;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource\Pages\ListBrands;
use RedJasmine\Product\Application\Brand\Services\BrandApplicationService;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandDeleteCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandUpdateCommand;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;

class BrandResource extends Resource
{
    protected static ?int    $navigationSort = 2;
    protected static ?string $cluster        = Product::class;
    protected static ?string $model          = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    use ResourcePageHelper;

    protected static ?string $service        = BrandApplicationService::class;
    protected static ?string $commandService = BrandApplicationService::class;
    protected static ?string $createCommand  = BrandCreateCommand::class;
    protected static ?string $updateCommand  = BrandUpdateCommand::class;
    protected static ?string $deleteCommand  = BrandDeleteCommand::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::brand.labels.brand');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product.labels.brand-category-service');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                SelectTree::make('parent_id')
                          ->label(__('red-jasmine-product::brand.fields.parent.name'))
                          ->relationship(
                              relationship: 'parent',
                              titleAttribute: 'name',
                              parentAttribute: 'parent_id',
                              modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(),
                                  fn($query, $value) => $query->where('id', '<>', $value)),
                              modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(),
                                  fn($query, $value) => $query->where('id', '<>', $value)),
                          )
                    // ->required()
                          ->searchable()
                          ->defaultZero()
                          ->enableBranchNode()
                          ->parentNullValue(0)
                ,
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-product::brand.fields.name'))
                                          ->required(),
                Forms\Components\TextInput::make('english_name')
                                          ->label(__('red-jasmine-product::brand.fields.english_name'))
                ,
                Forms\Components\TextInput::make('initial')
                                          ->maxLength(1)
                                          ->label(__('red-jasmine-product::brand.fields.initial'))
                ,
                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-product::brand.fields.description'))
                                          ->maxLength(255),
                Forms\Components\FileUpload::make('logo')
                                           ->label(__('red-jasmine-product::brand.fields.logo'))
                                           ->image(),
                Forms\Components\Radio::make('is_show')
                                      ->label(__('red-jasmine-product::brand.fields.is_show'))
                                      ->boolean()
                                      ->inline()
                                      ->default(true),
                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine-product::brand.fields.sort'))
                                          ->default(0)->required()->numeric()->minValue(0),

                Forms\Components\ToggleButtons::make('status')->label(__('red-jasmine-product::brand.fields.status'))
                                              ->inline()
                                              ->grouped()
                                              ->required()
                                              ->default(BrandStatusEnum::ENABLE)
                                              ->useEnum(BrandStatusEnum::class)
                ,

                ...static::operateFormSchemas()
            ])->columns(1);
    }

    public static function table(Table $table) : Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->copyable(),
                Tables\Columns\TextColumn::make('parent.name')->label(__('red-jasmine-product::brand.fields.parent.name')),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-product::brand.fields.name'))
                                         ->searchable()->copyable(),

                Tables\Columns\TextColumn::make('initial')
                                         ->label(__('red-jasmine-product::brand.fields.initial')),
                Tables\Columns\TextColumn::make('english_name')
                                         ->label(__('red-jasmine-product::brand.fields.english_name')),
                Tables\Columns\ImageColumn::make('logo')->label(__('red-jasmine-product::brand.fields.logo')),
                Tables\Columns\IconColumn::make('is_show')->label(__('red-jasmine-product::brand.fields.is_show'))->boolean(),
                Tables\Columns\TextColumn::make('sort')->label(__('red-jasmine-product::brand.fields.sort'))->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('red-jasmine-product::brand.fields.status'))
                                         ->useEnum(),


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
            'index'  => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit'   => EditBrand::route('/{record}/edit'),
        ];
    }
}
