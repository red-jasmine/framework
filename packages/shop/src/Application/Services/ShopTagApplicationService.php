<?php

namespace RedJasmine\Shop\Application\Services;

use RedJasmine\Shop\Domain\Models\ShopTag;
use RedJasmine\Shop\Domain\Repositories\ShopTagRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserTagApplicationService;

class ShopTagApplicationService extends BaseUserTagApplicationService
{
    public function __construct(
        public ShopTagRepositoryInterface $repository
    ) {
    }

    protected static string $modelClass = ShopTag::class;
} 