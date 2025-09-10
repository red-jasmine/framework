<?php

namespace RedJasmine\Payment\Application\Services\MerchantApp;

use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;


class MerchantAppApplicationService extends ApplicationService
{
    public function __construct(
        public MerchantAppRepositoryInterface $repository,
        public MerchantRepositoryInterface $merchantRepository,
        public ChannelAppRepositoryInterface $channelAppRepository
    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.merchant-app.command';

    protected static string $modelClass = MerchantApp::class;

}

