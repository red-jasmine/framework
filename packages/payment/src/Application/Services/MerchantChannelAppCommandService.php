<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Data\MerchantChannelAppData;
use RedJasmine\Payment\Domain\Models\MerchantChannelApp;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\DeleteCommandHandler;
use RedJasmine\Support\Data\Data;

/**
 * @method MerchantChannelApp create(MerchantChannelAppData $command)
 * @method void delete(Data $command)
 */
class MerchantChannelAppCommandService extends ApplicationCommandService
{
    public function __construct(protected MerchantChannelAppRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.merchant-channel-app.command';

    protected static string $modelClass = MerchantChannelApp::class;


    protected static $macros = [
        'create' => CreateCommandHandler::class,
        //'update' => UpdateCommandHandler::class,
        'delete' => DeleteCommandHandler::class,
    ];
}
