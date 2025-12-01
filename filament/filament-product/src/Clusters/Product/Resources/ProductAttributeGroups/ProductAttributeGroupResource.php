<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups;

use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductAttributeGroupResource\RelationManagers;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentCore\Resources\Schemas\Operators;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\CreateProductAttributeGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\EditProductAttributeGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\ListProductAttributeGroups;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductAttributeGroups\Pages\ViewProductAttributeGroup;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupDeleteCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeGroupApplicationService;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeGroup;

class ProductAttributeGroupResource extends Resource
{
    protected static ?string $cluster = Product::class;
    protected static ?string $model   = ProductAttributeGroup::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-list-bullet';


    use ResourcePageHelper;

    protected static ?string $service        = ProductAttributeGroupApplicationService::class;
    protected static ?string $createCommand  = ProductAttributeGroupCreateCommand::class;
    protected static ?string $updateCommand  = ProductAttributeGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductAttributeGroupDeleteCommand::class;
    protected static ?int    $navigationSort = 6;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-attribute-group.labels.product-attribute-group');
    }

    public static function getNavigationGroup() : ?string
    {
        return __('red-jasmine-product::product-attribute.labels.attribute');
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
            'index'  => ListProductAttributeGroups::route('/'),
            'create' => CreateProductAttributeGroup::route('/create'),
            'view'   => ViewProductAttributeGroup::route('/{record}'),
            'edit'   => EditProductAttributeGroup::route('/{record}/edit'),
        ];
    }


}
