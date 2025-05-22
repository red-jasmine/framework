<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources;

use App\Filament\Clusters\Product\Resources\ProductGroupResource\Pages;
use App\Filament\Clusters\Product\Resources\ProductGroupResource\RelationManagers;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Filters\TreeParent;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroupResource\Pages\CreateProductGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroupResource\Pages\EditProductGroup;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductGroupResource\Pages\ListProductGroups;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Domain\Group\Models\Enums\GroupStatusEnum;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;

class ProductGroupResource extends Resource
{



    protected static ?int    $navigationSort = 4;
    protected static ?string $cluster        = Product::class;
    protected static ?string $model          = ProductGroup::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    use ResourcePageHelper;
    protected static ?string $service = ProductGroupApplicationService::class;
    protected static ?string $commandService = ProductGroupApplicationService::class;

    protected static ?string $createCommand  = ProductGroupCreateCommand::class;
    protected static ?string $updateCommand  = ProductGroupUpdateCommand::class;
    protected static ?string $deleteCommand  = ProductGroupDeleteCommand::class;
    protected static bool    $onlyOwner      = true;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-product::product-group.labels.group');
    }

    public static function form(Form $form) : Form
    {
        return static::categoryForm($form, static::$onlyOwner ?? false);
    }

    public static function table(Table $table) : Table
    {
        return static::categoryTable($table, static::$onlyOwner ?? false);
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
            'index'  => ListProductGroups::route('/'),
            'create' => CreateProductGroup::route('/create'),
            'edit'   => EditProductGroup::route('/{record}/edit'),
        ];
    }
}
