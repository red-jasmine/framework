<?php

declare(strict_types=1);

namespace RedJasmine\Money\Currencies;

use InvalidArgumentException;
use Money\Currencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Formatter\AggregateMoneyFormatter;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlLocalizedDecimalFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFormatter;
use NumberFormatter;

use function app;

/**
 * Money 格式化器特性
 * 提供多种格式的金额格式化功能
 * 统一使用 AggregateCurrencies 管理货币
 */
trait MoneyFormatterTrait
{
    use LocaleTrait;

    /**
     * 格式化金额
     *
     * @param string|null $locale 区域设置
     * @param \Money\Currencies|null $currencies 货币集合
     * @param int $style 格式化样式
     * @return string
     * @throws InvalidArgumentException
     */
    public function format(?string $locale = null, ?Currencies $currencies = null, int $style = NumberFormatter::CURRENCY): string
    {
        $defaultFormatter = config('money.default_formatter');

        if ($defaultFormatter === null) {
            return $this->formatByIntl($locale, $currencies, $style);
        }

        $formatter = null;

        if (is_string($defaultFormatter)) {
            $formatter = app($defaultFormatter);
        }

        if (is_array($defaultFormatter) && count($defaultFormatter) === 2) {
            $formatter = app($defaultFormatter[0], $defaultFormatter[1]);
        }

        if ($formatter instanceof MoneyFormatter) {
            return $this->formatByFormatter($formatter);
        }

        throw new InvalidArgumentException(sprintf('Invalid default formatter %s', json_encode($defaultFormatter)));
    }

    /**
     * 使用聚合格式化器格式化
     */
    public function formatByAggregate(array $formatters): string
    {
        $formatter = new AggregateMoneyFormatter($formatters);

        return $this->formatByFormatter($formatter);
    }

    /**
     * 使用比特币格式化器格式化
     */
    public function formatByBitcoin(int $fractionDigits = 2, ?Currencies $currencies = null): string
    {
        $formatter = new BitcoinMoneyFormatter($fractionDigits, $currencies ?: new BitcoinCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * 使用小数格式化器格式化
     */
    public function formatByDecimal(?Currencies $currencies = null): string
    {
        $formatter = new DecimalMoneyFormatter($currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * 使用国际化格式化器格式化
     */
    public function formatByIntl(?string $locale = null, ?Currencies $currencies = null, int $style = NumberFormatter::CURRENCY): string
    {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlMoneyFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * 使用国际化本地化小数格式化器格式化
     */
    public function formatByIntlLocalizedDecimal(
        ?string $locale = null,
        ?Currencies $currencies = null,
        int $style = NumberFormatter::CURRENCY
    ): string {
        $numberFormatter = new NumberFormatter($locale ?: static::getLocale(), $style);
        $formatter = new IntlLocalizedDecimalFormatter($numberFormatter, $currencies ?: static::getCurrencies());

        return $this->formatByFormatter($formatter);
    }

    /**
     * 使用指定格式化器格式化
     */
    public function formatByFormatter(MoneyFormatter $formatter): string
    {
        // 需要将当前 Money 对象转换为底层 Money\Money 对象
        $baseMoney = new \Money\Money($this->getAmount(), $this->getCurrency());

        return $formatter->format($baseMoney);
    }
}

