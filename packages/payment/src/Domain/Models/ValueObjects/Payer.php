<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Support\Foundation\Data\Data;

class Payer extends Data
{

    public ?string $type;

    public ?string $userId;

    public ?string $appId;

    public ?string $openId;

    public ?string $name;

    public ?string $account;


}
