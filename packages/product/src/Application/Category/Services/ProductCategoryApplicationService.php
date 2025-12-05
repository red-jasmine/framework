<?php

namespace RedJasmine\Product\Application\Category\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\Services\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Data\CategoryData;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Domain\Category\Transformer\ProductCategoryTransformer;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationService;


/**
 * @method  create(CategoryData $command)
 * @method void update(CategoryData $command)
 * @method void delete(CategoryData $command)
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
        public ProductCategoryTransformer $transformer,
    ) {

    }


    public function newModel($data = null) : Model
    {
        // TODO 属于业务规则 需要调整到领域层
        if ($model = $this->repository->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }

    public function tree(ProductCategoryTreeQuery $query) : array
    {

        return $this->repository->tree($query);
    }

    public function isAllowUse(int $id) : bool
    {
        return (bool) ($this->repository->find($id)?->isAllowUse());
    }


}
