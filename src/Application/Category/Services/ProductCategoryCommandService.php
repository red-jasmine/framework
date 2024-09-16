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


    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.category.command';



    protected static string $modelClass = ProductCategory::class;

    public function __construct(protected ProductCategoryRepositoryInterface $repository)
    {

    }


    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }


}
