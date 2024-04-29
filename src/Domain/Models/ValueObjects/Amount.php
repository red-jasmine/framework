<?php

namespace RedJasmine\Support\Domain\Models\ValueObjects;

use http\Exception\InvalidArgumentException;
use RedJasmine\Support\Domain\ValueObjects\Exception;


class Amount extends ValueObject
{

    /**
     * @var string
     */
    private string $value;

    /**
     * @param float|int|string|null $value
     *
     * @throws Exception
     */
    public function __construct(float|int|string|null $value = 0)
    {

        $this->value = bcadd($value, 0, 2);

        $this->validateMin();
    }


    /**
     * @return void
     */
    public function validateMin() : void
    {
        if (bccomp($this->value, 0, 2) < 0) {
            throw new InvalidArgumentException('金额不能小于0');
        }
    }

    public function value() : string
    {
        return bcadd($this->value, 0, 2);
    }

    public function __toString() : string
    {
        return $this->value();
    }

}
