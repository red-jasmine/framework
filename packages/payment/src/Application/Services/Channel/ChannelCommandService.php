<?php

namespace RedJasmine\Payment\Application\Services\Channel;

use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Data\Data;

/**
 * @method Channel create(Data $command)
 */
class ChannelCommandService extends ApplicationService
{
    public function __construct(
        public ChannelRepositoryInterface $repository
    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel.command';

    protected static string $modelClass = Channel::class;
}

