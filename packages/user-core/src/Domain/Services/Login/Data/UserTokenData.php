<?php

namespace RedJasmine\UserCore\Domain\Services\Login\Data;

use RedJasmine\Support\Foundation\Data\Data;

class UserTokenData extends Data
{

    public string $guard;
    public string $accessToken;
    public string $refreshToken;
    public string $tokenType = 'bearer';
    public int    $expire;

}
