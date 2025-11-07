<?php

namespace RedJasmine\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Money\Currency;
use RedJasmine\Money\Data\Money;
use Money\Parser\DecimalMoneyParser;
use RedJasmine\Money\Currencies\AggregateCurrencies;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

/**
 * Money 转换器
 * 支持 Eloquent Model 和 Spatie Laravel Data 的金额转换
 */
class MoneyCast implements CastsAttributes, Cast, Transformer
{
    public const AMOUNT_TYPE_DECIMAL = 'decimal';
    public const AMOUNT_TYPE_BIGINT = 'bigint';

    protected ?string $valueKey = null;
    protected ?string $currencyKey = null;
    protected string $valueType = self::AMOUNT_TYPE_DECIMAL;
    protected string $valueSuffix = 'amount';
    protected string $currencySuffix = 'currency';
    protected bool $isShareCurrencyField = false;

    private ?AggregateCurrencies $currencies = null;
    private ?DecimalMoneyParser $parser = null;

    public function __construct(
        $currencyKey = null,
        $valueKey = null,
        ?string $isShareCurrencyField = null,
        string $valueType = self::AMOUNT_TYPE_DECIMAL
    ) {
        $this->valueKey = $valueKey ?? null;
        $this->currencyKey = $currencyKey ?? null;
        $this->valueType = $valueType ?? 'decimal';
        $this->isShareCurrencyField = filter_var($isShareCurrencyField, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * 获取货币定义实例（单例模式）
     */
    private function getCurrencies(): AggregateCurrencies
    {
        if ($this->currencies === null) {
            $config = config('money.currencies', []);
            $this->currencies = AggregateCurrencies::make($config);
        }
        return $this->currencies;
    }

    /**
     * 获取小数金额解析器（单例模式）
     */
    private function getParser(): DecimalMoneyParser
    {
        return $this->parser ??= new DecimalMoneyParser($this->getCurrencies());
    }

    /**
     * 安全创建货币对象
     */
    private function createCurrency(?string $currencyCode): Currency
    {
        try {
            $code = $currencyCode ?? config('money.default_currency', 'CNY');
            return new Currency($code);
        } catch (\Throwable $e) {
            // 如果货币代码无效，回退到默认货币
            return new Currency(config('money.default_currency', 'CNY'));
        }
    }

    /**
     * Eloquent Model: 从数据库读取
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        $key = Str::snake($key);

        $moneyValue = $attributes[$this->getValueKey($key)] ?? null;
        $currencyCode = $attributes[$this->getCurrencyKey($key)] ?? null;

        // 如果金额为空，返回 null
        if (blank($moneyValue)) {
            return null;
        }

        // 如果不是共享货币字段，但货币代码为空，返回 null
        if (!$this->isShareCurrencyField && blank($currencyCode)) {
            return null;
        }

        $currency = $this->createCurrency($currencyCode);


        try {

            if ($this->valueType === static::AMOUNT_TYPE_DECIMAL) {
                // 使用 DecimalMoneyParser 解析小数金额

                $base = $this->getParser()->parse((string)$moneyValue, $currency);
                return new Money($base->getAmount(), $base->getCurrency());
            } else {
                // bigint 类型，直接创建 Money 对象（金额已经是最小单位）
                return new Money((int)$moneyValue, $currency);
            }
        } catch (\Throwable $e) {
            // 解析失败，返回 null
            return null;
        }
    }

    /**
     * Eloquent Model: 保存到数据库
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        $key = Str::snake($key);

        // 处理空值
        if (blank($value)) {
            if ($this->isShareCurrencyField) {
                return [
                    $this->getValueKey($key) => null,
                ];
            }
            return [
                $this->getValueKey($key) => null,
                $this->getCurrencyKey($key) => null,
            ];
        }

        try {
            $money = $this->parseMoney($value);

            if ($money === null) {
                return $this->isShareCurrencyField
                    ? [$this->getValueKey($key) => null]
                    : [$this->getValueKey($key) => null, $this->getCurrencyKey($key) => null];
            }

            // 格式化金额值
            $amountValue = $money->getAmount();
            if ($this->valueType === static::AMOUNT_TYPE_DECIMAL) {
                // 转换为小数格式
                $subunit = $this->getCurrencies()->subunitFor($money->getCurrency());
                $divisor = bcpow('10', (string)$subunit, 0);
                if ($divisor !== '0') {
                    $amountValue = bcdiv($amountValue, $divisor, $subunit);
                }
            }

            return [
                $this->getValueKey($key) => $amountValue,
                $this->getCurrencyKey($key) => $money->getCurrency()->getCode(),
            ];
        } catch (\Throwable $e) {
            // 解析失败，返回 null
            return $this->isShareCurrencyField
                ? [$this->getValueKey($key) => null]
                : [$this->getValueKey($key) => null, $this->getCurrencyKey($key) => null];
        }
    }

    /**
     * Spatie Data: Cast 输入数据
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?Money
    {
        // 如果值为空，返回 null
        if (blank($value)) {
            return null;
        }

        // 如果已经是 Money 对象，直接返回
        if ($value instanceof Money) {
            return $value;
        }

        $valueKey = $this->getValueKey($property->name);
        $currencyKey = $this->getCurrencyKey($property->name);
        $data = [];

        // 解析输入值为数组格式
        if (is_array($value)) {
            $data = $value;
        } elseif (is_string($value) || is_numeric($value)) {
            $data[$valueKey] = $value;
        } else {
            return null;
        }

        // 检查金额值是否存在
        if (blank($data[$valueKey] ?? null)) {
            return null;
        }

        // 确定货币代码
        $currencyCode = null;
        if (isset($data[$currencyKey]) && !blank($data[$currencyKey])) {
            $currencyCode = $data[$currencyKey];
        } elseif ($this->isShareCurrencyField) {
            // 共享货币字段，从 properties 中获取
            $currencyCode = $properties[$currencyKey] ?? config('money.default_currency', 'CNY');
        } else {
            // 非共享货币字段，使用默认货币
            $currencyCode = config('money.default_currency', 'CNY');
        }

        $currency = $this->createCurrency($currencyCode);
        $moneyValue = $data[$valueKey];

        try {
            if ($this->valueType === static::AMOUNT_TYPE_DECIMAL) {
                // 使用 DecimalMoneyParser 解析小数金额
                $base = $this->getParser()->parse((string)$moneyValue, $currency);
                return new Money($base->getAmount(), $base->getCurrency());
            } else {
                // bigint 类型，直接创建 Money 对象（金额已经是最小单位）
                return new Money((int)$moneyValue, $currency);
            }
        } catch (\Throwable $e) {
            // 解析失败，返回 null
            return null;
        }
    }

    /**
     * Spatie Data: Transform 输出数据
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): ?array
    {
        if (blank($value) || !($value instanceof Money)) {
            return null;
        }

        $valueKey = $this->getValueKey($property->name);
        $currencyKey = $this->getCurrencyKey($property->name);

        try {
            // 格式化金额值
            $amountValue = $value->getAmount();
            if ($this->valueType === static::AMOUNT_TYPE_DECIMAL) {
                // 转换为小数格式
                $subunit = $this->getCurrencies()->subunitFor($value->getCurrency());
                $divisor = bcpow('10', (string)$subunit, 0);
                if ($divisor !== '0') {
                    $amountValue = bcdiv($amountValue, $divisor, $subunit);
                }
            }

            return [
                $currencyKey => $value->getCurrency()->getCode(),
                $valueKey => $amountValue,
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * 获取金额字段键
     */
    protected function getValueKey(string $key): string
    {
        return $this->valueKey ?? $key . '_' . $this->valueSuffix;
    }

    /**
     * 获取货币字段键
     */
    protected function getCurrencyKey(string $key): string
    {
        return $this->currencyKey ?? $key . '_' . $this->currencySuffix;
    }

    /**
     * 解析各种类型的输入为 Money 对象
     */
    private function parseMoney(mixed $value): ?Money
    {
        // 如果已经是 Money 对象，直接返回
        if ($value instanceof Money) {
            return $value;
        }

        // 处理字符串或数字
        if (is_string($value) || is_numeric($value)) {
            return $this->parseMoneyFromScalar($value);
        }

        // 处理数组
        if (is_array($value)) {
            return $this->parseMoneyFromArray($value);
        }

        return null;
    }

    /**
     * 从标量值解析 Money
     */
    private function parseMoneyFromScalar(string|int|float $value): ?Money
    {
        $currency = $this->createCurrency(null);

        if ($this->valueType === static::AMOUNT_TYPE_DECIMAL) {
            // decimal 类型：值视为元（如 100.50）
            $base = $this->getParser()->parse((string)$value, $currency);
            return new Money($base->getAmount(), $base->getCurrency());
        } else {
            // bigint 类型：值视为最小单位分（如 10050）
            return new Money((int)$value, $currency);
        }
    }

    /**
     * 从数组解析 Money
     */
    private function parseMoneyFromArray(array $value): ?Money
    {
        // 检查必需的键是否存在
        if (!isset($value[$this->valueSuffix])) {
            return null;
        }

        $currencyCode = $value[$this->currencySuffix] ?? null;
        $currency = $this->createCurrency($currencyCode);
        $amount = $value[$this->valueSuffix];

        if ($this->valueType === static::AMOUNT_TYPE_DECIMAL) {
            // decimal 类型：值视为元
            $base = $this->getParser()->parse((string)$amount, $currency);
            return new Money($base->getAmount(), $base->getCurrency());
        } else {
            // bigint 类型：值视为最小单位分
            return new Money((int)$amount, $currency);
        }
    }
}

