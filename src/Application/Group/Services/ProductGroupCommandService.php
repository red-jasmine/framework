<?php

namespace RedJasmine\Product\Application\Group\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupReadRepositoryInterface;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @method int create(ProductGroupCreateCommand $command)
 * @method void update(ProductGroupUpdateCommand $command)
 * @method void delete(ProductGroupDeleteCommand $command)
 * @method ProductGroup find(int $id)
 */
class ProductGroupCommandService extends ApplicationCommandService
{

    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.seller-category.command';

    protected static string $modelClass = ProductGroup::class;

    public function __construct(
        protected ProductGroupRepositoryInterface     $repository,
        protected ProductGroupReadRepositoryInterface $readRepository
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
