<?php

namespace RedJasmine\Product\Application\Product\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Product\Services\CommandHandlers\ProductCreateCommandHandler;
use RedJasmine\Product\Application\Product\Services\CommandHandlers\ProductUpdateCommandHandler;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductInfo;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;


/**
 * @see ProductCreateCommandHandler::handle()
 * @method Product create(ProductCreateCommand $command)
 * @see ProductUpdateCommandHandler::handle()
 * @method void update(ProductUpdateCommand $command)
 */
class ProductCommandService extends ApplicationCommandService
{

    protected static string $modelClass = Product::class;

    public function __construct(
        protected ProductRepositoryInterface $repository
    )
    {
        parent::__construct();
    }

    protected static $macros = [
        'create' => ProductCreateCommandHandler::class,
        'update' => ProductUpdateCommandHandler::class,
    ];


    public function newModel($data = null) : Model
    {
        $model = parent::newModel();
        $model->setRelation('info', new ProductInfo());
        $model->setRelation('skus', Collection::make([]));
        return $model;
    }


}
