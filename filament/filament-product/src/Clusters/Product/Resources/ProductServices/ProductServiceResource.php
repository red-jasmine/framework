<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices;

use BackedEnum;
use Filament\Resources\Resource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages\CreateProductService;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages\EditProductService;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductServices\Pages\ListProductServices;
use RedJasmine\Product\Application\Service\Services\Commands\ProductServiceCreateCommand;
use RedJasmine\Product\Application\Service\Services\Commands\ProductServiceDeleteCommand;
use RedJasmine\Product\Application\Service\Services\Commands\ProductServiceUpdateCommand;
use RedJasmine\Product\Application\Service\Services\ProductServiceApplicationService;
use RedJasmine\Product\Domain\Service\Models\ProductService;

class ProductServiceResource extends Resource
{
    protected static ?string $model = ProductService::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $cluster = Product::class;


    protected static ?int $navigationSort = 6;

    use ResourcePageHelper;

    protected static ?string $service       = ProductServiceApplicationService::class;
    protected static ?string $createCommand = ProductServiceCreateCommand::class;
    protected static ?string $updateCommand = ProductServiceUpdateCommand::class;
    protected static ?string $deleteCommand = ProductServiceDeleteCommand::class;
    protected static bool $isTranslatable = true;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-service.labels.service');
    }

    use CategoryResource;

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product.labels.brand-category-service');
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
            'index'  => ListProductServices::route('/'),
            'create' => CreateProductService::route('/create'),
            'edit'   => EditProductService::route('/{record}/edit'),
        ];
    }

}
