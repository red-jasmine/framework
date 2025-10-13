<?php

namespace RedJasmine\Product\Application\Attribute\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupCreateCommand;
use RedJasmine\Product\Application\Attribute\Services\Commands\ProductAttributeGroupUpdateCommand;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeGroup;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeGroupRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductAttributeException;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method ProductAttributeGroup create(ProductAttributeGroupCreateCommand $command)
 * @method void update(ProductAttributeGroupUpdateCommand $command)
 */
class ProductAttributeGroupApplicationService extends ApplicationService
{


    public static string $hookNamePrefix = 'product.application.product-attribute-group';


    /**
     * @var string
     */
    protected static string $modelClass = ProductAttributeGroup::class;

    public function __construct(
        public ProductAttributeGroupRepositoryInterface $repository,

    ) {

    }


    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByName($data->name)) {
            throw new ProductAttributeException('名称已存在');

        }
        return parent::newModel($data);
    }


}
