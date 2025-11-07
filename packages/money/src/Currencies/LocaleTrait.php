<?php

declare(strict_types=1);

namespace RedJasmine\Money\Currencies;

/**
 * 区域设置特性
 * 提供区域设置管理功能
 */
trait LocaleTrait
{
    /**
     * 区域设置
     */
    protected static ?string $locale = null;

    /**
     * 获取区域设置
     */
    public static function getLocale(): string
    {
        if (static::$locale === null) {
            static::setLocale(config('money.locale', config('app.locale', 'zh_CN')));
        }

        return static::$locale;
    }

    /**
     * 设置区域设置
     */
    public static function setLocale(string $locale): void
    {
        static::$locale = $locale;
    }
}

