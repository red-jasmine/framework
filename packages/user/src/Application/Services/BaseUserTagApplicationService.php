<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;

/**
 * @property UserTagRepositoryInterface $repository
 */
abstract class BaseUserTagApplicationService extends ApplicationService
{
    public function tree(Query $query) : array
    {
        return $this->repository->tree($query);
    }

}