<?php

namespace RedJasmine\Payment\Application\Services\ChannelProduct;

use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\ChannelProductTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Data\Data;

/**
 * @method ChannelProduct create(Data $command)
 */
class ChannelProductApplicationService extends ApplicationService
{
    public function __construct(
        public ChannelProductRepositoryInterface $repository,
        public ChannelProductTransformer $transformer
    ) {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel-product.command';

    protected static string $modelClass = ChannelProduct::class;

}

