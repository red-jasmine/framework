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


    protected UserReadRepositoryInterface $readRepository;
    protected string                      $guard;

    public function init(UserReadRepositoryInterface $readRepository, string $guard) : static
    {
        $this->readRepository = $readRepository;

        $this->guard = $guard;

        return $this;
    }

    /**
     * @param  UserRegisterData  $data
     *
     * @return UserData
     * @throws UserRegisterException
     */
    public function captcha(UserRegisterData $data) : UserData
    {
        // 邮箱 or 手机 or 用户名 必须填写一个
        $this->validate($data);


        return $this->getUserData($data);

    }

    /**
     * @param  UserRegisterData  $data
     *
     * @return void
     * @throws UserRegisterException
     */
    public function validate(UserRegisterData $data) : void
    {
        if (blank($data->data['name'] ?? null)
            && blank($data->data['email'] ?? null)
            && blank($data->data['phone'] ?? null)
        ) {
            throw new UserRegisterException('请填写账号');
        }
        if (blank($data->data['password'] ?? null)) {
            throw new UserRegisterException('密码不能为空');
        }
        //严重用户名是否已经注册
        if (filled($data->data['name'] ?? null) && $this->readRepository->findByName($data->data['name'])) {
            throw new UserRegisterException('用户名已存在');
        }

        if (filled($data->data['email'] ?? null) && $this->readRepository->findByEmail($data->data['email'])) {
            throw new UserRegisterException('邮箱已存在');
        }

        if (filled($data->data['phone'] ?? null) && $this->readRepository->findByEmail($data->data['phone'])) {
            throw new UserRegisterException('邮箱已存在');
        }

    }

    public function getUserData(UserRegisterData $data) : UserData
    {
        $userData = new UserData();

        $userData->name           = $data->data['name'] ?? null;
        $userData->phone          = $data->data['phone'] ?? null;
        $userData->email          = $data->data['email'] ?? null;
        $userData->password       = $data->data['password'];
        $userData->invitationCode = $data->data['invitation_code'] ?? null;
        return $userData;
    }

    /**
     * @param  UserRegisterData  $data
     *
     * @return UserData
     * @throws UserRegisterException
     */
    public function register(UserRegisterData $data) : UserData
    {
        $this->validate($data);
        return $this->getUserData($data);
    }


}
