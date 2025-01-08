<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Services\CommandHandlers\Transfers\TransferCreateCommandHandler;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Support\Application\ApplicationCommandService;

class TransferCommandService extends ApplicationCommandService
{
    public function __construct(
        public TransferRepositoryInterface $repository,
        public MerchantAppRepositoryInterface $merchantAppRepository,
        public PaymentChannelService $paymentChannelService,

    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.transfer.command';

    protected static string $modelClass = Transfer::class;

    protected static $macros = [
        'create' => TransferCreateCommandHandler::class,

    ];
}
