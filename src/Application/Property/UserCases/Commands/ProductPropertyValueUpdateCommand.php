<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyValueUpdateCommand extends ProductPropertyValueCreateCommand
{
    public int $id;

}
