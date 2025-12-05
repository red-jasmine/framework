<?php

namespace RedJasmine\UserCore\Domain\Data;

use RedJasmine\Support\Foundation\Data\Data;

class UserSetAccountData extends Data
{
    public ?string $phone = null;
    public ?string $email = null;
    public string  $name;
}