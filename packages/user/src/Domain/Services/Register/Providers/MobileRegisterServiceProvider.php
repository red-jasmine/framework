<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Exceptions\UserRegisterException;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class MobileRegisterServiceProvider implements UserRegisterServiceProviderInterface
{

    public function __construct(
        protected UserReadRepositoryInterface $userReadRepository

    ) {
    }

    public const string NAME = 'mobile';

    public function preCheck(UserRegisterData $data) : UserData
    {
        // 发送验证码 TODO
    }


    /**
     * @param  UserRegisterData  $data
     *
     * @return UserData
     * @throws UserRegisterException
     */
    public function register(UserRegisterData $data) : UserData
    {
        // 验证验证码 TODO

        // 验证手机号是否已经注册
        $phoneNumber = $data->data['phoneNumber'] ?? null;

        $hasUser = $this->userReadRepository->findByConditions(['phone_number' => $phoneNumber]);
        if ($hasUser) {
            throw  new UserRegisterException('手机号已经注册');
        }
        $userData              = new UserData;
        $userData->phone_numer = $phoneNumber;

        return $userData;

    }


}
