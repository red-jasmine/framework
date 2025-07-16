<?php

namespace RedJasmine\User\Domain\Services\Login;


use Illuminate\Support\Facades\Auth;
use RedJasmine\User\Domain\Exceptions\LoginException;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserTokenData;
use RedJasmine\User\Domain\Services\Login\Facades\UserLoginServiceProvider;

class UserLoginService
{

    public function __construct(
        protected UserReadRepositoryInterface $readRepository,
        protected string $guard
    ) {
    }


    public function captcha(Data\UserLoginData $data) : bool
    {

        $provider = UserLoginServiceProvider::create($data->provider);

        $provider->init($this->readRepository, $this->guard)->captcha($data);
        return true;
    }

    protected function attempt(Data\UserLoginData $data) : User
    {
        // 使用服务提供者的登陆方法 进行登陆
        $provider = UserLoginServiceProvider::create($data->provider);

        return $provider->init($this->readRepository, $this->guard)->login($data);

    }


    /**
     * @param  Data\UserLoginData  $data
     *
     * @return UserTokenData
     * @throws LoginException
     */
    public function login(Data\UserLoginData $data) : UserTokenData
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
