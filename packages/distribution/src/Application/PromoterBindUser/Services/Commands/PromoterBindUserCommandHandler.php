<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands;

use RedJasmine\Distribution\Application\PromoterBindUser\Services\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Domain\Services\PromoterBindUserService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class PromoterBindUserCommandHandler extends CommandHandler
{
    public function __construct(protected PromoterBindUserApplicationService $service)
    {
        $this->context = new HandleContext();
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterBindUserCommand $command) : bool
    {
        $this->beginDatabaseTransaction();
        try {

            $promoter = $this->service->promoterRepository->find($command->promoterId);

            $promoterBindUserService = new PromoterBindUserService($this->service->repository);

            $binds = $promoterBindUserService->bind($promoter, $command->user);
            if (count($binds)) {
                foreach ($binds as $bind) {
                    if ($bind->exists()) {
                        $this->service->repository->update($bind);
                    } else {
                        $this->service->repository->store($bind);
                    }

                }
            }

            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return true;
    }
}