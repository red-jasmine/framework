<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Support\Data\Data;

/**
 * 渠道返回的结果
 */
abstract class AbstractChannelResult extends Data
{

    public bool $successFul;

    public function isSuccessFul() : bool
    {
        return $this->successFul;
    }

    public function setSuccessFul(bool $successFul) : static
    {
        $this->successFul = $successFul;
        return $this;
    }


    public ?string $code    = null;
    public ?string $message = null;
    /**
     * The data contained in the response.
     *
     * @var mixed
     */
    protected mixed $data;

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


    public function getData() : mixed
    {
        return $this->data;
    }

    public function setData(mixed $data) : static
    {
        $this->data = $data;
        return $this;
    }


}
