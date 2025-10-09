<?php

namespace RedJasmine\UserCore\Domain\Services\ForgotPassword;

use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\Contracts\UserForgotPasswordServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\Data\ForgotPasswordData;
use RedJasmine\UserCore\Domain\Services\ForgotPassword\Facades\UserForgotPasswordServiceProvider;

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