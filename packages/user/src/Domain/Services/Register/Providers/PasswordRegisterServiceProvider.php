<?php

namespace RedJasmine\User\Domain\Services\Register\Providers;

use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Exceptions\UserRegisterException;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class PasswordRegisterServiceProvider implements UserRegisterServiceProviderInterface
{


    public const string NAME = 'password';

    /**
     * @param  UserRegisterData  $data
     *
     * @return UserData
     * @throws UserRegisterException
     */
    public function preCheck(UserRegisterData $data) : UserData
    {
        // 邮箱 or 手机 or 用户名 必须填写一个

        if (blank($data->data['name'] ?? null)
            && blank($data->data['email'] ?? null)
            && blank($data->data['mobile'] ?? null)
        ) {
            throw new UserRegisterException('请填写账号');
        }
        if (blank($data->data['password'] ?? null)) {
            throw new UserRegisterException('密码不能为空');
        }
        //严重用户名是否已经注册
        if (filled($data->data['name'] ?? null) && app(UserReadRepositoryInterface::class)->findByName($data->data['name'])) {
            throw new UserRegisterException('用户名已存在');
        }

        if (filled($data->data['email'] ?? null) && app(UserReadRepositoryInterface::class)->findByEmail($data->data['email'])) {
            throw new UserRegisterException('邮箱已存在');
        }

        if (filled($data->data['mobile'] ?? null) && app(UserReadRepositoryInterface::class)->findByEmail($data->data['mobile'])) {
            throw new UserRegisterException('邮箱已存在');
        }

        return $this->getUserData($data);

    }


    public function getUserData(UserRegisterData $data) : UserData
    {
        $userData = new UserData();

        $userData->name     = $data->data['name'] ?? null;
        $userData->mobile   = $data->data['mobile'] ?? null;
        $userData->email    = $data->data['email'] ?? null;
        $userData->password = $data->data['password'];
        return $userData;
    }

    public function register(UserRegisterData $data) : UserData
    {
        return $this->getUserData($data);
    }


}
