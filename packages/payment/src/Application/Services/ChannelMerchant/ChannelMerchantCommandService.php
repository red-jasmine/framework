<?php

namespace RedJasmine\Payment\Application\Services\ChannelMerchant;

use RedJasmine\Payment\Domain\Models\ChannelMerchant;
use RedJasmine\Payment\Domain\Repositories\ChannelMerchantRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method ChannelMerchant create(Data $command)
 */
class ChannelMerchantCommandService extends ApplicationCommandService
{
    public function __construct(public ChannelMerchantRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel-merchant.command';

    protected static string $modelClass = ChannelMerchant::class;
}
