<?php

namespace RedJasmine\Payment\Application\Services\Transfer;

use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCreateCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCreateCommandHandler;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferExecutingCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferExecutingCommandHandler;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferFailCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferFailCommandHandler;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferSuccessCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferSuccessCommandHandler;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TransferRepositoryInterface;
use RedJasmine\Payment\Domain\Services\PaymentChannelService;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @see TransferExecutingCommandHandler::handle()
 * @method executing(TransferExecutingCommand $command)
 * @see TransferFailCommandHandler::handle()
 * @method fail(TransferFailCommand $command)
 * @see TransferSuccessCommandHandler::handle()
 * @method success(TransferSuccessCommand $command)
 * @see TransferCreateCommandHandler::handle()
 * @method create(TransferCreateCommand $command)
 */
class TransferCommandService extends ApplicationCommandService
{
    public function __construct(
        public TransferRepositoryInterface                     $repository,
        public ChannelAppRepositoryInterface                   $channelAppRepository,
        public MerchantAppRepositoryInterface                  $merchantAppRepository,
        public PaymentChannelService                           $paymentChannelService,
        public MerchantChannelAppPermissionRepositoryInterface $permissionRepository
    )
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.transfer.command';

    protected static string $modelClass = Transfer::class;

    protected static $macros = [
        'create'    => TransferCreateCommandHandler::class,
        'executing' => TransferExecutingCommandHandler::class,
        'success'   => TransferSuccessCommandHandler::class,
        'fail'      => TransferFailCommandHandler::class
    ];
}
