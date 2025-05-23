<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Repositories\UserGroupReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UserGroupTransformer;

class UserGroupApplicationService extends BaseUserGroupApplicationService
{

    public function __construct(
        public UserGroupRepositoryInterface $repository,
        public UserGroupReadRepositoryInterface $readRepository,
        public UserGroupTransformer $transformer
    ) {
    }

    protected static string $modelClass = UserGroup::class;


}