<?php

namespace RedJasmine\UserCore\Application\Services\Commands\Login;


use RedJasmine\UserCore\Domain\Services\Login\Data\UserLoginData;

class UserLoginOrRegisterCommand extends UserLoginData
{
    public string $provider;
}
