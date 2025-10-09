<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UserTransformer;


class UserApplicationService extends BaseUserApplicationService
{

    public static string    $hookNamePrefix = 'user.application.user';
    protected static string $modelClass     = User::class;


    public function __construct(
        public UserRepositoryInterface $repository,
        public UserGroupRepositoryInterface $groupRepository,
        public UserTransformer $transformer
    ) {
    }

    public function getGuard() : string
    {
        return 'user';
    }


}
