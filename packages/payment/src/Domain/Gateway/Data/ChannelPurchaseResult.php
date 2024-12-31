<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Payment\Domain\Data\PaymentTrigger;

class ChannelPurchaseResult extends AbstractChannelResult
{

    public ?PaymentTrigger $paymentTrigger = null;

    public mixed   $result  = null;
    public ?string $code    = null;
    public ?string $message = null;


    public ?string $tradeNo = null;

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function setCode(?string $code) : static
    {
        $this->code = $code;
        return $this;
    }

    public function getMessage() : ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message) : static
    {
        $this->message = $message;
        return $this;
    }


    /**
     * The data contained in the response.
     *
     * @var mixed
     */
    protected mixed $data;

    public function getData() : mixed
    {
        return $this->data;
    }

    public function setData(mixed $data) : static
    {
        $this->data = $data;
        return $this;
    }


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
