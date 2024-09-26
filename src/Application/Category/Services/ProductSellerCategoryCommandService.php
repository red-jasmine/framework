<?php

namespace RedJasmine\Product\Application\Category\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryReadRepositoryInterface;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryRepositoryInterface;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @method int create(ProductSellerCategoryCreateCommand $command)
 * @method void update(ProductSellerCategoryUpdateCommand $command)
 * @method void delete(ProductSellerCategoryDeleteCommand $command)
 * @method ProductSellerCategory find(int $id)
 */
class ProductSellerCategoryCommandService extends ApplicationCommandService
{

    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.seller-category.command';

    protected static string $modelClass = ProductSellerCategory::class;

    public function __construct(
        protected ProductSellerCategoryRepositoryInterface     $repository,
        protected ProductSellerCategoryReadRepositoryInterface $readRepository
    )
    {

    }

    public function newModel($data = null) : Model
    {
        if ($model = $this->readRepository
            ->withQuery(fn($query) => $query->onlyOwner($data->owner)->where('parent_id',$data->parentId))
            ->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }

}
