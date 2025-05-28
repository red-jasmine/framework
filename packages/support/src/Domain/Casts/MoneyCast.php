<?php

namespace RedJasmine\Support\Domain\Casts;

use Cknow\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Money\Currency;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyCast implements CastsAttributes, Cast, Transformer
{

    protected ?string $valueKey    = null;
    protected ?string $currencyKey = null;


    public const string AMOUNT_TYPE_DECIMAL = 'decimal';
    public const string AMOUNT_TYPE_BIGINT  = 'bigint';

    protected string $valueType            = self::AMOUNT_TYPE_DECIMAL;
    protected string $valueSuffix          = 'amount';
    protected string $currencySuffix       = 'currency';
    protected bool   $isShareCurrencyField = false;


    protected function getValueKey(string $key)
    {
        return $this->valueKey ?? $key.'_'.$this->valueSuffix;
    }

    protected function getCurrencyKey(string $key)
    {
        return $this->currencyKey ?? $key.'_'.$this->currencySuffix;
    }

    public function __construct(
        $currencyKey = null,
        $valueKey = null,
        string $isShareCurrencyField = null,
        string $valueType = self::AMOUNT_TYPE_DECIMAL
    ) {

        $this->valueKey             = $valueKey ?? null;
        $this->currencyKey          = $currencyKey ?? null;
        $this->valueType            = $valueType ?? 'decimal';
        $this->isShareCurrencyField = filter_var($isShareCurrencyField, FILTER_VALIDATE_BOOLEAN);
    }

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?Money
    {
        $key = Str::snake($key);

        $moneyValue = $attributes[$this->getValueKey($key)] ?? null;
        $currency   = $attributes[$this->getCurrencyKey($key)] ?? null;

        if (blank($currency) && blank($moneyValue)) {
            return null;
        }
        if ($this->isShareCurrencyField && blank($moneyValue)) {
            return null;
        }

        return $this->valueType === static::AMOUNT_TYPE_DECIMAL ?
            Money::parseByDecimal($moneyValue, $currency) : Money::parseByIntl($moneyValue, $currency);

    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : ?array
    {
        $money = $value;
        $key   = Str::snake($key);
        if (blank($money)) {
            if ($this->isShareCurrencyField) {
                return [
                    $this->getValueKey($key) => null,
                ];
            }
            return [
                $this->getValueKey($key)    => null,
                $this->getCurrencyKey($key) => null,
            ];
        }

        if (is_string($value) || is_numeric($value)) {
            $money = new Money($value, new Currency('CNY'));
        }
        if (is_array($value)) {
            $money = new Money($value[$this->valueSuffix], new Currency($value[$this->currencySuffix]));
        }


        return [
            $this->getValueKey($key)    => $this->valueType === static::AMOUNT_TYPE_DECIMAL ?
                $money->formatByDecimal() :
                $money->getAmount(),
            $this->getCurrencyKey($key) => $money->getCurrency()->getCode(),
        ];
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : ?Money
    {
        if (blank($value)) {
            return null;
        }
        $data = [];

        if (is_array($value)) {
            $data = $value;
        } elseif (is_string($value) || is_numeric($value)) {
            $data[$this->valueSuffix] = $value;
        }

        if (blank($data[$this->valueSuffix] ?? null) && blank($data[$this->currencySuffix] ?? null)) {
            return null;
        }
        if (blank($data[$this->valueSuffix] ?? null)) {
            return null;
        }

        if ($value instanceof Money) {
            return $value;
        }

        return new Money($data[$this->valueSuffix], new Currency($data[$this->currencySuffix] ?? 'CNY'));
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : ?array
    {
        if (blank($value)) {
            return null;
        }
        return [
            $this->currencySuffix => $value->getCurrency()->getCode(),
            $this->valueSuffix    => $value->amount,
        ];
    }


}
