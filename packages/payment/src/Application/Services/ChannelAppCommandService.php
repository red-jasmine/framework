<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Models\PaymentChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\ChannelAppTransformer;
use RedJasmine\Support\Application\ApplicationCommandService;

class ChannelAppCommandService extends ApplicationCommandService
{
    public function __construct(protected ChannelAppRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.channel-app.command';

    protected static string $modelClass = PaymentChannelApp::class;


    protected static ?string $transformerClass = ChannelAppTransformer::class;
}
