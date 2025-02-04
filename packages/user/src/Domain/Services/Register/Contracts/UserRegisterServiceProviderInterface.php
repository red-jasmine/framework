<?php

namespace RedJasmine\User\Domain\Services\Register\Contracts;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

interface UserRegisterServiceProviderInterface
{
    public function register(UserRegisterData $data) : User;
}
