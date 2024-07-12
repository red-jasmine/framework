<?php

namespace RedJasmine\Product\Application\Category\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @method  create(ProductCategoryCreateCommand $command)
 * @method void update(ProductCategoryUpdateCommand $command)
 * @method void delete(ProductCategoryDeleteCommand $command)
 * @method ProductCategory find(int $id)
 */
class ProductCategoryCommandService extends ApplicationCommandService
{

    protected static string $modelClass = ProductCategory::class;

    public function __construct(protected ProductCategoryRepositoryInterface $repository)
    {
        parent::__construct();
    }


    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }


}
