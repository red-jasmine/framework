<?php

namespace RedJasmine\Admin\Application\Services;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Repositories\AdminGroupReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Transformers\UserTransformer;

class AdminApplicationService extends BaseUserApplicationService
{
    public function __construct(
        public AdminRepositoryInterface $repository,
        public AdminReadRepositoryInterface $readRepository,
        public AdminGroupReadRepositoryInterface $groupReadRepository,
        public UserTransformer $transformer
    ) {
    }

    protected static string $modelClass = Admin::class;
}