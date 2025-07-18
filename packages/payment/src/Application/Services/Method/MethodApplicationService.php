<?php

namespace RedJasmine\Payment\Application\Services\Method;

use RedJasmine\Payment\Domain\Data\MethodData;
use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method Method create(MethodData $command)
 */
class MethodApplicationService extends ApplicationService
{


    public function __construct(public MethodRepositoryInterface $repository)
    {
    }

    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'payment.application.method.command';

    protected static string $modelClass = Method::class;

}
