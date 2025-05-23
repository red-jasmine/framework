<?php

namespace RedJasmine\Admin\Application\Services;

use RedJasmine\Admin\Domain\Models\AdminGroup;
use RedJasmine\Admin\Domain\Repositories\AdminGroupReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminGroupRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserGroupApplicationService;
use RedJasmine\User\Domain\Transformers\UserGroupTransformer;

class AdminGroupApplicationService extends BaseUserGroupApplicationService
{
    public function __construct(
        public AdminGroupRepositoryInterface $repository,
        public AdminGroupReadRepositoryInterface $readRepository,
        public UserGroupTransformer $transformer
    ) {
    }

    protected static string $modelClass = AdminGroup::class;

}