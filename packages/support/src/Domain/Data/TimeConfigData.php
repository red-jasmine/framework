<?php

namespace RedJasmine\Support\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;

/**
 * 时间配置
 *
 */
class TimeConfigData extends Data
{
    public int $value = 0;


    public TimeUnitEnum $unit = TimeUnitEnum::NEVER;


    public function afterAt(Carbon $carbon = null) : Carbon
    {
        $carbon = $carbon ?? Carbon::now();
        $carbon = $carbon->clone();

        return match ($this->unit) {
            TimeUnitEnum::SECOND => $carbon->addSeconds($this->value),
            TimeUnitEnum::MINUTE => $carbon->addMinutes($this->value),
            TimeUnitEnum::HOUR => $carbon->addHours($this->value),
            TimeUnitEnum::DAY => $carbon->addDays($this->value),
            TimeUnitEnum::MONTH => $carbon->addMonths($this->value),
            TimeUnitEnum::QUARTER => $carbon->addMonths($this->value * 3),
            TimeUnitEnum::YEAR => $carbon->addYears($this->value),
            TimeUnitEnum::FOREVER => $carbon->addYears(1000),
            default => $carbon,
        };

    }

}