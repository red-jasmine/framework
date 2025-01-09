<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\UpdateCommandHandler;


class MerchantAppCommandService extends ApplicationCommandService
{
    public function __construct(
        public MerchantAppRepositoryInterface $repository,
        public MerchantRepositoryInterface $merchantRepository,
        public ChannelAppRepositoryInterface $channelAppRepository,

    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.merchant-app.command';

    protected static string $modelClass = MerchantApp::class;

}
