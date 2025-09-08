<?php

namespace RedJasmine\Admin\Application\Services;

use RedJasmine\Admin\Domain\Models\AdminGroup;
use RedJasmine\Admin\Domain\Repositories\AdminGroupRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserGroupApplicationService;
use RedJasmine\User\Domain\Transformers\UserGroupTransformer;

/**
 * 管理员分组应用服务
 *
 * 提供管理员分组相关的业务逻辑操作
 */
class AdminGroupApplicationService extends BaseUserGroupApplicationService
{
    public function __construct(
        public AdminGroupRepositoryInterface $repository,
        public UserGroupTransformer $transformer
    ) {
    }

    protected static string $modelClass = AdminGroup::class;
}