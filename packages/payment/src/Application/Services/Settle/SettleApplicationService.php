<?php

namespace RedJasmine\Payment\Application\Services\Settle;

use RedJasmine\Payment\Application\Services\Settle\Commands\SettleCreateCommand;
use RedJasmine\Payment\Application\Services\Settle\Commands\SettleCreateCommandHandler;
use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Repositories\SettleRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see SettleCreateCommandHandler::handle()
 * @method Settle create(SettleCreateCommand $command)
 */
class SettleApplicationService extends ApplicationService
{
    public function __construct(
        public SettleRepositoryInterface $repository) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.settle.command';

    protected static string $modelClass = Settle::class;


    protected static $macros = [
        'create' => SettleCreateCommandHandler::class,
    ];
}

