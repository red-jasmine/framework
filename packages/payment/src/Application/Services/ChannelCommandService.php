<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Models\PaymentChannel;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method PaymentChannel create(Data $command)
 */
class ChannelCommandService extends ApplicationCommandService
{
    public function __construct(protected ChannelRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel.command';

    protected static string $modelClass = PaymentChannel::class;
}
