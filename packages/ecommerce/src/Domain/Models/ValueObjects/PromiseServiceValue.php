<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use InvalidArgumentException;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;


class PromiseServiceValue extends ValueObject
{

    public const   UNSUPPORTED     = 'unsupported';
    public const   BEFORE_SHIPMENT = 'before_shipping';


    public function enums() : array
    {
        return [
            self::UNSUPPORTED     => '不支持',
            self::BEFORE_SHIPMENT => '发货前',
        ];
    }


    public function getEnums() : array
    {
        return $this->enums();
    }

    protected bool $isEnum = false;

    public function isEnum() : bool
    {
        return $this->isEnum;
    }

    public function isTime() : bool
    {
        return !$this->isEnum();
    }


    private string $value;

    public function __construct(string $value = self::UNSUPPORTED)
    {
        $this->isValid($value);
        $this->value = $value;
    }

    protected function allowTimeSuffix() : array
    {
        return [
            'minute',
            'hour',
            'day',
            'month',
            'year'
        ];

    }

    protected function isValid(string $value) : bool
    {

        if (in_array($value, array_keys($this->enums), true)) {
            $this->isEnum = true;
            return true;
        }
        $this->validateDeadline($value);


        return true;
    }

    /**
     * 格式 阶段+截止时间
     *
     * @param string $item
     *
     * @return void
     */
    protected function validateDeadline(string $item) : void
    {

        $allowTimeSuffixString = implode('|', $this->allowTimeSuffix());
        $pattern               = "/^[1-9]+\d*($allowTimeSuffixString)$/";
        if ((bool)preg_match($pattern, $item) === false) {
            throw new InvalidArgumentException('参数值错误:' . $item);
        }

    }

    public function value() : string
    {
        return $this->value;
    }


}
