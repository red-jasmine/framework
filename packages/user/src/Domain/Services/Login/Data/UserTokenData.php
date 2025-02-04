<?php

namespace RedJasmine\User\Domain\Services\Login\Data;

use RedJasmine\Support\Data\Data;

class UserTokenData extends Data
{
    public string $accessToken;
    public string $refreshToken;
    public string $tokenType = 'bearer';
    public int    $expire;

}
