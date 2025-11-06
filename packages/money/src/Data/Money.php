<?php

namespace RedJasmine\Money\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Money\Money as BaseMoney;

/**
 * Money 值对象
 * 继承 moneyphp/money 并实现 Laravel 接口
 */
class Money extends BaseMoney implements Arrayable, Jsonable, JsonSerializable
{
    /**
     * 缓存的格式化器
     */
    private static ?\Money\Formatter\DecimalMoneyFormatter $decimalFormatter = null;

    /**
     * 转换为数组
     *
     * @return array
     */
    public function toArray(): array
    {
        $currencyCode = $this->getCurrency()->getCode();

        // 使用官方 DecimalMoneyFormatter 获取小数格式（缓存实例）
        if (self::$decimalFormatter === null) {
            $currencies = app(\RedJasmine\Money\Currencies\AggregateCurrencies::class);
            self::$decimalFormatter = new \Money\Formatter\DecimalMoneyFormatter($currencies);
        }
        $formatted = self::$decimalFormatter->format($this);

        // 使用 NumberFormatter 直接获取货币符号
        $locale = config('app.locale', 'zh_CN');
        $numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $pattern = $numberFormatter->formatCurrency(0, $currencyCode);
        $symbol = trim(preg_replace('/[\d\s.,]+/', '', $pattern));

        return [
            'amount' => $this->getAmount(),
            'currency' => $currencyCode,
            'formatted' => $formatted,
            'symbol' => $symbol ?: $currencyCode,
        ];
    }

    /**
     * 转换为 JSON
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * JSON 序列化
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

