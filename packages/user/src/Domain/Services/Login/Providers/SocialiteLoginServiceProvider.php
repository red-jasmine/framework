<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserLoginCommand;
use RedJasmine\Socialite\Application\Services\SocialiteUserCommandService;
use RedJasmine\User\Domain\Exceptions\UserNotFoundException;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;
use Throwable;

class SocialiteLoginServiceProvider implements UserLoginServiceProviderInterface
{


    protected UserReadRepositoryInterface $readRepository;
    protected string                      $guard;

    public function init(UserReadRepositoryInterface $readRepository, string $guard) : static
    {
        $this->readRepository = $readRepository;

        $this->guard = $guard;

        return $this;
    }


    public const string NAME = 'socialite';

    public function captcha(UserLoginData $data)
    {
        // TODO: Implement captcha() method.
    }


    /**
     * @param  UserLoginData  $data
     *
     * @return User
     * @throws UserNotFoundException
     */
    public function login(UserLoginData $data) : User
    {
        // 验证参数 TODO
        $data->data;
        $data->data['appId'] = 'UserCenter';
        $command             = SocialiteUserLoginCommand::from($data->data);
        // 获取社交账号信息
        $socialiteUser = app(SocialiteUserCommandService::class)->login($command);
        // 根据社交账号绑定的信息 查询用户信息
        try {
            $user = app(UserRepositoryInterface::class)->find($socialiteUser->owner_id);
        } catch (Throwable $throwable) {
            $exception = new UserNotFoundException();
            $exception->setSocialiteUser($socialiteUser);
            throw $exception;
        }

        // 返回用户信息
        return $user;
    }


}
