<?php

namespace RedJasmine\Logistics\Domain\Data;

use RedJasmine\Logistics\Domain\Models\Enums\Companies\CompanyTypeEnum;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class LogisticsCompanyData extends Data
{

    #[WithCast(EnumCast::class, CompanyTypeEnum::class)]
    public CompanyTypeEnum     $type;
    #[WithCast(EnumCast::class, UniversalStatusEnum::class)]
    public UniversalStatusEnum $status = UniversalStatusEnum::ENABLE;


    public string $code;
    public string $name;
    public ?string $logo;
    public ?string $tel;
    public ?string $url;
}