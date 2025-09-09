<?php

namespace RedJasmine\Product\Application\Group\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;


/**
 * @method int create(ProductGroupCreateCommand $command)
 * @method void update(ProductGroupUpdateCommand $command)
 * @method void delete(ProductGroupDeleteCommand $command)
 * @method ProductGroup find(int $id)
 */
class ProductGroupApplicationService extends ApplicationService
{

    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.seller-category';

    protected static string $modelClass = ProductGroup::class;

    public function __construct(
        public ProductGroupRepositoryInterface $repository
    ) {
    }

    public function newModel($data = null) : Model
    {

        if ($model = $this->repository
            ->withQuery(fn($query) => $query->onlyOwner($data->owner)->where('parent_id',$data->parentId))
            ->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }


    public function isAllowUse(int $id, UserInterface $owner) : bool
    {

        return (bool) ($this->getRepository()->withQuery(function ($query) use ($owner) {
            return $query->onlyOwner($owner);
        })->find(FindQuery::make($id))?->isAllowUse());
    }

    public function tree(Query $query) : array
    {

        // $owner = $query->owner;
        // unset($query->owner);
        // $this->repository->withQuery(function ($builder)use($owner){
        //     return $builder->onlyOwner($owner);
        // });
        return $this->repository->tree($query);
    }


}
