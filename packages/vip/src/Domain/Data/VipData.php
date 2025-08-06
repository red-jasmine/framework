<?php

namespace RedJasmine\Vip\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Vip\Domain\Models\Enums\VipStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class VipData extends Data
{
    public string $biz;

    public string $type;

    public string $name;

    public int $level = 1;

    #[WithCast(EnumCast::class, VipStatusEnum::class)]
    public VipStatusEnum $status = VipStatusEnum::ENABLE;

    public ?string $icon;

    public ?string $description;

    public ?array $extra;


}