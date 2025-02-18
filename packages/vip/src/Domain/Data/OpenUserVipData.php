<?php

namespace RedJasmine\Vip\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;

class OpenUserVipData extends Data
{

    public UserInterface $owner;
    public string        $appID;
    public string        $type;
    public bool          $isForever = false;
    public int           $timeValue = 1;
    #[WithCast(EnumCast::class, TimeUnitEnum::class)]
    public TimeUnitEnum  $timeUnit  = TimeUnitEnum::MONTH; // 时间单位


}