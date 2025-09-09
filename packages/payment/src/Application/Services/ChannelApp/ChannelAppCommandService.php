<?php

namespace RedJasmine\Payment\Application\Services\ChannelApp;

use RedJasmine\Payment\Application\Services\ChannelApp\Commands\ChannelAppCreateCommandHandler;
use RedJasmine\Payment\Application\Services\ChannelApp\Commands\ChannelAppUpdateCommandHandler;
use RedJasmine\Payment\Application\Services\ChannelApp\Commands\MerchantChannelAppPermissionCommandHandler;
use RedJasmine\Payment\Domain\Data\ChannelAppData;
use RedJasmine\Payment\Domain\Data\MerchantChannelAppPermissionData;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\ChannelAppTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method ChannelApp create(ChannelAppData $command)
 * @method void authorize(MerchantChannelAppPermissionData $command)
 */
class ChannelAppCommandService extends ApplicationService
{
    public function __construct(
        public ChannelAppRepositoryInterface $repository,
        public ChannelAppTransformer $transformer,
        public MerchantRepositoryInterface $merchantRepository,
        public MerchantAppRepositoryInterface $merchantAppRepository,
        public MerchantChannelAppPermissionRepositoryInterface $permissionRepository
    ) {
    }

    protected static string $modelClass = ChannelApp::class;
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel-app.command';

    protected static $macros = [
        'create'    => ChannelAppCreateCommandHandler::class,
        'update'    => ChannelAppUpdateCommandHandler::class,
        'authorize' => MerchantChannelAppPermissionCommandHandler::class,
    ];
}

