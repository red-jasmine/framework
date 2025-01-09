<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Services\CommandHandlers\Merchants\MerchantChannelAppPermissionCommandHandler;
use RedJasmine\Payment\Domain\Data\ChannelAppData;
use RedJasmine\Payment\Domain\Data\MerchantChannelAppPermissionData;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\ChannelAppTransformer;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\UpdateCommandHandler;

/**
 * @method ChannelApp create(ChannelAppData $command)
 * @method void authorize(MerchantChannelAppPermissionData $command)
 */
class ChannelAppCommandService extends ApplicationCommandService
{
    public function __construct(
        public ChannelAppRepositoryInterface $repository,
        public ChannelAppTransformer $transformer,
        public MerchantRepositoryInterface $merchantRepository,
        public MerchantChannelAppPermissionRepositoryInterface $permissionRepository
    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel-app.command';

    protected static string $modelClass = ChannelApp::class;


    protected static ?string $transformerClass = ChannelAppTransformer::class;

    protected static $macros = [
        'create'    => CreateCommandHandler::class,
        'update'    => UpdateCommandHandler::class,
        'authorize' => MerchantChannelAppPermissionCommandHandler::class,
    ];
}
