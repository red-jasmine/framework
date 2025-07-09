<?php

namespace RedJasmine\Coupon\Domain\Models\ValueObjects;

use Carbon\Carbon;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class ValidityRule extends ValueObject
{
    /**
     * 有效期类型
     */
    public ValidityTypeEnum $validityType;

    /**
     * 开始时间
     */
    public ?Carbon $startTime;

    /**
     * 结束时间
     */
    public ?Carbon $endTime;

    /**
     * 相对天数
     */
    public ?int $relativeDays;

    public function __construct(array $data = [])
    {
        $this->validityType = $data['validityType'] ?? ValidityTypeEnum::ABSOLUTE;
        $this->startTime = $data['startTime'] ? Carbon::parse($data['startTime']) : null;
        $this->endTime = $data['endTime'] ? Carbon::parse($data['endTime']) : null;
        $this->relativeDays = $data['relativeDays'] ?? null;
    }

    /**
     * 检查是否有效
     */
    public function isValid(?Carbon $checkTime = null): bool
    {
        $checkTime = $checkTime ?? Carbon::now();

        return match ($this->validityType) {
            ValidityTypeEnum::ABSOLUTE => $this->isAbsoluteValid($checkTime),
            ValidityTypeEnum::RELATIVE => true, // 相对时间在发放时才确定有效期
        };
    }

    /**
     * 获取生效时间
     */
    public function getEffectiveTime(?Carbon $issueTime = null): Carbon
    {
        $issueTime = $issueTime ?? Carbon::now();

        return match ($this->validityType) {
            ValidityTypeEnum::ABSOLUTE => $this->startTime ?? $issueTime,
            ValidityTypeEnum::RELATIVE => $issueTime,
        };
    }

    /**
     * 获取过期时间
     */
    public function getExpireTime(?Carbon $issueTime = null): Carbon
    {
        $issueTime = $issueTime ?? Carbon::now();

        return match ($this->validityType) {
            ValidityTypeEnum::ABSOLUTE => $this->endTime ?? $issueTime->addYears(1),
            ValidityTypeEnum::RELATIVE => $issueTime->addDays($this->relativeDays ?? 30),
        };
    }

    /**
     * 检查绝对时间有效性
     */
    private function isAbsoluteValid(Carbon $checkTime): bool
    {
        if ($this->startTime && $checkTime->isBefore($this->startTime)) {
            return false;
        }

        if ($this->endTime && $checkTime->isAfter($this->endTime)) {
            return false;
        }

        return true;
    }

    /**
     * 获取剩余天数
     */
    public function getRemainingDays(?Carbon $issueTime = null): int
    {
        $expireTime = $this->getExpireTime($issueTime);
        $now = Carbon::now();

        if ($expireTime->isBefore($now)) {
            return 0;
        }

        return $now->diffInDays($expireTime);
    }

    /**
     * 获取显示文案
     */
    public function getDisplayText(): string
    {
        return match ($this->validityType) {
            ValidityTypeEnum::ABSOLUTE => "有效期：{$this->startTime?->format('Y-m-d H:i')} ~ {$this->endTime?->format('Y-m-d H:i')}",
            ValidityTypeEnum::RELATIVE => "有效期：领取后{$this->relativeDays}天内有效",
        };
    }

    public function equals(object $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->validityType === $other->validityType
            && $this->startTime?->eq($other->startTime)
            && $this->endTime?->eq($other->endTime)
            && $this->relativeDays === $other->relativeDays;
    }

    public function hashCode(): int
    {
        return crc32(
            $this->validityType->value .
            ($this->startTime?->timestamp ?? '') .
            ($this->endTime?->timestamp ?? '') .
            ($this->relativeDays ?? '')
        );
    }
} 