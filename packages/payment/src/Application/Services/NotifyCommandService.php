<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Payment\Domain\Repositories\NotifyRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class NotifyCommandService extends ApplicationCommandService
{

    public function __construct(
        public NotifyRepositoryInterface $repository
    )
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.notify.command';


    protected static string $modelClass = Notify::class;

}
