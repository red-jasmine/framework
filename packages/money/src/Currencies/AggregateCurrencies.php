<?php

namespace RedJasmine\Money\Currencies;

use Money\Currencies;
use Money\Currencies\AggregateCurrencies as BaseAggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Symfony\Component\Intl\Currencies as IntlCurrencies;
use Traversable;

/**
 * 聚合货币类
 * 聚合 ISO 货币、比特币货币和自定义货币
 */
class AggregateCurrencies implements Currencies
{
    private static ?AggregateCurrencies $instance = null;

    private BaseAggregateCurrencies $aggregate;

    /**
     * @param Currencies[] $currencies
     */
    private function __construct(array $currencies)
    {
        $this->aggregate = new BaseAggregateCurrencies($currencies);
    }

    /**
     * 创建货币聚合器
     */
    public static function make(array $config = []): self
    {
        $currencies = [];

        // ISO 货币
        if (!empty($config['iso'])) {
            if ($config['iso'] === 'all' || (is_array($config['iso']) && !empty($config['iso']))) {
                $currencies[] = new ISOCurrencies();
            }
        }

        // 比特币货币
        if (!empty($config['bitcoin'])) {
            $currencies[] = new BitcoinCurrencies();
        }

        // 自定义货币
        if (!empty($config['custom']) && is_array($config['custom'])) {
            $currencies[] = new CustomCurrencies($config['custom']);
        }

        return new self($currencies);
    }

    /**
     * 获取单例实例
     */
    public static function getInstance(array $config = []): self
    {
        if (self::$instance === null) {
            self::$instance = self::make($config);
        }

        return self::$instance;
    }

    /**
     * 重置单例实例
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }

    public function contains(Currency $currency): bool
    {
        return $this->aggregate->contains($currency);
    }

    public function subunitFor(Currency $currency): int
    {
        return $this->aggregate->subunitFor($currency);
    }

    public function getIterator(): Traversable
    {
        return $this->aggregate->getIterator();
    }

    /**
     * 检查是否为 ISO 货币
     */
    public function isISO(Currency $currency): bool
    {
        $isoCurrencies = new ISOCurrencies();
        return $isoCurrencies->contains($currency);
    }

    /**
     * 检查是否为比特币货币
     */
    public function isBitcoin(Currency $currency): bool
    {
        $bitcoinCurrencies = new BitcoinCurrencies();
        return $bitcoinCurrencies->contains($currency);
    }

    /**
     * 检查是否为自定义货币
     */
    public function isCustom(Currency $currency): bool
    {
        return !$this->isISO($currency) && !$this->isBitcoin($currency);
    }

    /**
     * 获取货币符号
     *
     * @param Currency $currency 货币对象
     * @param string|null $locale 区域设置，如果为 null 则使用应用默认区域设置
     * @return string|null 货币符号，如果未找到则返回货币代码
     */
    public function getSymbol(Currency $currency, ?string $locale = null): ?string
    {
        $currencyCode = $currency->getCode();
        if ($locale === null) {
            $locale = config('app.locale', 'en_US');
        }
        // 尝试使用 Symfony Intl Currencies 获取符号
        if($this->isISO($currency)){
            try {
                return IntlCurrencies::getSymbol($currencyCode, $locale);
            }catch (\Throwable $throwable){
                return  $currencyCode;
            }

        }

        try {
            // 如果 IntlCurrencies 无法获取，尝试使用 NumberFormatter
            $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            $formatter->setTextAttribute(\NumberFormatter::CURRENCY_CODE, $currencyCode);
            $symbol = $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
            if ($symbol && $symbol !== $currencyCode && $symbol !== '') {
                return $symbol;
            }
        } catch (\Throwable $e) {
            // 如果获取失败，返回货币代码
        }

        // 如果都失败，返回货币代码
        return $currencyCode;
    }

    /**
     * 获取货币名称
     *
     * @param Currency $currency 货币对象
     * @param string|null $locale 区域设置，如果为 null 则使用应用默认区域设置
     * @return string|null 货币名称，如果未找到则返回货币代码
     */
    public function getName(Currency $currency, ?string $locale = null): ?string
    {
        $currencyCode = $currency->getCode();

        if ($locale === null) {
            $locale = config('app.locale', 'en_US');
        }

        // 尝试使用 Symfony Intl Currencies 获取名称
        if ($this->isISO($currency)) {
            try {
                // 先检查货币是否存在，避免抛出异常
                if (IntlCurrencies::exists($currencyCode)) {
                    return IntlCurrencies::getName($currencyCode, $locale);
                }else{
                    return $currencyCode;
                }
            } catch (\Throwable $e) {
                // 如果获取失败，继续尝试其他方式
            }
        }

        if ($this->isCustom($currency)) {
            // 通过配置获取货币名称
            $customCurrencies = config('money.currencies.custom', []);
            if (isset($customCurrencies[$currencyCode])) {
                return $customCurrencies[$currencyCode]['name'] ?? $currency->getCode();
            }
        }

        // 如果都失败，返回货币代码
        return $currencyCode;
    }
}

