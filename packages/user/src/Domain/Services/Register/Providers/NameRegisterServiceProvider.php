<?php

namespace RedJasmine\User\Domain\Services\Register\Providers;

use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Exceptions\UserRegisterException;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class NameRegisterServiceProvider implements UserRegisterServiceProviderInterface
{


    public const string NAME = 'name';


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
        //严重用户名是否已经注册
        if ($this->readRepository->findByName($data->data['account'])) {
            throw new UserRegisterException('用户名已存在');
        }

        return $this->getUserData($data);

    }


    public function getUserData(UserRegisterData $data) : UserData
    {
        $userData = new UserData();

        $userData->name = $data->data['account'];

        $userData->password = $data->data['password'];

        return $userData;
    }

    public function register(UserRegisterData $data) : UserData
    {
        return $this->getUserData($data);
    }


}
