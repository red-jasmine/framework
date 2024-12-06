<?php

namespace RedJasmine\Payment\Application\Services;

use RedJasmine\Payment\Domain\Data\PlatformData;
use RedJasmine\Payment\Domain\Models\Platform;
use RedJasmine\Payment\Domain\Repositories\PlatformRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

/**
 * @method Platform create(PlatformData $command)
 */
class PlatformCommandService extends ApplicationCommandService
{


    public function __construct(protected PlatformRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.platform.command';

    protected static string $modelClass = Platform::class;

}
