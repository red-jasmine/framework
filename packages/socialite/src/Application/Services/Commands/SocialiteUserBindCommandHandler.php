<?php

namespace RedJasmine\Socialite\Application\Services\Commands;

use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class SocialiteUserBindCommandHandler extends CommandHandler
{

    public function __construct(
        protected SocialiteUserCommandService $service,

    ) {
    }

    /**
     * 返回绑定信息
     *
     * @param  SocialiteUserBindCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(SocialiteUserBindCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {
            $query = SocialiteUserFindUserQuery::from([
                'provider'  => $command->provider,
                'client_id' => $command->clientId,
                'identity'  => $command->identity,
                'app_id'    => $command->appId,
            ]);

            $socialiteUser = $this->service->readRepository->findUser($query) ?? SocialiteUser::make(
                [
                    'provider'  => $command->provider,
                    'client_id' => $command->clientId,
                    'identity'  => $command->identity,
                    'app_id'    => $command->appId,
                ]
            );

            $socialiteUser->bind($command->appId, $command->owner);

            $this->service->repository->update($socialiteUser);

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
