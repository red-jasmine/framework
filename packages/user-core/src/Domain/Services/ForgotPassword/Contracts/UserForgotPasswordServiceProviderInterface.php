<?php

namespace RedJasmine\UserCore\Domain\Services\ForgotPassword\Contracts;

use RedJasmine\UserCore\Domain\Services\ForgotPassword\Data\ForgotPasswordData;

interface UserForgotPasswordServiceProviderInterface
{
    public function captcha(ForgotPasswordData $data);

    public function verify(ForgotPasswordData $data) : int;

}