<?php

namespace RedJasmine\Product\Application\Category\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Application\Category\Services\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;


/**
 * @method  create(ProductCategoryCreateCommand $command)
 * @method void update(ProductCategoryUpdateCommand $command)
 * @method void delete(ProductCategoryDeleteCommand $command)
 * @method ProductCategory find(int $id)
 */
class ProductCategoryApplicationService extends ApplicationService
{


    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.category';


    protected static string $modelClass = ProductCategory::class;

    public function __construct(
        public ProductCategoryRepositoryInterface $repository,
        public ProductCategoryReadRepositoryInterface $readRepository,
    ) {

    }


    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }

    public function tree(ProductCategoryTreeQuery $query) : array
    {

        return $this->readRepository->tree($query);
    }

    public function isAllowUse(int $id) : bool
    {
        return (bool)($this->readRepository->find(FindQuery::make($id))?->isAllowUse());
    }


}
