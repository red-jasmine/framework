<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Pages\CreateProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Pages\EditProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Pages\ListProducts;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Pages\ViewProduct;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Schemas\ProductForm;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Tables\ProductTable;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product as Model;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;


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
        $findQuery->include = ['variants','media','variants.media','variants.stocks', 'extension','translations', 'extendProductGroups', 'tags'];
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

    public static function getModelLabel(): string
    {
        return __('red-jasmine-product::product.labels.product');
    }

    public static function form(Schema $form): Schema
    {
        return ProductForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return ProductTable::configure($table);
    }
}
