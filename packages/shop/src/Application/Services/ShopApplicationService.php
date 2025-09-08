<?php

namespace RedJasmine\Shop\Application\Services;

use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\Shop\Domain\Repositories\ShopRepositoryInterface;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Transformers\UserTransformer;

class ShopApplicationService extends BaseUserApplicationService
{
    public function __construct(
        public ShopRepositoryInterface $repository,
        public UserTransformer $transformer
    ) {
    }

    public static string $hookNamePrefix = 'shop.application.shop';

    protected static string $modelClass = Shop::class;

    public function getGuard(): string
    {
        return 'shop';
    }
} 