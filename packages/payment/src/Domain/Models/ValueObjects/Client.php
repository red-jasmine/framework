<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Payment\Domain\Models\Enums\ClientTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class Client extends Data
{
    #[WithCast(EnumCast::class, ClientTypeEnum::class)]
    public ?ClientTypeEnum $type;

    public ?string $name;

    public ?string $version;

    public ?string $ip;

    public ?string $agent;


}
