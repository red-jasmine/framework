<?php

namespace RedJasmine\Product\Application\Attribute\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeData;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeRepositoryInterface;
use RedJasmine\Product\Domain\Attribute\Transformer\ProductAttributeTransformer;
use RedJasmine\Product\Exceptions\ProductAttributeException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Handlers\CreateCommandHandler;
use RedJasmine\Support\Application\Handlers\DeleteCommandHandler;
use RedJasmine\Support\Application\Handlers\UpdateCommandHandler;

/**
 * @method ProductAttribute create(ProductAttributeData $command)
 * @method void update(ProductAttributeData $command)
 */
class ProductAttributeApplicationService extends ApplicationService
{
    public static string    $hookNamePrefix = 'product.application.product-attribute.command';
    protected static string $modelClass     = ProductAttribute::class;

    public function __construct(
        public ProductAttributeRepositoryInterface $repository,
        public ProductAttributeTransformer $transformer,
    ) {

    }

    public function newModel($data = null) : Model
    {
        if ($this->repository->findByName($data->name)) {
            throw new ProductAttributeException('名称已存在');
        }
        return parent::newModel($data);
    }


}
