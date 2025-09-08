<?php

namespace RedJasmine\Shop\Application\Services;

use RedJasmine\Shop\Domain\Models\ShopGroup;
use RedJasmine\Shop\Domain\Repositories\ShopGroupRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserGroupApplicationService;

class ShopGroupApplicationService extends BaseUserGroupApplicationService
{
    public function __construct(
        public ShopGroupRepositoryInterface $repository
    ) {
    }

    protected static string $modelClass = ShopGroup::class;
} 