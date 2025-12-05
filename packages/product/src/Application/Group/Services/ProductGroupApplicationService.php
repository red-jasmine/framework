<?php

namespace RedJasmine\Product\Application\Group\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Domain\Group\Data\GroupData;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Product\Domain\Group\Transformer\GroupTransformer;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;


/**
 * @method int create(GroupData $command)
 * @method void update(GroupData $command)
 * @method void delete(GroupData $command)
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
        public ProductGroupRepositoryInterface $repository,
        public GroupTransformer $transformer,
    ) {
    }

    public function newModel($data = null) : Model
    {
        // TODO 属于业务规则 需要放在领域层

        if ($model = $this->repository
            ->withQuery(fn($query) => $query->onlyOwner($data->owner)->where('parent_id',$data->parentId))
            ->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }


    public function isAllowUse(int $id, UserInterface $owner) : bool
    {

        return (bool) ($this->repository->withQuery(function ($query) use ($owner) {
            return $query->onlyOwner($owner);
        })->find($id)?->isAllowUse());
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
