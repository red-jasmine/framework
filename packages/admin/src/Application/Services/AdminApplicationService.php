<?php

namespace RedJasmine\Admin\Application\Services;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Transformers\UserTransformer;

/**
 * 管理员应用服务
 *
 * 提供管理员相关的业务逻辑操作
 */
class AdminApplicationService extends BaseUserApplicationService
{
    public function __construct(
        public AdminRepositoryInterface $repository,
        public UserTransformer $transformer
    ) {
    }

    public static string $hookNamePrefix = 'admin.application.admin';

    protected static string $modelClass = Admin::class;

    /**
     * 获取认证守卫
     */
    public function getGuard() : string
    {
        return 'admin';
    }
}