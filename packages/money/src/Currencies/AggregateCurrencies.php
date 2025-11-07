<?php

namespace RedJasmine\Money\Currencies;

use Money\Currencies;
use Money\Currencies\AggregateCurrencies as BaseAggregateCurrencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
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
}

