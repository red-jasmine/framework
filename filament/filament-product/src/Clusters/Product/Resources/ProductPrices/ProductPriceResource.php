<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Pages\CreateProductPrice;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Pages\EditProductPrice;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Pages\ListProductPrices;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Schemas\ProductPriceForm;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductPrices\Tables\ProductPriceTable;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceCreateCommand;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceDeleteCommand;
use RedJasmine\Product\Application\Price\Services\Commands\ProductPriceUpdateCommand;
use RedJasmine\Product\Application\Price\Services\PriceApplicationService;
use RedJasmine\Product\Domain\Price\Models\ProductPrice as Model;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class ProductPriceResource extends Resource
{
    use ResourcePageHelper;

    /**
     * @var class-string<PriceApplicationService::class>
     */
    protected static string $service = PriceApplicationService::class;

    protected static ?string $createCommand = ProductPriceCreateCommand::class;
    protected static ?string $updateCommand = ProductPriceUpdateCommand::class;
    protected static ?string $deleteCommand = ProductPriceDeleteCommand::class;

    protected static ?string $cluster = \RedJasmine\FilamentProduct\Clusters\Product::class;

    protected static ?string $model = Model::class;

    protected static ?int $navigationSort = 6;

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-currency-dollar';

    protected static bool $onlyOwner = false; // 价格通过 product 关联 owner，不需要单独检查

    public static function getModelLabel(): string
    {
        return __('red-jasmine-product::product-price.labels.price');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductPrices::route('/'),
            'create' => CreateProductPrice::route('/create'),
            'edit' => EditProductPrice::route('/{record}/edit'),
        ];
    }

    public static function callFindQuery(FindQuery $findQuery): FindQuery
    {
        $findQuery->include = ['product'];
        return $findQuery;
    }


    public static function form(Schema $schema): Schema
    {
        return ProductPriceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductPriceTable::configure($table);
    }
}

