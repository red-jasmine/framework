<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\Card\Domain\Repositories\CardGroupRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 卡密分组应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 */
class CardGroupApplicationService extends ApplicationService
{
    protected static string $modelClass = CardGroup::class;

    public function __construct(
        public CardGroupRepositoryInterface $repository,
    ) {
    }
}
