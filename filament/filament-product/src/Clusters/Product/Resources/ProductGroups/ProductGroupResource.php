<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups;

use App\Filament\Clusters\Product\Resources\ProductGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductGroupResource\RelationManagers;
use Filament\Resources\Resource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\Pages\CreateProductGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\Pages\EditProductGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroups\Pages\ListProductGroups;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;

class ProductGroupResource extends Resource
{



    protected static ?int    $navigationSort = 4;
    protected static ?string $cluster        = Product::class;
    protected static ?string $model          = ProductGroup::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-group';

    use ResourcePageHelper;
    protected static ?string $service = ProductGroupApplicationService::class;
    protected static ?string $commandService = ProductGroupApplicationService::class;

    protected static ?string $createCommand  = ProductGroupCreateCommand::class;
    protected static ?string $updateCommand  = ProductGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductGroupDeleteCommand::class;
    protected static bool    $onlyOwner      = true;
    protected static bool $isTranslatable = true;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-group.labels.group');
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
            'index'  => ListProductGroups::route('/'),
            'create' => CreateProductGroup::route('/create'),
            'edit'   => EditProductGroup::route('/{record}/edit'),
        ];
    }
}
