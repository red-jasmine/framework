<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands;

use App\Filament\Clusters\Product\Resources\BrandResource\RelationManagers;
use Filament\Resources\Resource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\Pages\CreateBrand;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\Pages\EditBrand;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\Brands\Pages\ListBrands;
use RedJasmine\Product\Application\Brand\Services\BrandApplicationService;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandDeleteCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandUpdateCommand;
use RedJasmine\Product\Domain\Brand\Models\ProductBrand;

class BrandResource extends Resource
{
    protected static ?int    $navigationSort = 2;
    protected static ?string $cluster        = Product::class;
    protected static ?string $model          = ProductBrand::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-swatch';

    use ResourcePageHelper;

    protected static ?string $service        = BrandApplicationService::class;
    protected static ?string $commandService = BrandApplicationService::class;
    protected static ?string $createCommand  = BrandCreateCommand::class;
    protected static ?string $updateCommand  = BrandUpdateCommand::class;
    protected static ?string $deleteCommand  = BrandDeleteCommand::class;
    protected static bool    $isTranslatable = true;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::brand.labels.brand');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product.labels.brand-category-service');
    }

    use CategoryResource;

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
