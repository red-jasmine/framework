<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Domain\Exceptions\LoginException;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class PasswordLoginServiceProvider implements UserLoginServiceProviderInterface
{
    public const  NAME = 'password';


    protected UserRepositoryInterface $repository;
    protected string                  $guard;

    public function init(UserRepositoryInterface $repository, string $guard) : static
    {
        $this->repository = $repository;

        $this->guard = $guard;

        return $this;
    }

    public function captcha(UserLoginData $data)
    {
        // TODO: Implement captcha() method.
    }


    /**
     * @param  UserLoginData  $data
     *
     * @return User
     * @throws LoginException
     */
    public function login(UserLoginData $data) : User
    {
        // 按用户名称查询 用户
        if ($user = $this->repository->findByAccount($data->data['account'])) {
            // 手动验证密码
            $credentials = [
                'password' => $data->data['password']
            ];
            if (!Auth::guard($this->guard)->getProvider()->validateCredentials($user, $credentials)) {
                throw new LoginException('账号或者密码错误');
            }

            // 返回用户信息
            return $user;
        }

        throw new LoginException('账号或者密码错误');

    }


}
