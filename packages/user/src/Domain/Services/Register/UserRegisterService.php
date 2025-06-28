<?php

namespace RedJasmine\User\Domain\Services\Register;

use Illuminate\Support\Str;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;
use RedJasmine\User\Domain\Services\Register\Facades\UserRegisterServiceProvider;

/**
 * 注册服务
 */
class UserRegisterService extends Service
{

    public function __construct(
        protected UserReadRepositoryInterface $readRepository,
        protected string $guard,
        protected User $newUser,
    ) {
    }


    public static string $hookNamePrefix = 'user.register';

    public function captcha(UserRegisterData $data) : void
    {
        $provider = UserRegisterServiceProvider::create($data->provider);

        $provider->init($this->readRepository, $this->guard)->captcha($data);
    }

    public function register(UserRegisterData $data) : User
    {
        $provider = UserRegisterServiceProvider::create($data->provider);

        $userData = $provider->init($this->readRepository, $this->guard)->register($data);

        $user = $this->hook('makeUser', $data, fn() => $this->makeUser($userData));

        $user->register();

        return $user;
    }


    public function makeUser(UserData $data) : User
    {
        /**
         * @var User $user
         */
        $user = $this->newUser;

        // 用户注册功能呢

        // 验证是否允许注册

        $user->type     = $data->type;
        $user->name     = $data->name ?? $this->buildUserName();
        $user->nickname = $data->nickname ?? $this->buildNickname();
        $user->email    = $data->email ?? null;
        $user->phone    = $data->phone ?? null;
        $user->password = $data->password ?? null;
        $user->avatar   = $data->avatar ?? null;
        $user->gender   = $data->gender ?? null;
        $user->birthday = $data->birthday ?? null;
        // 验证是否允许注册

        // 渠道 TODO

        return $user;
    }

    protected function buildUserName() : string
    {

        return Str::random(16);

    }

    protected function buildNickname() : string
    {
        return Str::random(6);
    }

}
