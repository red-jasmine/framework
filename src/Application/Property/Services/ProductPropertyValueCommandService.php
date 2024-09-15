<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyGroupRulePipeline;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyRulePipeline;
use RedJasmine\Product\Application\Property\Services\Pipelines\PropertyValueUpdatePipeline;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method ProductPropertyValue create(ProductPropertyValueCreateCommand $command)
 * @method void update(ProductPropertyValueUpdateCommand $command)
 */
class ProductPropertyValueCommandService extends ApplicationCommandService
{
    protected static string $modelClass = ProductPropertyValue::class;

    public function __construct(
        protected ProductPropertyValueRepositoryInterface $repository
    )
    {
        parent::__construct();
    }

    protected function hooks() : array
    {
        return [

            'create' => [
                ProductPropertyRulePipeline::class,
                ProductPropertyGroupRulePipeline::class,
            ],
            'update' => [
                ProductPropertyRulePipeline::class,
                PropertyValueUpdatePipeline::class,
                ProductPropertyGroupRulePipeline::class,
            ],
        ];
    }

    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByNameInProperty($data->pid, $data->name)) {
            throw new ProductPropertyException('名称已存在');
            return $model;
        }
        return parent::newModel($data);
    }


}
