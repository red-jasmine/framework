<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Support\Data\Data;

class ChannelResult extends Data
{
    public bool $successFul;

    public ?string $result = null;

    public function getResult() : ?string
    {
        return $this->result;
    }

    public function setResult(?string $result) : static
    {
        $this->result = $result;
        return $this;
    }






    public function isSuccessFul() : bool
    {
        return $this->successFul;
    }

    public function setSuccessFul(bool $successFul) : static
    {
        $this->successFul = $successFul;
        return $this;
    }


}
