<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Repositories\UserGroupReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UserGroupTransformer;

class UserTagCategoryApplicationService extends ApplicationService
{

    public function __construct(
        protected UserGroupRepositoryInterface $repository,
        protected UserGroupReadRepositoryInterface $readRepository,
        protected UserGroupTransformer $transformer
    ) {
    }

    protected static string $modelClass = UserGroup::class;


    public function tree(Query $query) : array
    {
        return $this->readRepository->tree($query);
    }
}