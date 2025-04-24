<?php

namespace RedJasmine\User\Domain\Services\ForgotPassword\Contracts;

use RedJasmine\User\Domain\Services\ForgotPassword\Data\ForgotPasswordData;

interface UserForgotPasswordServiceProviderInterface
{
    public function captcha(ForgotPasswordData $data);

    public function verify(ForgotPasswordData $data) : int;

}