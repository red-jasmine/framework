<?php

namespace RedJasmine\Product\Application\Property\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductPropertyException;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method ProductPropertyGroup create(ProductPropertyGroupCreateCommand $command)
 * @method void update(ProductPropertyGroupUpdateCommand $command)
 */
class ProductPropertyGroupCommandService extends ApplicationCommandService
{
    protected static string $modelClass = ProductPropertyGroup::class;

    public function __construct(
        protected ProductPropertyGroupRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByName($data->name)) {
            throw new ProductPropertyException('名称已存在');

        }
        return parent::newModel($data);
    }


}
