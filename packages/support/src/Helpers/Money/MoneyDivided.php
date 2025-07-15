<?php

namespace RedJasmine\Support\Helpers\Money;

use Cknow\Money\Money;

/**
 * 分摊
 */
class MoneyDivided
{
    public static function divided(Money $amount, array $proportions = []) : array
    {
        if (empty($proportions)) {
            return [];  // 如果没有比例数组，直接返回全部金额
        }

        arsort($proportions);

        // 计算比例的总和
        $totalProportion = array_sum($proportions);

        $result = [];
        if ($totalProportion <= 0) {
            $zero = Money::parse(0, $amount->getCurrency()->getCode());
            foreach ($proportions as $index => $proportion) {
                $result[$index] = $zero;
            }
            return $result;
        }

        $indexCount       = 0;
        $proportionsCount = count($proportions);
        foreach ($proportions as $index => $proportion) {
            $indexCount++;
            if ($indexCount === $proportionsCount) {
                // 最后一个
                if (empty($result)) {
                    $result[$index] = $amount;
                } else {
                    $result[$index] = $amount->subtract(...$result);
                }
            } else {

                $result[$index] = $amount->multiply(bcdiv($proportion, $totalProportion, 6));

            }
        }
        return $result;

    }
}