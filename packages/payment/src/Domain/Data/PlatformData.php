<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Data\Data;

class PlatformData extends Data
{

    public string $code;

    public string $name;

    public string $icon;

    public ?string $remarks = null;


}
