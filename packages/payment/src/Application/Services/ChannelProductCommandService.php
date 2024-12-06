<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\ChannelProductTransformer;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * @method ChannelProduct create(Data $command)
 */
class ChannelProductCommandService extends ApplicationCommandService
{
    public function __construct(protected ChannelProductRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel-product.command';

    protected static string $modelClass = ChannelProduct::class;

    protected static ?string $transformerClass = ChannelProductTransformer::class;
}
