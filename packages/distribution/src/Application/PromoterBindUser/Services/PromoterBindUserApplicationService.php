<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Services;

use RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands\PromoterBindUserCommand;
use RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands\PromoterBindUserCommandHandler;
use RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands\PromoterUnbindUserCommand;
use RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands\PromoterUnbindUserCommandHandler;
use RedJasmine\Distribution\Domain\Models\PromoterBindUs\Serviceser;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterRepositoryInterface;
use RedJasmine\Distribution\Domain\Transformers\PromoterBindUserTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @see PromoterBindUserCommandHandler::handle()
 * @method bind(PromoterBindUserCommand $command)
 * @see PromoterUnbindUserCommandHandler::handle()
 * @method unbind(PromoterUnbindUserCommand $command)
 */
class PromoterBindUserApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'distribution.application.promoter-bind-user.command';

    protected static string $modelClass = PromoterBindUser::class;

    protected static $macros = [
        'bind'   => PromoterBindUserCommandHandler::class,
        'unbind' => PromoterUnbindUserCommandHandler::class,
    ];

    public function __construct(
        public PromoterBindUserRepositoryInterface $repository,
        public PromoterBindUserReadRepositoryInterface $readRepository,
        public PromoterRepositoryInterface $promoterRepository,
        public PromoterBindUserTransformer $transformer,
    ) {
    }
} 