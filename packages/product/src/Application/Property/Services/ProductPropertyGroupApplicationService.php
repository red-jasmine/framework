<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method ProductPropertyGroup create(ProductPropertyGroupCreateCommand $command)
 * @method void update(ProductPropertyGroupUpdateCommand $command)
 */
class ProductPropertyGroupApplicationService extends ApplicationService
{


    public static string $hookNamePrefix = 'product.application.product-property-group';


    /**
     * @var string
     */
    protected static string $modelClass = ProductPropertyGroup::class;

    public function __construct(
        public ProductPropertyGroupRepositoryInterface $repository,

    ) {

    }


    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByName($data->name)) {
            throw new ProductPropertyException('名称已存在');

        }
        return parent::newModel($data);
    }


}
