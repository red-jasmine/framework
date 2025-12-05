<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Socialite\Application\Services\SocialiteUserApplicationService;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class SocialiteUserClearCommandHandler extends CommandHandler
{

    public function __construct(
        protected SocialiteUserApplicationService $service,

    ) {
    }

    /**
     * 返回绑定信息
     *
     * @param  SocialiteUserClearCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(SocialiteUserClearCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $socialiteUsers = $this->service
                ->repository
                ->getUsersByOwner($command->owner, $command->appId, $command->provider);

            foreach ($socialiteUsers as $socialiteUser) {
                $socialiteUser->unbind();
                $this->service->repository->update($socialiteUser);
            }
            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return true;

    }


}
