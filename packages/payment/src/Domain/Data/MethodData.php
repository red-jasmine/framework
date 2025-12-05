<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Foundation\Data\Data;

class MethodData extends Data
{

    public string $code;

    public string $name;

    public string $icon;

    public ?string $remarks = null;


}
