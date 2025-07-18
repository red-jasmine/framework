<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;
use RedJasmine\Support\Helpers\ID\NoCheckNumber;

/**
 *
 * @property string $uniqueNoKey
 * @property ?string $uniqueNoPrefix
 * @method string[] buildUniqueNoFactors()
 */
trait HasUniqueNo
{

    /**
     * @return string|null
     */
    public static function getUniqueNoPrefix() : ?string
    {
        if (isset(static::$uniqueNoPrefix)) {
            return static::$uniqueNoPrefix;
        }
        return null;
    }


    public static function getUniqueNoKey() : string
    {
        return static::$uniqueNoKey ?? 'no';
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
        if ($prefix = static::getUniqueNoPrefix()) {

            if (!Str::startsWith($no, $prefix)) {
                return false;
            }
        }
        return NoCheckNumber::chack($no);
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
        if (static::checkUniqueNo($no) === false) {
            throw new Exception('Invalid unique no');
        }
        return $query->where(static::getUniqueNoKey(), $no);
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
        if (!isset($this->{static::getUniqueNoKey()})) {
            $this->{static::getUniqueNoKey()} = $this->newUniqueNo();
        }
    }

    public function newUniqueNo() : string
    {
        $factors = [];
        if ($prefix = static::getUniqueNoPrefix()) {
            $factors[] = $prefix;
        }
        $factors[] = DatetimeIdGenerator::buildId();

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
        return NoCheckNumber::generator(implode('', $factors));
    }


}