<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Support\Foundation\Data\Data;

class Store extends Data
{

    public ?string $type;
    public ?string $name;
    public ?string $id;

}
