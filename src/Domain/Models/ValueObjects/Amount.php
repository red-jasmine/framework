<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Support\Jsonable;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;
use RedJasmine\Support\Domain\ValueObjects\Exception;


class Amount extends ValueObject implements Jsonable
{


    protected int $scale = 2;

    public function getScale() : int
    {
        return $this->scale;
    }

    public function setScale(int $scale) : Amount
    {
        $this->scale = $scale;
        return $this;
    }


    public static function make() : Amount
    {
        $args = func_get_args();
        return new static(...$args);
    }

    /**
     * @var string
     */
    private string $value;

    /**
     * @param  float|int|string|null  $value
     *
     * @throws Exception
     */
    public function __construct(float|int|string|null $value = 0)
    {

        $this->value = bcadd($value, 0, $this->scale);

        $this->validateMin();
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

    public function value() : string
    {
        return bcadd($this->value, 0, $this->scale);
    }

    protected function setValue($value) : void
    {
        $this->value = bcadd($value, 0, $this->scale);
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
