<?php

declare(strict_types=1);

namespace RedJasmine\Money\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Money\Calculator\BcMathCalculator;
use Money\Calculator\Calculator;
use Money\Currency;
use Money\Exception\InvalidArgumentException;
use Money\Number;
use RedJasmine\Money\Currencies\CurrenciesTrait;
use RedJasmine\Money\Currencies\MoneyParserTrait;
use RedJasmine\Money\Currencies\MoneyFormatterTrait;

use function array_fill;
use function array_keys;
use function array_map;
use function array_sum;
use function count;
use function filter_var;
use function floor;
use function is_int;
use function max;
use function str_pad;
use function strlen;
use function substr;
use function str_starts_with;
use function ltrim;

use const FILTER_VALIDATE_INT;
use const PHP_ROUND_HALF_DOWN;
use const PHP_ROUND_HALF_EVEN;
use const PHP_ROUND_HALF_ODD;
use const PHP_ROUND_HALF_UP;

/**
 * 完整实现 Money 值对象，复制 moneyphp/money 的核心能力并扩展 Laravel 接口
 */
class Money implements Arrayable, Jsonable, JsonSerializable
{
    use CurrenciesTrait;
    use MoneyParserTrait;
    use MoneyFormatterTrait {
        MoneyFormatterTrait::format as formatWithOptions;
    }
    public const ROUND_HALF_UP = PHP_ROUND_HALF_UP;

    public const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

    public const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;

    public const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    public const ROUND_UP = 5;

    public const ROUND_DOWN = 6;

    public const ROUND_HALF_POSITIVE_INFINITY = 7;

    public const ROUND_HALF_NEGATIVE_INFINITY = 8;

    /**
     * 内部金额，最小单位表示
     *
     * @phpstan-var numeric-string
     */
    private string $amount;

    /**
     * 金额计算器
     *
     * @var class-string<Calculator>
     */
    private static string $calculator = BcMathCalculator::class;

    /**
     * 货币对象
     */
    private readonly Currency $currency;

    /**
     * @param int|string $amount 以最小货币单位表示的金额
     * @throws InvalidArgumentException
     */
    public function __construct(int|string $amount, Currency $currency)
    {
        $this->currency = $currency;

        if (filter_var($amount, FILTER_VALIDATE_INT) === false) {
            $numberFromString = Number::fromString((string) $amount);
            if (! $numberFromString->isInteger()) {
                throw new InvalidArgumentException('Amount must be an integer(ish) value');
            }

            $this->amount = $numberFromString->getIntegerPart();

            return;
        }

        $this->amount = (string) $amount;
    }

    /**
     * 判断货币是否一致
     */
    public function isSameCurrency(self ...$others): bool
    {
        foreach ($others as $other) {
            if ($this->currency != $other->currency) {
                return false;
            }
        }

        return true;
    }

    /**
     * 判断金额是否相等
     */
    public function equals(self $other): bool
    {
        if ($this->currency != $other->currency) {
            return false;
        }

        if ($this->amount === $other->amount) {
            return true;
        }

        return $this->compare($other) === 0;
    }

    /**
     * 金额比较
     *
     * @throws InvalidArgumentException
     */
    public function compare(self $other): int
    {
        if ($this->currency != $other->currency) {
            throw InvalidArgumentException::currencyMismatch();
        }

        return self::$calculator::compare($this->amount, $other->amount);
    }

    public function greaterThan(self $other): bool
    {
        return $this->compare($other) > 0;
    }

    public function greaterThanOrEqual(self $other): bool
    {
        return $this->compare($other) >= 0;
    }

    public function lessThan(self $other): bool
    {
        return $this->compare($other) < 0;
    }

    public function lessThanOrEqual(self $other): bool
    {
        return $this->compare($other) <= 0;
    }

    /**
     * 获取金额（最小单位）
     *
     * @phpstan-return numeric-string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 获取货币
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * 金额累加
     */
    public function add(self ...$addends): self
    {
        $amount = $this->amount;

        foreach ($addends as $addend) {
            if ($this->currency != $addend->currency) {
                throw InvalidArgumentException::currencyMismatch();
            }

            $amount = self::$calculator::add($amount, $addend->amount);
        }

        return new self($amount, $this->currency);
    }

    /**
     * 金额相减
     */
    public function subtract(self ...$subtrahends): self
    {
        $amount = $this->amount;

        foreach ($subtrahends as $subtrahend) {
            if ($this->currency != $subtrahend->currency) {
                throw InvalidArgumentException::currencyMismatch();
            }

            $amount = self::$calculator::subtract($amount, $subtrahend->amount);
        }

        return new self($amount, $this->currency);
    }

    /**
     * 金额相乘
     */
    public function multiply(int|string $multiplier, int $roundingMode = self::ROUND_HALF_UP): self
    {
        if (is_int($multiplier)) {
            $multiplier = (string) $multiplier;
        }

        $product = $this->round(self::$calculator::multiply($this->amount, $multiplier), $roundingMode);

        return new self($product, $this->currency);
    }

    /**
     * 金额相除
     */
    public function divide(int|string $divisor, int $roundingMode = self::ROUND_HALF_UP): self
    {
        if (is_int($divisor)) {
            $divisor = (string) $divisor;
        }

        $quotient = $this->round(self::$calculator::divide($this->amount, $divisor), $roundingMode);

        return new self($quotient, $this->currency);
    }

    /**
     * 取模
     */
    public function mod(self|int|string $divisor): self
    {
        if ($divisor instanceof self) {
            if ($this->currency != $divisor->currency) {
                throw InvalidArgumentException::currencyMismatch();
            }

            $divisor = $divisor->amount;
        } else {
            $divisor = (string) Number::fromNumber($divisor);
        }

        return new self(self::$calculator::mod($this->amount, $divisor), $this->currency);
    }

    /**
     * 按比例分配
     *
     * @return self[]
     */
    public function allocate(array $ratios): array
    {
        $remainder = $this->amount;
        $results   = [];
        $total     = array_sum($ratios);

        if ($total <= 0) {
            throw new InvalidArgumentException('Cannot allocate to none, sum of ratios must be greater than zero');
        }

        foreach ($ratios as $key => $ratio) {
            if ($ratio < 0) {
                throw new InvalidArgumentException('Cannot allocate to none, ratio must be zero or positive');
            }

            $share         = self::$calculator::share($this->amount, (string) $ratio, (string) $total);
            $results[$key] = new self($share, $this->currency);
            $remainder     = self::$calculator::subtract($remainder, $share);
        }

        if (self::$calculator::compare($remainder, '0') === 0) {
            return $results;
        }

        $amount    = $this->amount;
        $fractions = array_map(static function (float|int $ratio) use ($total, $amount) {
            $share = (float) $ratio / $total * (float) $amount;

            return $share - floor($share);
        }, $ratios);

        while (self::$calculator::compare($remainder, '0') > 0) {
            $index           = $fractions !== [] ? array_keys($fractions, max($fractions))[0] : 0;
            $results[$index] = new self(self::$calculator::add($results[$index]->amount, '1'), $results[$index]->currency);
            $remainder       = self::$calculator::subtract($remainder, '1');
            unset($fractions[$index]);
        }

        return $results;
    }

    /**
     * 平均分配
     */
    public function allocateTo(int $n): array
    {
        return $this->allocate(array_fill(0, $n, 1));
    }

    /**
     * 计算比率
     */
    public function ratioOf(self $money): string
    {
        if ($money->isZero()) {
            throw new InvalidArgumentException('Cannot calculate a ratio of zero');
        }

        if ($this->currency != $money->currency) {
            throw InvalidArgumentException::currencyMismatch();
        }

        return self::$calculator::divide($this->amount, $money->amount);
    }

    /**
     * 四舍五入到指定单位
     */
    public function roundToUnit(int $unit, int $roundingMode = self::ROUND_HALF_UP): self
    {
        if ($unit === 0) {
            return $this;
        }

        $abs = self::$calculator::absolute($this->amount);
        if (strlen($abs) < $unit) {
            return new self('0', $this->currency);
        }

        $toBeRounded = substr($this->amount, 0, strlen($this->amount) - $unit) . '.' . substr($this->amount, $unit * -1);

        $result = $this->round($toBeRounded, $roundingMode);
        if ($result !== '0') {
            $result .= str_pad('', $unit, '0');
        }

        return new self($result, $this->currency);
    }

    public function absolute(): self
    {
        return new self(
            self::$calculator::absolute($this->amount),
            $this->currency
        );
    }

    public function negative(): self
    {
        return (new self(0, $this->currency))->subtract($this);
    }

    public function isZero(): bool
    {
        return self::$calculator::compare($this->amount, '0') === 0;
    }

    public function isPositive(): bool
    {
        return self::$calculator::compare($this->amount, '0') > 0;
    }

    public function isNegative(): bool
    {
        return self::$calculator::compare($this->amount, '0') < 0;
    }

    /**
     * 序列化数据
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $currencyCode = $this->currency->getCode();

        // 使用 formatByDecimal 方法格式化，确保输出小数形式的元单位
        $formatted = $this->formatByDecimal();

        $symbol = static::getCurrencySymbol($this->currency);

        return [
            'amount' => $this->amount,
            'currency' => $currencyCode,
            'formatted' => $formatted,
            'symbol' => $symbol ?: $currencyCode,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * 静态快捷构造，如 Money::CNY(100)
     */
    public static function __callStatic(string $method, array $arguments): self
    {
        return new self($arguments[0], new Currency($method));
    }

    public static function min(self $first, self ...$collection): self
    {
        $min = $first;

        foreach ($collection as $money) {
            if (! $money->lessThan($min)) {
                continue;
            }

            $min = $money;
        }

        return $min;
    }

    public static function max(self $first, self ...$collection): self
    {
        $max = $first;

        foreach ($collection as $money) {
            if (! $money->greaterThan($max)) {
                continue;
            }

            $max = $money;
        }

        return $max;
    }

    public static function sum(self $first, self ...$collection): self
    {
        return $first->add(...$collection);
    }

    public static function avg(self $first, self ...$collection): self
    {
        return $first->add(...$collection)->divide((string) (count($collection) + 1));
    }

    /**
     * 注册自定义计算器
     *
     * @phpstan-param class-string<Calculator> $calculator
     */
    public static function registerCalculator(string $calculator): void
    {
        self::$calculator = $calculator;
    }

    /**
     * 获取当前计算器
     *
     * @phpstan-return class-string<Calculator>
     */
    public static function getCalculator(): string
    {
        return self::$calculator;
    }

    private function round(string $amount, int $roundingMode): string
    {
        if ($roundingMode === self::ROUND_UP) {
            return self::$calculator::ceil($amount);
        }

        if ($roundingMode === self::ROUND_DOWN) {
            return self::$calculator::floor($amount);
        }

        return self::$calculator::round($amount, $roundingMode);
    }

    private static function currencies(): \RedJasmine\Money\Currencies\AggregateCurrencies
    {
        return static::getCurrencies();
    }

    /**
     * 格式化金额（默认使用小数格式）
     * 重写 trait 方法以提供无参数的默认格式化
     */
    public function format(?string $locale = null, ?\Money\Currencies $currencies = null, int $style = \NumberFormatter::CURRENCY): string
    {
        // 如果没有参数，使用简单的小数格式
        if ($locale === null && $currencies === null && $style === \NumberFormatter::CURRENCY) {
            return $this->formatByDecimal();
        }

        // 否则使用 trait 的完整格式化方法
        return $this->formatWithOptions($locale, $currencies, $style);
    }

    private function formatDecimalAmount(): string
    {
        try {
            $subunit = self::currencies()->subunitFor($this->currency);
        } catch (\Throwable $throwable) {
            return $this->amount;
        }

        if ($subunit <= 0) {
            return $this->amount;
        }

        $amount = $this->amount;
        $negative = str_starts_with($amount, '-');

        if ($negative) {
            $amount = substr($amount, 1);
        }

        $length = strlen($amount);

        if ($length <= $subunit) {
            $integer = '0';
            $decimal = str_pad($amount, $subunit, '0', STR_PAD_LEFT);
        } else {
            $integer = substr($amount, 0, $length - $subunit);
            $decimal = substr($amount, -$subunit);
        }

        $integer = ltrim($integer, '0');
        if ($integer === '') {
            $integer = '0';
        }

        $decimal = str_pad($decimal, $subunit, '0', STR_PAD_LEFT);
        $result = $integer . '.' . $decimal;

        return $negative ? '-' . $result : $result;
    }
}

