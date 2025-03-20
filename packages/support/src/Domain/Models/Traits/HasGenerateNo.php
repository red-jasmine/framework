<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;

/**
 * @property string $generateNoKey
 * @method  array<int> generateNoFactors
 */
trait HasGenerateNo
{

    public static function bootHasGenerateNo() : void
    {
        static::creating(function ($model) {
            $model->{$model->generateNoKey} = $model->generateNo();
        });
    }


    protected function generateNo() : string
    {
        // 14位时间YYYYMMDDHHIISS + 10位序号
        return implode('', [
            DatetimeIdGenerator::buildId(), // 24位
            ...$this->generateNoFactors()
        ]);
    }

    protected function factorRemainder(int|string $number) : string
    {
        if (is_string($number)) {
            $number = crc32($number);
        }
        return sprintf("%02d", ($number % 64));
    }

}