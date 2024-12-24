<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantUpdateCommand;
use RedJasmine\Payment\Application\Services\CommandHandlers\MerchantCreateCommandHandle;
use RedJasmine\Payment\Application\Services\CommandHandlers\MerchantSetStatusCommandHandle;
use RedJasmine\Payment\Application\Services\CommandHandlers\MerchantUpdateCommandHandle;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\MerchantTransformer;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\DeleteCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\UpdateCommandHandler;

/**
 * @method Merchant create(MerchantCreateCommand $command)
 * @method Merchant update(MerchantUpdateCommand $command)
 * @method void setStatus(MerchantSetStatusCommand $command)
 */
class MerchantCommandService extends ApplicationCommandService
{
    public function __construct(
        public MerchantRepositoryInterface $repository,
        public MerchantTransformer $transformer,
    ) {
    }


    protected static string $modelClass = Merchant::class;

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.merchant.command';


    protected static $macros = [
        'create'    => CreateCommandHandler::class,
        'update'    => UpdateCommandHandler::class,
        'setStatus' => MerchantSetStatusCommandHandle::class,
        'delete'    => DeleteCommandHandler::class,
    ];


    // protected static $macros = [
    //     'create'    => MerchantCreateCommandHandle::class,
    //     'setStatus' => MerchantSetStatusCommandHandle::class,
    //     'update'    => MerchantUpdateCommandHandle::class,
    // ];


}
