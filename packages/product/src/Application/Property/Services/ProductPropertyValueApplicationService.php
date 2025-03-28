<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyGroupRulePipeline;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyRulePipeline;
use RedJasmine\Product\Application\Property\Services\Pipelines\PropertyValueUpdatePipeline;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueReadRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method ProductPropertyValue create(ProductPropertyValueCreateCommand $command)
 * @method void update(ProductPropertyValueUpdateCommand $command)
 */
class ProductPropertyValueApplicationService extends ApplicationService
{


    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product-property-value';


    protected static string $modelClass = ProductPropertyValue::class;

    public function __construct(
        public ProductPropertyValueRepositoryInterface $repository,
        public ProductPropertyValueReadRepositoryInterface $readRepository,
    ) {

    }

    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByNameInProperty($data->pid, $data->name)) {
            throw new ProductPropertyException('名称已存在');
            return $model;
        }
        return parent::newModel($data);
    }

    protected function hooks() : array
    {
        return [

            'create' => [
                ProductPropertyRulePipeline::class,
                ProductPropertyGroupRulePipeline::class,
            ],
            'update' => [
                //ProductPropertyRulePipeline::class,
                PropertyValueUpdatePipeline::class,
                ProductPropertyGroupRulePipeline::class,
            ],
        ];
    }


}
