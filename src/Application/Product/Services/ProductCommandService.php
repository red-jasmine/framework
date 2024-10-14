<?php

namespace RedJasmine\Product\Application\Product\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Product\Services\CommandHandlers\ProductCreateCommandHandler;
use RedJasmine\Product\Application\Product\Services\CommandHandlers\ProductDeleteCommandHandler;
use RedJasmine\Product\Application\Product\Services\CommandHandlers\ProductSetStatusCommandHandler;
use RedJasmine\Product\Application\Product\Services\CommandHandlers\ProductUpdateCommandHandler;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductSetStatusCommand;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductUpdateCommand;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Models\ProductInfo;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @see ProductCreateCommandHandler::handle()
 * @method Product create(ProductCreateCommand $command)
 * @see ProductUpdateCommandHandler::handle()
 * @method void update(ProductUpdateCommand $command)
 * @method void setStatus(ProductSetStatusCommand $command)
 */
class ProductCommandService extends ApplicationCommandService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product.command';


    protected static string $modelClass = Product::class;
    protected static        $macros     = [
        'create'    => ProductCreateCommandHandler::class,
        'update'    => ProductUpdateCommandHandler::class,
        'delete'    => ProductDeleteCommandHandler::class,
        'setStatus' => ProductSetStatusCommandHandler::class,
    ];

    public function __construct(
        protected ProductRepositoryInterface $repository
    )
    {

    }

    public function newModel($data = null) : Model
    {
        // TODO 需要移动到模型中

        $model = parent::newModel();
        $model->setRelation('info', new ProductInfo());
        $model->setRelation('skus', Collection::make());
        $model->setRelation('sellerExtendCategories', Collection::make());
        $model->setRelation('tags', Collection::make());
        return $model;
    }


}
