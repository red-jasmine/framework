<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Models\PaymentMerchantApp;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class MerchantAppCommandService extends ApplicationCommandService
{
    public function __construct(protected MerchantAppRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.merchant-app.command';

    protected static string $modelClass = PaymentMerchantApp::class;
}
