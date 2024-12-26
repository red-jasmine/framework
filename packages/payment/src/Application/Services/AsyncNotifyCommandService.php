<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Services\CommandHandlers\Notify\NotifyCreateCommandHandler;
use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Payment\Domain\Repositories\NotifyRepositoryInterface;
use RedJasmine\Payment\Domain\Services\AsyncNotifyService;
use RedJasmine\Support\Application\ApplicationCommandService;

class AsyncNotifyCommandService extends ApplicationCommandService
{

    public function __construct(
        public NotifyRepositoryInterface $repository,
        public AsyncNotifyService        $asyncNotifyService,
    )
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.async-notify.command';


    protected static string $modelClass = Notify::class;


    protected static $macros = [
        'create' => NotifyCreateCommandHandler::class,
    ];

}
