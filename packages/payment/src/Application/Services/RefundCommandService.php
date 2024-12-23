<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Refund\RefundCreateCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\Refunds\RefundCreateCommandHandler;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @see  RefundCreateCommandHandler::handle()
 * @method void refund(RefundCreateCommand $command)
 */
class RefundCommandService extends ApplicationCommandService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.refund.command';

    protected static string $modelClass = Refund::class;

    protected static $macros = [
        'create' => RefundCreateCommandHandler::class
    ];
}
