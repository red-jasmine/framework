<?php

namespace RedJasmine\Vip\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class UserVip extends Model implements OwnerInterface, OperatorInterface
{
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
        return Carbon::parse('9999-12-31 23:59:59');
    }


    public function vip() : BelongsTo
    {
        return $this->belongsTo(Vip::class, 'vip_id', 'id');
    }

    public function setForever() : void
    {
        $this->is_forever = true;
        $this->end_time   = $this->maxEndTime();
    }

    public function getCurrentEndTime() : Carbon
    {
        // 获取
        if ($this->isExpired()) {
            return Carbon::now();
        } else {
            return Carbon::parse($this->end_time->toDateTimeString());
        }

    }

    public function addEndTime(int $timeValue, TimeUnitEnum $timeUnit) : void
    {
        // 判断是否已经过期 如果已经过期了 则开始时间和结束时间从现在开始
        if ($this->isExpired()) {
            $this->start_time = now();
            $this->end_time   = now();
        }

        // 如果为永久
        if ($timeUnit === TimeUnitEnum::FOREVER) {
            $this->setForever();
        } else {
            $this->end_time = $this->end_time ?? now();
            $this->end_time = $this->end_time->add($timeUnit->value, $timeValue);
        }
    }


    public function isExpired() : bool
    {
        if ($this->is_forever === true) {
            return false;
        }
        if ($this->end_time && $this->end_time->gt(now())) {
            return false;
        }
        return true;

    }
}
