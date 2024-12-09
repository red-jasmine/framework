<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Services\CommandHandlers\MerchantChannelAppPermissionCommandHandler;
use RedJasmine\Payment\Domain\Data\MerchantChannelAppPermissionData;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @method void authorize(MerchantChannelAppPermissionData $command)
 */
class MerchantChannelAppPermissionCommandService extends ApplicationCommandService
{
    public function __construct(protected MerchantChannelAppPermissionRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.merchant-channel-app-permission.command';

    protected static string $modelClass = MerchantChannelAppPermission::class;


    protected static $macros = [
        'authorize' => MerchantChannelAppPermissionCommandHandler::class,
    ];
}
