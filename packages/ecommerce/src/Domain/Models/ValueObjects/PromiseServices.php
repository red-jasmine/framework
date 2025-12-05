<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Support\Foundation\Data\Data;

class PromiseServices extends Data
{

    /**
     * 退款
     * @var PromiseServiceValue
     */
    public PromiseServiceValue $refund;

    /**
     * 换货
     * @var PromiseServiceValue
     */
    public PromiseServiceValue $exchange;


    /**
     * 售后服务
     * @var PromiseServiceValue
     */
    public PromiseServiceValue $service;


    /**
     * 保修
     * @var PromiseServiceValue
     */
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
