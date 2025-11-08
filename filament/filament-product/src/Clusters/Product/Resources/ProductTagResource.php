<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentCore\Resources\Schemas\CategoryForm;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\Pages\CreateProductTag;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\Pages\EditProductTag;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\Pages\ListProductTags;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTagResource\RelationManagers;
use RedJasmine\Product\Application\Tag\Services\Commands\ProductTagCreateCommand;
use RedJasmine\Product\Application\Tag\Services\Commands\ProductTagDeleteCommand;
use RedJasmine\Product\Application\Tag\Services\Commands\ProductTagUpdateCommand;
use RedJasmine\Product\Application\Tag\Services\ProductTagApplicationService;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;

class ProductTagResource extends Resource
{
    protected static ?string $model = ProductTag::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';
    protected static ?string $cluster        = Product::class;
    protected static ?int    $navigationSort = 5;

    use ResourcePageHelper;

    protected static ?string $service = ProductTagApplicationService::class;

    protected static ?string $createCommand = ProductTagCreateCommand::class;
    protected static ?string $updateCommand = ProductTagUpdateCommand::class;
    protected static ?string $deleteCommand = ProductTagDeleteCommand::class;
    protected static bool    $onlyOwner     = true;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-tag.labels.tag');
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
            'index'  => ListProductTags::route('/'),
            'create' => CreateProductTag::route('/create'),
            'edit'   => EditProductTag::route('/{record}/edit'),
        ];
    }


}
