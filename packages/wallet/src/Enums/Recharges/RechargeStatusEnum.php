<?php

namespace RedJasmine\Wallet\Enums\Recharges;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RechargeStatusEnum: string
{
    use EnumsHelper;

    case CREATED = 'created';
    case PAYING = 'paying';
    case PAID = 'paid';
    case SUCCESS = 'success';
    case FAIL = 'fail';


    public static function labels() : array
    {

        return [
            self::CREATED->value => '待支付',
            self::PAYING->value  => '支付中',
            self::PAID->value    => '支付成功',
            self::SUCCESS->value => '充值成功',
            self::FAIL->value    => '失败',
        ];
    }


}
