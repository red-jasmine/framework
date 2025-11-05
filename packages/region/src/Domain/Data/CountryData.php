<?php

namespace RedJasmine\Region\Domain\Data;

use RedJasmine\Support\Data\Data;

/**
 * 国家数据传输对象
 */
class CountryData extends Data
{
    /**
     * 国家代码 ISO 3166-1 alpha-2
     */
    public string $code;

    /**
     * 国家代码 ISO 3166-1 alpha-3
     */
    public string $isoAlpha3;

    /**
     * 名称
     */
    public string $name;

    /**
     * 本地名称
     */
    public ?string $native = null;

    /**
     * 大区
     */
    public ?string $region = null;

    /**
     * 货币代码 ISO 4217
     */
    public string $currency;

    /**
     * 电话区号
     */
    public ?string $phoneCode = null;

    /**
     * 经度
     */
    public ?float $longitude = null;

    /**
     * 纬度
     */
    public ?float $latitude = null;

    /**
     * 时区列表
     */
    public ?array $timezones = null;

    /**
     * 翻译信息
     */
    public ?array $translations = null;
}

