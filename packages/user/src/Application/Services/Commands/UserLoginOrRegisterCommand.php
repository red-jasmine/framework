<?php

namespace RedJasmine\User\Application\Services\Commands;


use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class UserLoginOrRegisterCommand extends UserLoginData
{
    public string $provider;
}
