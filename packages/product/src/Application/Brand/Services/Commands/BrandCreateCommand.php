<?php

namespace RedJasmine\Product\Application\Brand\Services\Commands;

use Illuminate\Validation\Rules\Enum;
use RedJasmine\Product\Domain\Brand\Data\BrandData;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class BrandCreateCommand extends BrandData
{

}
