<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups;

use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\RelationManagers;
use BackedEnum;
use Filament\Resources\Resource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\CreateProductAttributeGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\EditProductAttributeGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\ListProductAttributeGroups;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\ViewProductAttributeGroup;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeGroupApplicationService;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeGroupData;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeGroup;

class ProductAttributeGroupResource extends Resource
{
    protected static ?string $cluster = Product::class;
    protected static ?string $model   = ProductAttributeGroup::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int    $navigationSort = 6;

    use ResourcePageHelper;
    use CategoryResource;
    protected static ?string $service        = ProductAttributeGroupApplicationService::class;
    protected static ?string $dataClass      = ProductAttributeGroupData::class;

    protected static bool    $isTranslatable = true;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-attribute-group.labels.product-attribute-group');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-attribute.labels.attribute');
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
            'index'  => ListProductAttributeGroups::route('/'),
            'create' => CreateProductAttributeGroup::route('/create'),
            'view'   => ViewProductAttributeGroup::route('/{record}'),
            'edit'   => EditProductAttributeGroup::route('/{record}/edit'),
        ];
    }


}
