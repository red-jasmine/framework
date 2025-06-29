<?php

namespace RedJasmine\User\Domain\Services\Register\Data;

use RedJasmine\Support\Data\Data;

class UserRegisterData extends Data
{
    // 注册类型
    public string $provider;

    public ?string $ip;

    public ?string $ua;

    public ?string $version;

    public array $data;


    /**
     * 更多上下文参数
     * @var array|null
     */
    public ?array $context = [];

}
