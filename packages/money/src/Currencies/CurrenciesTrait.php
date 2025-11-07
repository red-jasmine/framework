<?php

declare(strict_types=1);

namespace RedJasmine\Money\Currencies;

use Money\Currencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

/**
 * 货币工具特性
 * 提供默认货币、货币解析、符号等能力
 * 统一使用 AggregateCurrencies 管理所有货币
 */
trait CurrenciesTrait
{
    /**
     * 默认货币代码
     */
    protected static ?string $defaultCurrency = null;

    /**
     * 货币集合实例（统一使用 AggregateCurrencies）
     */
    protected static ?AggregateCurrencies $currencies = null;

    /**
     * 解析货币对象
     *
     * @param Currency|string $currency
     * @return Currency
     */
    public static function parseCurrency(Currency|string $currency): Currency
    {
        return is_string($currency) ? new Currency($currency) : $currency;
    }

    /**
     * 获取货币符号
     *
     * @param Currency|string $currency
     * @return string|null
     */
    public static function getCurrencySymbol(Currency|string $currency): ?string
    {
        $currency = static::parseCurrency($currency);
        $currencies = static::getCurrencies();

        // AggregateCurrencies 可能没有 getSymbol 方法，使用 NumberFormatter 获取
        try {
            $locale = config('app.locale', 'zh_CN');
            $numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            $pattern = $numberFormatter->formatCurrency(0, $currency->getCode());
            $symbol = trim(preg_replace('/[\d\s.,]+/', '', $pattern));
            return $symbol ?: $currency->getCode();
        } catch (\Throwable $e) {
            return $currency->getCode();
        }
    }

    /**
     * 验证货币是否有效
     *
     * @param Currency|string $currency
     * @return bool
     */
    public static function isValidCurrency(Currency|string $currency): bool
    {
        return static::getCurrencies()->contains(static::parseCurrency($currency));
    }

    /**
     * 获取默认货币代码
     *
     * @return string
     */
    public static function getDefaultCurrency(): string
    {
        if (static::$defaultCurrency === null) {
            static::setDefaultCurrency(
                config('money.default_currency')
                ?? config('app.currency')
                ?? 'CNY'
            );
        }

        return static::$defaultCurrency;
    }

    /**
     * 设置默认货币代码
     *
     * @param string $currency
     * @return void
     */
    public static function setDefaultCurrency(string $currency): void
    {
        static::$defaultCurrency = $currency;
    }

    /**
     * 获取 ISO 货币列表
     *
     * @return array
     */
    public static function getISOCurrencies(): array
    {
        $isoCurrencies = new ISOCurrencies();
        $currencies = [];

        foreach ($isoCurrencies as $currency) {
            $currencies[$currency->getCode()] = [
                'code' => $currency->getCode(),
                'subunit' => $isoCurrencies->subunitFor($currency),
            ];
        }

        return $currencies;
    }

    /**
     * 获取货币集合（统一使用 AggregateCurrencies）
     *
     * @return AggregateCurrencies
     */
    public static function getCurrencies(): AggregateCurrencies
    {
        if (static::$currencies === null) {
            static::setCurrencies(config('money.currencies', []));
        }

        return static::$currencies;
    }

    /**
     * 设置货币集合
     * 如果传入数组，则使用 AggregateCurrencies::make() 创建
     * 如果传入 Currencies 实例，则转换为 AggregateCurrencies
     *
     * @param Currencies|array|null $currencies
     * @return void
     */
    public static function setCurrencies(Currencies|array|null $currencies): void
    {
        if ($currencies instanceof AggregateCurrencies) {
            static::$currencies = $currencies;
            return;
        }

        if ($currencies instanceof Currencies) {
            // 如果不是 AggregateCurrencies，则创建一个新的 AggregateCurrencies 包装它
            static::$currencies = AggregateCurrencies::make([
                'iso' => 'all', // 假设是 ISO 货币
            ]);
            return;
        }

        // 数组配置或 null，使用 AggregateCurrencies::make() 创建
        $config = is_array($currencies) ? $currencies : [];
        static::$currencies = AggregateCurrencies::make($config);
    }

    /**
     * 重置货币集合
     *
     * @return void
     */
    public static function resetCurrencies(): void
    {
        static::$currencies = null;
        AggregateCurrencies::resetInstance();
    }
}


