<?php

namespace RedJasmine\Payment\Application\Services\Merchant;

use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantSetStatusCommandHandle;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\MerchantTransformer;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\DeleteCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\UpdateCommandHandler;

/**
 * @method Merchant create(Commands\MerchantCreateCommand $command)
 * @method Merchant update(Commands\MerchantUpdateCommand $command)
 * @method void setStatus(MerchantSetStatusCommand $command)
 */
class MerchantCommandService extends ApplicationCommandService
{
    public function __construct(
        public MerchantRepositoryInterface $repository,
        public MerchantTransformer         $transformer,
    )
    {
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


}
