<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Foundation\Data\Data;

class NotifyResponseData extends Data
{

    public int $statusCode = 0;


    public ?string $body = null;


    public function isSuccessFul() : bool
    {
        if ($this->body === 'success') {
            return true;
        } else {
            return false;
        }
    }

}
