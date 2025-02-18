<?php

namespace RedJasmine\Vip\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class UserVip extends Model implements OwnerInterface, OperatorInterface
{
    use SoftDeletes;

    public $incrementing = false;
    use HasSnowflakeId;
    use HasOperator;
    use HasOwner;


    protected function casts() : array
    {
        return [
            'start_time' => 'datetime',
            'end_time'   => 'datetime',
            'is_forever' => 'boolean'
        ];
    }


    public function defaultLevel() : int
    {
        return 1;
    }

    public function maxEndTime() : Carbon
    {
        return Carbon::parse('9999-12-31 00:00:00');
    }


    public function setForever() : void
    {
        $this->is_forever = true;
        $this->end_time   = $this->maxEndTime();
    }

    public function addEndTime(int $timeValue, TimeUnitEnum $timeUnit) : void
    {
        $this->end_time->add($timeUnit->value, $timeValue);
    }
}
