<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\BrandResource\RelationManagers;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentCore\Resources\Schemas\CategoryForm;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource\Pages\CreateBrand;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource\Pages\EditBrand;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\BrandResource\Pages\ListBrands;
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
