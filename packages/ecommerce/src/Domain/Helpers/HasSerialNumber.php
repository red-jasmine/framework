<?php

namespace RedJasmine\Ecommerce\Domain\Helpers;

/**
 * 序列号
 */
trait HasSerialNumber
{


    protected ?string $serialNumber;

    public function buildSerialNumber() : void
    {
        $this->serialNumber = md5(uniqid());
    }

    public function getSerialNumber() : string
    {
        if (!isset($this->serialNumber)) {
            $this->buildSerialNumber();
        }
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber) : void
    {
        $this->serialNumber = $serialNumber;
    }


}