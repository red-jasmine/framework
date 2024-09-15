<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyGroupRulePipeline;
use RedJasmine\Product\Application\Property\Services\Pipelines\ProductPropertyPipeline;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\Handlers\CreateCommandHandler;
use RedJasmine\Support\Application\Handlers\DeleteCommandHandler;
use RedJasmine\Support\Application\Handlers\UpdateCommandHandler;
use RedJasmine\Support\Data\Data;

/**
 * @method ProductProperty create(ProductPropertyCreateCommand $command)
 * @method void update(ProductPropertyUpdateCommand $command)
 */
class ProductPropertyCommandService extends ApplicationCommandService
{
    protected static string $modelClass = ProductProperty::class;

    /**
     * 管道配置前缀
     * @var string|null
     */
    protected ?string $pipelinesConfigKeyPrefix = 'pipelines.product.properties';

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


    public function __construct(
        protected ProductPropertyRepositoryInterface $repository
    )
    {

    }

    public function newModel($data = null) : Model
    {
        if ($this->repository->findByName($data->name)) {
            throw new ProductPropertyException('名称已存在');
        }
        return parent::newModel($data);
    }


}
