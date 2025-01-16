<?php

namespace RedJasmine\Payment\Application\Services\Settle;

use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Repositories\SettleRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method Settle create(Data $command)
 */
class SettleCommandService extends ApplicationCommandService
{
    public function __construct(
        public SettleRepositoryInterface $repository,
    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.settle.command';

    protected static string $modelClass = Settle::class;
}
