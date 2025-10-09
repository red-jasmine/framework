<?php

namespace RedJasmine\UserCore\Domain\Services\ChangeAccount\Data;

use RedJasmine\Support\Data\Data;

class UserChangeAccountData extends Data
{

    public string $provider;

    public ?string $ip;

    public ?string $ua;

    public ?string $version;

    public array $data;

}