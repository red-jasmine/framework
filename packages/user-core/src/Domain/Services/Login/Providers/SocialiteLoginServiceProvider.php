<?php

namespace RedJasmine\UserCore\Domain\Services\Login\Providers;

use RedJasmine\Socialite\Application\Services\Commands\SocialiteUserLoginCommand;
use RedJasmine\Socialite\Application\Services\SocialiteUserApplicationService;
use RedJasmine\UserCore\Domain\Exceptions\UserNotFoundException;
use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\Login\Data\UserLoginData;
use Throwable;

class SocialiteLoginServiceProvider implements UserLoginServiceProviderInterface
{


    protected BaseUserRepositoryInterface $repository;
    protected string                      $guard;

    public function init(BaseUserRepositoryInterface $repository, string $guard) : static
    {
        $this->repository = $repository;

        $this->guard = $guard;

        return $this;
    }


    public const  NAME = 'socialite';

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
        $socialiteUser = app(SocialiteUserApplicationService::class)->login($command);
        // 根据社交账号绑定的信息 查询用户信息
        try {
            $user = app(BaseUserRepositoryInterface::class)->find($socialiteUser->owner_id);
        } catch (Throwable $throwable) {
            $exception = new UserNotFoundException();
            $exception->setSocialiteUser($socialiteUser);
            throw $exception;
        }

        // 返回用户信息
        return $user;
    }


}
