<?php

namespace RedJasmine\User\Domain\Services\ForgotPassword;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\User\Domain\Services\ForgotPassword\Contracts\UserForgotPasswordServiceProviderInterface;
use RedJasmine\User\Domain\Services\ForgotPassword\Data\ForgotPasswordData;
use RedJasmine\User\Domain\Services\ForgotPassword\Facades\UserForgotPasswordServiceProvider;

class ForgotPasswordService
{

    public function __construct(
        public UserRepositoryInterface $repository,
    ) {
    }

    // 通过验证码 设置密码
    protected function getProvider(ForgotPasswordData $data) : UserForgotPasswordServiceProviderInterface
    {
        return UserForgotPasswordServiceProvider::create($data->provider);
    }

    public function captcha(ForgotPasswordData $data) : bool
    {
        $provider = $this->getProvider($data);
        $provider->captcha($data);
        return true;
    }


    public function resetPassword(ForgotPasswordData $data) : User
    {
        $provider = $this->getProvider($data);
        $id       = $provider->verify($data);// 进行外部验证


        $user = $this->repository->find($id);


        $user->setPassword($data->password);

        return $user;
    }

}