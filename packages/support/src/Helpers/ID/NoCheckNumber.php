<?php

namespace RedJasmine\Support\Helpers\ID;

/**
 * 序号校验码
 */
class NoCheckNumber
{

    public static function generator(string $no) : string
    {
        return $no.static::calculate($no);
    }

    public static function calculate(string $no) : int
    {

        // 使用 Luhn 算法计算校验码
        $sum    = 0;
        $length = strlen($no);

        // 从右往左处理每一位数字
        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = (int) $no[$i];

            // 如果是偶数位（从右往左数的第 2、4、6... 位）
            if (($length - $i) % 2 === 0) {
                $digit *= 2;

                // 如果乘以 2 后结果大于 9，则将各位数字相加（例如 10 变成 1 + 0 = 1）
                if ($digit > 9) {
                    $digit = ($digit % 10) + 1;
                }
            }

            $sum += $digit;
        }

        // 计算校验码，使总和能被 10 整除
        return (10 - ($sum % 10)) % 10;


    }


    public static function chack(string $no) : bool
    {
        $originalNumber   = substr($no, 0, -1);
        $providedChecksum = substr($no, -1);
        return ((string) static::calculate($originalNumber) === $providedChecksum);
    }
}