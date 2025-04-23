<?php

namespace RedJasmine\User\Application\Services\Commands;


use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class UserLoginCaptchaCommand extends UserLoginData
{
    public string $provider;
}
