<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyGroupRulePipeline;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyPipeline;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Handlers\CreateCommandHandler;
use RedJasmine\Support\Application\Handlers\DeleteCommandHandler;
use RedJasmine\Support\Application\Handlers\UpdateCommandHandler;

/**
 * @method ProductProperty create(ProductPropertyCreateCommand $command)
 * @method void update(ProductPropertyUpdateCommand $command)
 */
class ProductPropertyApplicationService extends ApplicationService
{
    protected static string $modelClass = ProductProperty::class;


    public static string $hookNamePrefix = 'product.application.product-property.command';


    public function __construct(
        public ProductPropertyRepositoryInterface $repository,
    ) {

    }

    public function newModel($data = null) : Model
    {
        if ($this->repository->findByName($data->name)) {
            throw new ProductPropertyException('名称已存在');
        }
        return parent::newModel($data);
    }

    protected function hooks() : array
    {
        return [
            'create' => [
                ProductPropertyGroupRulePipeline::class,
            ],
            'update' => [
                ProductPropertyGroupRulePipeline::class,
            ],
        ];
    }


}
