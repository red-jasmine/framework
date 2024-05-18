<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Ecommerce\Domain\Models\Casts\PromiseServiceValueCastTransformer;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCastAndTransformer;

class PromiseServices extends Data
{
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
