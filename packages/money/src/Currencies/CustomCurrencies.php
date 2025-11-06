<?php

namespace RedJasmine\Money\Currencies;

use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;

/**
 * 自定义货币类
 * 支持通过配置定义的虚拟货币和特殊货币
 */
class CustomCurrencies implements Currencies
{
    private array $currencies;

    public function __construct(array $currencies = [])
    {
        $this->currencies = $this->normalizeCurrencies($currencies);
    }

    /**
     * 标准化货币配置
     */
    private function normalizeCurrencies(array $currencies): array
    {
        $normalized = [];

        foreach ($currencies as $code => $config) {
            if (is_array($config)) {
                $normalized[$code] = [
                    'name' => $config['name'] ?? $code,
                    'code' => $config['code'] ?? $code,
                    'subunit' => $config['subunit'] ?? 2,
                    'numeric_code' => $config['numeric_code'] ?? null,
                ];
            }
        }

        return $normalized;
    }

    /**
     * {@inheritdoc}
     */
    public function contains(Currency $currency): bool
    {
        return isset($this->currencies[$currency->getCode()]);
    }

    /**
     * {@inheritdoc}
     */
    public function subunitFor(Currency $currency): int
    {
        if (!$this->contains($currency)) {
            throw new UnknownCurrencyException(
                'Cannot find currency '.$currency->getCode()
            );
        }

        return $this->currencies[$currency->getCode()]['subunit'];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->currencies as $code => $config) {
            yield new Currency($code);
        }
    }

    /**
     * 获取所有自定义货币
     */
    public function all(): array
    {
        return $this->currencies;
    }

    /**
     * 添加货币
     */
    public function addCurrency(string $code, array $config): void
    {
        $this->currencies[$code] = [
            'name' => $config['name'] ?? $code,
            'code' => $code,
            'subunit' => $config['subunit'] ?? 2,
            'numeric_code' => $config['numeric_code'] ?? null,
        ];
    }

    /**
     * 移除货币
     */
    public function removeCurrency(string $code): void
    {
        unset($this->currencies[$code]);
    }
}

