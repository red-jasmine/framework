<?php

namespace RedJasmine\Distribution\Application\PromoterGroup\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Application\PromoterGroup\Services\Commands\CreatePromoterGroupCommand;
use RedJasmine\Distribution\Application\PromoterGroup\Services\Commands\DeletePromoterGroupCommand;
use RedJasmine\Distribution\Application\PromoterGroup\Services\Commands\UpdatePromoterGroupCommand;
use RedJasmine\Distribution\Domain\Data\PromoterGroupData;
use RedJasmine\Distribution\Domain\Models\PromoterGroup;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupRepositoryInterface;
use RedJasmine\Distribution\Domain\Transformers\PromoterGroupTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;

/**
 * @method int create(CreatePromoterGroupCommand $command)
 * @method void update(UpdatePromoterGroupCommand $command)
 * @method void delete(DeletePromoterGroupCommand $command)
 * @method PromoterGroup find(int $id)
 */
class PromoterGroupApplicationService extends ApplicationService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'distribution.application.promoter-group';

    protected static string $modelClass = PromoterGroup::class;

    public function __construct(
        public PromoterGroupRepositoryInterface $repository,
        public PromoterGroupReadRepositoryInterface $readRepository,
        public PromoterGroupTransformer $transformer,
    ) {
    }

  
    public function isAllowUse(int $id, UserInterface $owner): bool
    {
        return (bool) ($this->readRepository->withQuery(function ($query) use ($owner) {
            return $query->onlyOwner($owner);
        })->find(FindQuery::make($id))?->isAllowUse());
    }

    public function tree(Query $query): array
    {
        return $this->readRepository->tree($query);
    }

} 