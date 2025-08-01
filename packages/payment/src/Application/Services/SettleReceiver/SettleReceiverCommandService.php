<?php

namespace RedJasmine\Payment\Application\Services\SettleReceiver;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\SettleReceiverTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Data\Data;

/**
 * @method SettleReceiver create(Data $command)
 */
class SettleReceiverCommandService extends ApplicationService
{
    public function __construct(
        public SettleReceiverRepositoryInterface $repository,
        public SettleReceiverReadRepositoryInterface $readRepository,
        public SettleReceiverTransformer $transformer,
    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.settle-receiver.command';

    protected static string $modelClass = SettleReceiver::class;
}
