<?php

namespace RedJasmine\Admin\Application\Services;

use RedJasmine\Admin\Domain\Models\AdminTag;
use RedJasmine\Admin\Domain\Repositories\AdminTagReadRepositoryInterface;
use RedJasmine\Admin\Domain\Repositories\AdminTagRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserTagApplicationService;
use RedJasmine\User\Domain\Transformers\UseTagTransformer;

class AdminTagApplicationService extends BaseUserTagApplicationService
{
    public function __construct(
        public AdminTagRepositoryInterface $repository,
        public AdminTagReadRepositoryInterface $readRepository,
        public UseTagTransformer $transformer
    ) {
    }


    protected static string $modelClass = AdminTag::class;


}