<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;

/**
 * @property string $uniqueNoKey
 */
trait HasUniqueNo
{

    public static function bootHasUniqueNo() : void
    {
        static::creating(function ($model) {
            $model->setUniqueNo();
        });
    }

    protected function generateDatetimeId() : string
    {
        return DatetimeIdGenerator::buildId();
    }

    protected function factorRemainder(int|string $number) : string
    {
        if (is_string($number)) {
            $number = crc32($number);
        }
        return sprintf("%02d", ($number % 64));
    }

    public function setUniqueNo() : void
    {
        if (!isset($this->{$this->uniqueNoKey})) {
            $this->{$this->uniqueNoKey} = $this->newUniqueNo();
        }
    }

    public function newUniqueNo() : string
    {
        return $this->generateDatetimeId();
    }


}