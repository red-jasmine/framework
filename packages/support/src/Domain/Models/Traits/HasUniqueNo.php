<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;
use RedJasmine\Support\Helpers\ID\NoCheckNumber;

/**
 *
 * @property  string $uniqueNoKey
 * @method string[] buildUniqueNoFactors()
 */
trait HasUniqueNo
{


    public static function getUniqueNoKey() : string
    {
        return static::$uniqueNoKey;
    }

    public static function bootHasUniqueNo() : void
    {
        static::creating(function ($model) {
            /**
             * @var HasUniqueNo $model
             */
            $model->setUniqueNo();
        });
    }

    /**
     * @param  string  $no
     *
     * @return bool
     * @throws Exception
     */
    public static function checkUniqueNo(string $no) : bool
    {
        return NoCheckNumber::chack($no) ? true : throw new Exception('Invalid unique no');
    }

    /**
     * @param  Builder  $query
     * @param  string  $no
     *
     * @return Builder
     * @throws Exception
     */
    public function scopeUniqueNo(Builder $query, string $no) : Builder
    {
        static::checkUniqueNo($no);
        return $query->where($this->uniqueNoKey, $no);
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