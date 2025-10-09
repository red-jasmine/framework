<?php

namespace RedJasmine\UserCore\Domain\Services\Login;


use Illuminate\Support\Facades\Auth;
use RedJasmine\UserCore\Domain\Exceptions\LoginException;
use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\UserCore\Domain\Services\Login\Facades\UserLoginServiceProvider;

class UserLoginService
{

    public function __construct(
        protected BaseUserRepositoryInterface $repository,
        protected string $guard
    ) {
    }


    public function captcha(\RedJasmine\UserCore\Domain\Services\Login\Data\UserLoginData $data) : bool
    {

        $provider = UserLoginServiceProvider::create($data->provider);

        $provider->init($this->repository, $this->guard)->captcha($data);
        return true;
    }

    protected function attempt(\RedJasmine\UserCore\Domain\Services\Login\Data\UserLoginData $data) : User
    {
        // 使用服务提供者的登陆方法 进行登陆
        $provider = UserLoginServiceProvider::create($data->provider);

        return $provider->init($this->repository, $this->guard)->login($data);

    }


    /**
     * @param  \RedJasmine\UserCore\Domain\Services\Login\Data\UserLoginData  $data
     *
     * @return UserTokenData
     * @throws LoginException
     */
    public function login(\RedJasmine\UserCore\Domain\Services\Login\Data\UserLoginData $data) : UserTokenData
    {

        // 使用服务提供者的登陆方法 进行登陆
        $user = $this->attempt($data);
        if (!$user->isAllowActivity()) {
            throw new LoginException('账户异常');
        }
        // 返回 token
        return $this->token($user);

    }

    public function token(User $user) : UserTokenData
    {
        $token                  = Auth::guard($this->guard)->login($user);
        $userToken              = new UserTokenData();
        $userToken->guard       = (string) $this->guard;
        $userToken->accessToken = (string) $token;
        $userToken->expire      = (int) (config('jwt.ttl') * 60);
        return $userToken;
    }

}
