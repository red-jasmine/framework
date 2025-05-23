<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\User\Domain\Models\UserTag;
use RedJasmine\User\Domain\Repositories\UserTagReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UseTagTransformer;

class UserTagApplicationService extends BaseUserTagApplicationService
{
    public function __construct(
        public UserTagRepositoryInterface $repository,
        public UserTagReadRepositoryInterface $readRepository,
        public UseTagTransformer $transformer
    ) {
    }


    protected static string $modelClass = UserTag::class;


}