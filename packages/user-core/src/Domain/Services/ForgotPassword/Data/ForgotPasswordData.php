<?php

namespace RedJasmine\UserCore\Domain\Services\ForgotPassword\Data;

use RedJasmine\Support\Foundation\Data\Data;

class ForgotPasswordData extends Data
{
    public string $provider;

    public ?string $ip;

    public ?string $ua;

    public ?string $version;

    public array $data;

    public ?string $password;
}