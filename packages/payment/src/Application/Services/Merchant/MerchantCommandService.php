<?php

namespace RedJasmine\Payment\Application\Services\Merchant;

use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantSetStatusCommandHandle;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantReadRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\MerchantTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method Merchant create(Commands\MerchantCreateCommand $command)
 * @method Merchant update(Commands\MerchantUpdateCommand $command)
 * @method void setStatus(MerchantSetStatusCommand $command)
 */
class MerchantCommandService extends ApplicationService
{
    public function __construct(
        public MerchantRepositoryInterface $repository,
        public MerchantReadRepositoryInterface $readRepository,
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

        'setStatus' => MerchantSetStatusCommandHandle::class,

    ];


}
