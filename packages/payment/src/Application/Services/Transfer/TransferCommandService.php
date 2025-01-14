<?php

namespace RedJasmine\Payment\Application\Services\Transfer;

use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCancelCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCancelCommandHandler;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCloseCommand;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCloseCommandHandler;
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
 * @method bool executing(TransferExecutingCommand $command)
 * @see TransferFailCommandHandler::handle()
 * @method bool fail(TransferFailCommand $command)
 * @see TransferSuccessCommandHandler::handle()
 * @method bool success(TransferSuccessCommand $command)
 * @see TransferCreateCommandHandler::handle()
 * @method Transfer create(TransferCreateCommand $command)
 * @see TransferCancelCommandHandler::handle()
 * @method bool cancel(TransferCancelCommand $command)
 * @see TransferCloseCommandHandler::handle()
 * * @method bool close(TransferCloseCommand $command)
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
        'create'    => TransferCreateCommandHandler::class,
        'executing' => TransferExecutingCommandHandler::class,
        'fail'      => TransferFailCommandHandler::class,
        'success'   => TransferSuccessCommandHandler::class,
        'close'     => TransferCloseCommandHandler::class,
        'cancel'    => TransferCancelCommandHandler::class,
    ];
}
