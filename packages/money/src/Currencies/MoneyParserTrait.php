<?php

declare(strict_types=1);

namespace RedJasmine\Money\Currencies;

use InvalidArgumentException;
use Money\Currencies;
use Money\Exception\ParserException;
use Money\MoneyParser;
use Money\Parser\AggregateMoneyParser;
use Money\Parser\BitcoinMoneyParser;
use Money\Parser\DecimalMoneyParser;
use Money\Parser\IntlLocalizedDecimalParser;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use RedJasmine\Money\Data\Money;

/**
 * Money 解析器特性
 * 提供多种格式的金额解析功能
 * 统一使用 AggregateCurrencies 管理货币
 */
trait MoneyParserTrait
{
    use LocaleTrait;

    /**
     * 解析金额值
     *
     * @param mixed $value 要解析的值
     * @param \Money\Currency|string|null $currency 货币
     * @param bool $forceDecimals 强制小数格式
     * @param string|null $locale 区域设置
     * @param \Money\Currencies|null $currencies 货币集合
     * @param int|null $bitcoinDigits 比特币小数位数
     * @return Money
     * @throws InvalidArgumentException
     */
    public static function parse(
        mixed $value,
        \Money\Currency|string|null $currency = null,
        bool $forceDecimals = false,
        ?string $locale = null,
        ?Currencies $currencies = null,
        ?int $bitcoinDigits = null,
    ): Money {
        $value = $value ?? 0;

        if ($value instanceof Money) {
            return $value;
        }

        if ($value instanceof \Money\Money) {
            return new Money($value->getAmount(), $value->getCurrency());
        }

        if (!is_scalar($value)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s', json_encode($value)));
        }

        if (
            (is_int($value) || (filter_var($value, FILTER_VALIDATE_INT) !== false && !is_float($value)))
            && $forceDecimals
        ) {
            $value = sprintf('%.14F', $value);
        }

        $currency = static::parseCurrency($currency ?: static::getDefaultCurrency());

        if (is_int($value) || (filter_var($value, FILTER_VALIDATE_INT) !== false && !is_float($value))) {
            return new Money((int)$value, $currency);
        }

        $currencies = $currencies ?: static::getCurrencies();

        if (is_float($value) || filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return static::parseByDecimal($value, $currency, $currencies);
        }

        $locale = $locale ?: static::getLocale();
        $bitcoinDigits = $bitcoinDigits ?? 2;

        try {
            $parsers = [
                new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::CURRENCY), $currencies),
                new DecimalMoneyParser($currencies),
                new BitcoinMoneyParser($bitcoinDigits),
            ];

            return static::parseByAggregate($value, null, $parsers);
        } catch (ParserException $e) {
            try {
                return static::parseByAggregate($value, $currency, $parsers);
            } catch (ParserException $e) {
                $parsers = [
                    new IntlMoneyParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new IntlLocalizedDecimalParser(new NumberFormatter($locale, NumberFormatter::DECIMAL), $currencies),
                    new DecimalMoneyParser($currencies),
                    new BitcoinMoneyParser($bitcoinDigits),
                ];

                try {
                    return static::parseByAggregate($value, null, $parsers);
                } catch (ParserException $e) {
                    return static::parseByAggregate($value, $currency, $parsers);
                }
            }
        }
    }

    /**
     * 使用聚合解析器解析
     */
    public static function parseByAggregate(
        string $money,
        \Money\Currency|string|null $fallbackCurrency = null,
        array $parsers = [],
    ): Money {
        $parser = new AggregateMoneyParser($parsers);

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * 使用比特币解析器解析
     */
    public static function parseByBitcoin(
        string $money,
        \Money\Currency|string|null $fallbackCurrency = null,
        ?int $fractionDigits = null,
    ): Money {
        $parser = new BitcoinMoneyParser($fractionDigits ?? 2);

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * 使用小数解析器解析
     */
    public static function parseByDecimal(
        string|float $money,
        \Money\Currency|string|null $fallbackCurrency = null,
        ?Currencies $currencies = null,
    ): Money {
        $parser = new DecimalMoneyParser($currencies ?: static::getCurrencies());

        return static::parseByParser($parser, (string)$money, $fallbackCurrency);
    }

    /**
     * 使用国际化解析器解析
     */
    public static function parseByIntl(
        string $money,
        \Money\Currency|string|null $fallbackCurrency = null,
        ?string $locale = null,
        ?Currencies $currencies = null,
        ?int $style = null,
    ): Money {
        $numberFormatter = new NumberFormatter(
            $locale ?: static::getLocale(),
            $style ?? NumberFormatter::CURRENCY
        );

        $parser = new IntlMoneyParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * 使用国际化本地化小数解析器解析
     */
    public static function parseByIntlLocalizedDecimal(
        string $money,
        \Money\Currency|string|null $fallbackCurrency = null,
        ?string $locale = null,
        ?Currencies $currencies = null,
        ?int $style = null,
    ): Money {
        $numberFormatter = new NumberFormatter(
            $locale ?: static::getLocale(),
            $style ?? NumberFormatter::CURRENCY
        );

        $parser = new IntlLocalizedDecimalParser($numberFormatter, $currencies ?: static::getCurrencies());

        return static::parseByParser($parser, $money, $fallbackCurrency);
    }

    /**
     * 使用指定解析器解析
     */
    public static function parseByParser(
        MoneyParser $parser,
        string $money,
        \Money\Currency|string|null $fallbackCurrency = null,
    ): Money {
        $fallbackCurrency = static::parseCurrency($fallbackCurrency);
        $originalMoney = $parser->parse($money, $fallbackCurrency);

        return new Money($originalMoney->getAmount(), $originalMoney->getCurrency());
    }
}

