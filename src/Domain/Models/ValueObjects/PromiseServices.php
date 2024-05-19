<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

class PromiseServices extends Data
{

    public PromiseServiceValue $refund;

    public PromiseServiceValue $exchange;


    public PromiseServiceValue $service;


    public PromiseServiceValue $guarantee;

    public function __construct()
    {
        $this->refund    = new PromiseServiceValue();
        $this->exchange  = new PromiseServiceValue();
        $this->service   = new PromiseServiceValue();
        $this->guarantee = new PromiseServiceValue();
    }

    public function toArray() : array
    {
        return [
            'refund'    => $this->refund->value(),
            'exchange'  => $this->exchange->value(),
            'service'   => $this->service->value(),
            'guarantee' => $this->guarantee->value(),
        ];
    }


}
