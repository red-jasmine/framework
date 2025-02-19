<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Vip\Domain\Data\VipProductData;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;

/**
 * @method VipProduct create(VipProductData $command)
 */
class VipProductCommandService extends ApplicationCommandService
{

    public function __construct(
        public VipProductRepositoryInterface $repository,
        public VipProductReadRepositoryInterface $readRepository,
    ) {
    }

    protected static string $modelClass = VipProduct::class;

    public static string $hookNamePrefix = 'vip.application.command.vip-product';


}