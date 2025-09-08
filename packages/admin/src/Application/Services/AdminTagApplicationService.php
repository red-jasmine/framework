<?php

namespace RedJasmine\Admin\Application\Services;

use RedJasmine\Admin\Domain\Models\AdminTag;
use RedJasmine\Admin\Domain\Repositories\AdminTagRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserTagApplicationService;
use RedJasmine\User\Domain\Transformers\UseTagTransformer;

/**
 * 管理员标签应用服务
 *
 * 提供管理员标签相关的业务逻辑操作
 */
class AdminTagApplicationService extends BaseUserTagApplicationService
{
    public function __construct(
        public AdminTagRepositoryInterface $repository,
        public UseTagTransformer $transformer
    ) {
    }

    protected static string $modelClass = AdminTag::class;
}