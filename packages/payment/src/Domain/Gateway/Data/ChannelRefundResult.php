<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

class ChannelRefundResult extends AbstractChannelResult
{


    public mixed   $result  = null;



    public ?string $tradeNo = null;



    public function getTradeNo() : ?string
    {
        return $this->tradeNo;
    }

    public function setTradeNo(?string $tradeNo) : static
    {
        $this->tradeNo = $tradeNo;
        return $this;
    }


    public function getResult() : mixed
    {
        return $this->result;
    }

    public function setResult(mixed $result) : static
    {
        $this->result = $result;
        return $this;
    }


}
