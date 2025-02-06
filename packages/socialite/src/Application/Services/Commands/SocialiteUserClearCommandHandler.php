<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class SocialiteUserClearCommandHandler extends CommandHandler
{

    public function __construct(
        protected SocialiteUserCommandService $service,

    ) {
    }

    /**
     * 返回绑定信息
     *
     * @param  SocialiteUserClearCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(SocialiteUserClearCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $socialiteUsers = $this->service
                ->readRepository
                ->getUsersByOwner($command->owner, $command->appId, $command->provider);

            foreach ($socialiteUsers as $socialiteUser) {
                $socialiteUser->unbind();
                $this->service->repository->update($socialiteUser);
            }
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return true;

    }


}
