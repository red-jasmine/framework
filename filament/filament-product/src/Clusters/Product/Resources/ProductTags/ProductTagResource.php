<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags;

use BackedEnum;
use Filament\Resources\Resource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags\Pages\CreateProductTag;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags\Pages\EditProductTag;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductTags\Pages\ListProductTags;
use RedJasmine\Product\Application\Tag\Services\Commands\ProductTagCreateCommand;
use RedJasmine\Product\Application\Tag\Services\Commands\ProductTagDeleteCommand;
use RedJasmine\Product\Application\Tag\Services\Commands\ProductTagUpdateCommand;
use RedJasmine\Product\Application\Tag\Services\ProductTagApplicationService;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;

class ProductTagResource extends Resource
{
    protected static ?string $model = ProductTag::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static ?string                $cluster        = Product::class;
    protected static ?int                    $navigationSort = 5;

    use ResourcePageHelper;

    protected static ?string $service = ProductTagApplicationService::class;

    protected static ?string $createCommand = ProductTagCreateCommand::class;
    protected static ?string $updateCommand = ProductTagUpdateCommand::class;
    protected static ?string $deleteCommand = ProductTagDeleteCommand::class;
    protected static bool    $onlyOwner     = true;
    protected static bool $isTranslatable = true;

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
