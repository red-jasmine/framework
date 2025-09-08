<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Repositories\UserGroupReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UserGroupTransformer;

/**
 * @property UserGroupRepositoryInterface $repository
 */
abstract class BaseUserGroupApplicationService extends ApplicationService
{


    public function tree(Query $query) : array
    {
        return $this->repository->tree($query);
    }
}