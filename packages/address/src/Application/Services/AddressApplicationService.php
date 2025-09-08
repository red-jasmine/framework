<?php

namespace RedJasmine\Address\Application\Services;

use RedJasmine\Address\Application\Services\Hooks\AddressRegionHook;
use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Address\Domain\Repositories\AddressRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 地址应用服务
 *
 * 使用统一的BaseRepository实现，简化了依赖注入
 */
class AddressApplicationService extends ApplicationService
{
    protected static string $modelClass = Address::class;

    public static string $hookNamePrefix = 'address.application.address';

    public function __construct(
        public AddressRepositoryInterface $repository,
    ) {
    }

    /**
     * 获取仓库实例（写操作）
     */
    public function getRepository(): AddressRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * 获取只读仓库实例（读操作）
     */
    public function getReadRepository(): AddressRepositoryInterface
    {
        return $this->repository;
    }

    protected function hooks(): array
    {
        return [
            'create.validate' => [
                AddressRegionHook::class
            ],
            'update.validate' => [
                AddressRegionHook::class
            ]
        ];
    }
}
