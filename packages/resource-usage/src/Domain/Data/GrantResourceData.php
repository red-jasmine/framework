<?php

namespace RedJasmine\ResourceUsage\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class GrantResourceData extends Data
{
    public UserInterface $owner;

    public string $appId;

    public string $name;

    public ?string $tag;

    public int $quantity;

    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public Carbon $startTime;
    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public Carbon $endTime;


}