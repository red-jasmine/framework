<?php

namespace RedJasmine\ResourceUsage\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\ResourceUsage\Domain\Models\Enums\ResourceUsageModeEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;

class UseResourceData extends Data
{
    public UserInterface $owner;

    // 资源使用模式
    #[WithCast(EnumCast::class, ResourceUsageModeEnum::class)]
    public ResourceUsageModeEnum $mode;

    public string $appId;

    public string $name;

    public int $quantity;

    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public Carbon $time;

}