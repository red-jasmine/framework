<?php

namespace RedJasmine\User\Domain\Services\Login\Data;

use RedJasmine\Support\Data\Data;


class UserLoginData extends Data
{
    // 登陆驱动类型
    public string $provider;

    public ?string $ip;

    public ?string $ua;

    public ?string $version;

}
