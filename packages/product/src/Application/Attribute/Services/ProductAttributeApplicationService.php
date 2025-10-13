<?php

namespace RedJasmine\Product\Application\Attribute\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeUpdateCommand;
use RedJasmine\Product\Application\Attribute\Services\Pipelines\ProductAttributeGroupRulePipeline;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductAttributeException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Handlers\CreateCommandHandler;
use RedJasmine\Support\Application\Handlers\DeleteCommandHandler;
use RedJasmine\Support\Application\Handlers\UpdateCommandHandler;

/**
 * @method ProductAttribute create(ProductAttributeCreateCommand $command)
 * @method void update(ProductAttributeUpdateCommand $command)
 */
class ProductAttributeApplicationService extends ApplicationService
{
    protected static string $modelClass = ProductAttribute::class;


    public static string $hookNamePrefix = 'product.application.product-attribute.command';


    public function __construct(
        public ProductAttributeRepositoryInterface $repository,
    ) {

    }

    public function newModel($data = null) : Model
    {
        if ($this->repository->findByName($data->name)) {
            throw new ProductAttributeException('名称已存在');
        }
        return parent::newModel($data);
    }

    protected function hooks() : array
    {
        return [
            'create' => [
                ProductAttributeGroupRulePipeline::class,
            ],
            'update' => [
                ProductAttributeGroupRulePipeline::class,
            ],
        ];
    }


}
