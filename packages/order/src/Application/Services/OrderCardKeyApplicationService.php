<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Domain\Models\OrderCardKey;
use RedJasmine\Order\Domain\Repositories\OrderCardKeyReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class OrderCardKeyApplicationService extends ApplicationService
{

    protected static string $modelClass = OrderCardKey::class;

    public function __construct(
        public OrderCardKeyReadRepositoryInterface $readRepository
    ) {

    }

    protected static $macros = [
        'create' => null,
        'update' => null,
        'delete' => null,
    ];
}
