<?php

namespace RedJasmine\UserCore\Domain\Services\Login\Data;

use RedJasmine\Support\Foundation\Data\Data;


class UserLoginData extends Data
{
    // 登陆驱动类型
    public string $provider;

    public ?string $ip;

    public ?string $ua;

    public ?string $version;

    public array $data;


}
