<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;
use RedJasmine\Support\Helpers\ID\NoCheckNumber;

/**
 * @property string $uniqueNoKey
 * @method string[] buildUniqueNoFactors()
 */
trait HasUniqueNo
{

    public static function bootHasUniqueNo() : void
    {
        static::creating(function ($model) {
            /**
             * @var HasUniqueNo $model
             */
            $model->setUniqueNo();
        });
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
        $factors = [];
        if (method_exists($this, 'buildUniqueNoFactors')) {
            foreach ($this->buildUniqueNoFactors() as $factor) {
                $factors[] = $this->factorRemainder($factor);
            }
        } else {
            $factors[] = rand(10, 99);
            $factors[] = rand(10, 99);
            $factors[] = rand(10, 99);
            $factors[] = rand(10, 99);
            $factors[] = rand(10, 99);
        }
        return NoCheckNumber::generator(implode('', [
            DatetimeIdGenerator::buildId(),
            ...$factors
        ]));
    }


}