<?php

namespace RedJasmine\User\Domain\Services\Login\Data;

use RedJasmine\Support\Data\Data;

class UserTokenData extends Data
{
    public string $token;
    public string $refreshToken;
    public int    $expire;

}
