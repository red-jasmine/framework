<?php

namespace RedJasmine\Payment\Application\Services\SettleReceiver;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\SettleReceiverRepository;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method SettleReceiver create(Data $command)
 */
class ChannelCommandService extends ApplicationCommandService
{
    public function __construct(public SettleReceiverRepository $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.settle-receiver.command';

    protected static string $modelClass = SettleReceiver::class;
}
