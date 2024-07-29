<?php

namespace RedJasmine\Order\Domain\Models\ValueObjects;

use RedJasmine\Order\Domain\Models\Casts\PromiseServiceValueCastTransformer;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCastAndTransformer;

class PromiseServices extends Data
{

    // TODO 这里应该可以设置全局的配置？
    #[WithCastAndTransformer(PromiseServiceValueCastTransformer::class)]
    public PromiseServiceValue $refund;

    #[WithCastAndTransformer(PromiseServiceValueCastTransformer::class)]
    public PromiseServiceValue $exchange;

    #[WithCastAndTransformer(PromiseServiceValueCastTransformer::class)]
    public PromiseServiceValue $service;

    #[WithCastAndTransformer(PromiseServiceValueCastTransformer::class)]
    public PromiseServiceValue $guarantee;

    public function __construct()
    {
        $this->refund    = new PromiseServiceValue();
        $this->exchange  = new PromiseServiceValue();
        $this->service   = new PromiseServiceValue();
        $this->guarantee = new PromiseServiceValue();
    }


}
