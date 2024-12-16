<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Support\Data\Data;

class PurchaseResult extends Data
{

    public bool $successFul;

    public function isSuccessFul() : bool
    {
        return $this->successFul;
    }

    public function setSuccessFul(bool $successFul) : PurchaseResult
    {
        $this->successFul = $successFul;
        return $this;
    }


}
