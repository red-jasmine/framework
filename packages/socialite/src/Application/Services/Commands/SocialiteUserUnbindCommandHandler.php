<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Socialite\Application\Services\SocialiteUserApplicationService;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;

class SocialiteUserUnbindCommandHandler extends CommandHandler
{

    public function __construct(
        protected SocialiteUserApplicationService $service,

    ) {
    }

    /**
     * 返回绑定信息
     *
     * @param  SocialiteUserUnbindCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(SocialiteUserUnbindCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $query = SocialiteUserFindUserQuery::from([
                'provider'  => $command->provider,
                'client_id' => $command->clientId,
                'identity'  => $command->identity,
                'app_id'    => $command->appId,
            ]);

            $socialiteUser = $this->service->repository->findUser($query);

            $socialiteUser->unbind();

            $this->service->repository->update($socialiteUser);

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
