<?php

namespace RedJasmine\Payment\Application\Services\Transfer;

use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCreateCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCreateCommandHandler;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @see TransferCreateCommandHandler::handle()
 * @method create(TransferCreateCommand $command)
 */
class TransferCommandService extends ApplicationCommandService
{
    public function __construct(
        public TransferRepositoryInterface $repository,
        public ChannelAppRepositoryInterface $channelAppRepository,
        public MerchantAppRepositoryInterface $merchantAppRepository,
        public PaymentChannelService $paymentChannelService,
        public MerchantChannelAppPermissionRepositoryInterface $permissionRepository

    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.transfer.command';

    protected static string $modelClass = Transfer::class;

    protected static $macros = [
        'create' => Commands\TransferCreateCommandHandler::class,
    ];
}
