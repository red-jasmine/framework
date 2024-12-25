<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Data\Data;

class NotifyData extends Data
{

    public string $businessType;

    public string $businessNo;

    public string $notifyUrl;

    public array $request;
}
