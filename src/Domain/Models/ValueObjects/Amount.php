<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use http\Exception\InvalidArgumentException;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;
use RedJasmine\Support\Domain\ValueObjects\Exception;


class Amount extends ValueObject
{


    protected int $scale = 2;
    /**
     * @var string
     */
    private string $value;

    /**
     * @param  float|int|string|Amount|null  $value
     *
     */
    public function __construct(float|int|string|null|self $value = 0)
    {
        if ($value instanceof static) {
            $value = $value->value();
        }
        $this->value = bcadd($value, 0, $this->scale);
    }

    public function value() : string
    {
        return bcadd($this->value, 0, $this->scale);
    }

    public static function make() : Amount
    {
        $args = func_get_args();
        return new static(...$args);
    }

    /**
     * @return void
     */
    public function validateMin() : void
    {
        if (bccomp($this->value, 0, $this->scale) < 0) {
            throw new InvalidArgumentException('金额不能小于0');
        }
    }

    public function getScale() : int
    {
        return $this->scale;
    }

    public function setScale(int $scale) : Amount
    {
        $this->scale = $scale;
        return $this;
    }

    public function __toString() : string
    {
        return $this->value();
    }

    public function sub($value) : self
    {
        $this->setValue(bcadd($this->value, (string) $value, $this->scale));
        return $this;
    }

    protected function setValue($value) : void
    {
        $this->value = bcadd($value, 0, $this->scale);
    }

    public function add($value) : self
    {
        $this->setValue(bcadd($this->value, (string) $value, $this->scale));
        return $this;
    }

    public function bcmul($value) : self
    {
        $this->setValue(bcmul($this->value, (string) $value, $this->scale));
        return $this;
    }


}
