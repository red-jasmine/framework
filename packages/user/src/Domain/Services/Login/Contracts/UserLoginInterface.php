<?php

namespace RedJasmine\User\Domain\Services\Login\Contracts;

interface UserLoginInterface
{
    public function login(array $params = []);
}
