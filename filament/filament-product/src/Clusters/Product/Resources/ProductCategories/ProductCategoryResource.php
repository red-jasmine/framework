<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories;

use App\Filament\Clusters\Product\Resources\ProductCategoryResource\RelationManagers;
use Filament\Resources\Resource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\Pages\CreateProductCategory;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\Pages\EditProductCategory;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductCategories\Pages\ListProductCategories;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Application\Category\Services\ProductCategoryApplicationService;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;

class ProductCategoryResource extends Resource
{
    protected static ?int    $navigationSort = 3;
    protected static ?string $cluster        = Product::class;
    protected static ?string $model          = ProductCategory::class;

    use ResourcePageHelper;


    public function __construct()
    {
    }

    protected static ?string $service        = ProductCategoryApplicationService::class;
    protected static ?string $createCommand  = ProductCategoryCreateCommand::class;
    protected static ?string $updateCommand  = ProductCategoryUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductCategoryDeleteCommand::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-category.labels.product-category');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product.labels.brand-category-service');
    }

    use CategoryResource;

    public static  bool $isTranslatable = true;

    public static function getPages() : array
    {
        return [
            'index'  => ListProductCategories::route('/'),
            'create' => CreateProductCategory::route('/create'),
            'edit'   => EditProductCategory::route('/{record}/edit'),
        ];
    }
}
