<?php

namespace RedJasmine\Region\Domain\Data;

use RedJasmine\Region\Domain\Enums\RegionTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 行政区划数据传输对象
 */
class RegionData extends Data
{
    /**
     * 代码
     */
    public string $code;

    /**
     * 父级编码
     */
    public ?string $parentCode = null;

    /**
     * 国家代码 ISO 3166-1 alpha-2
     */
    public string $countryCode;

    /**
     * 类型
     */
    #[WithCast(EnumCast::class, RegionTypeEnum::class)]
    public RegionTypeEnum $type;

    /**
     * 名称
     */
    public string $name;

    /**
     * 大区
     */
    public ?string $region = null;

    /**
     * 树层级
     */
    public int $level = 0;
}

