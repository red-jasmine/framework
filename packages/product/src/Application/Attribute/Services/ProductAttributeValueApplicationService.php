<?php

namespace RedJasmine\Product\Application\Attribute\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeValueUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\Pipelines\ProductAttributeGroupRulePipeline;
use RedJasmine\Product\Application\Attribute\Services\Pipelines\ProductAttributeRulePipeline;
use RedJasmine\Product\Application\Attribute\Services\Pipelines\ProductAttributeValueUpdatePipeline;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeValueRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method ProductAttributeValue create(ProductAttributeValueCreateCommand $command)
 * @method void update(ProductAttributeValueUpdateCommand $command)
 */
class ProductAttributeValueApplicationService extends ApplicationService
{


    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product-property-value';


    protected static string $modelClass = ProductAttributeValue::class;

    public function __construct(
        public ProductAttributeValueRepositoryInterface $repository,
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
                ProductAttributeRulePipeline::class,
                ProductAttributeGroupRulePipeline::class,
            ],
            'update' => [
                //ProductPropertyRulePipeline::class,
                ProductAttributeValueUpdatePipeline::class,
                ProductAttributeGroupRulePipeline::class,
            ],
        ];
    }


}
