<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Support\Foundation\Data\Data;

class Sdk extends Data
{

    public string $name;


    public string $version;
}
