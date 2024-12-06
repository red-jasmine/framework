<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantUpdateCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\MerchantCreateCommandHandle;
use RedJasmine\Payment\Application\Services\CommandHandlers\MerchantSetStatusCommandHandle;
use RedJasmine\Payment\Application\Services\CommandHandlers\MerchantUpdateCommandHandle;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @method Merchant create(MerchantCreateCommand $command)
 * @method Merchant update(MerchantUpdateCommand $command)
 * @method void setStatus(MerchantSetStatusCommand $command)
 */
class MerchantCommandService extends ApplicationCommandService
{

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.merchant.command';

    protected static string $modelClass = Merchant::class;


    protected static $macros = [
        'create'    => MerchantCreateCommandHandle::class,
        'setStatus' => MerchantSetStatusCommandHandle::class,
        'update'    => MerchantUpdateCommandHandle::class,
    ];


}
