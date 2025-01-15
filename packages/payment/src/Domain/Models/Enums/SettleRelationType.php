<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 结算关系类型
 */
enum SettleRelationType: string
{
    use EnumsHelper;

    case   SERVICE_PROVIDER = 'SERVICE_PROVIDER';
    case   STORE = 'STORE';
    case   STORE_OWNER = 'STORE_OWNER';
    case   STAFF = 'STAFF';
    case   HEADQUARTER = 'HEADQUARTER';
    case   BRAND = 'BRAND';
    case   DISTRIBUTOR = 'DISTRIBUTOR';
    case   USER = 'USER';
    case   SUPPLIER = 'SUPPLIER';
    case   CUSTOM = 'CUSTOM';

    public static function labels() : array
    {
        return [
            self::SERVICE_PROVIDER->value => '服务商',
            self::STORE->value            => '门店',
            self::STORE_OWNER->value      => '店主',
            self::STAFF->value            => '员工',
            self::HEADQUARTER->value      => '总部',
            self::BRAND->value            => '品牌',
            self::DISTRIBUTOR->value      => '分销商',
            self::USER->value             => '用户',
            self::SUPPLIER->value         => '供应商',
            self::CUSTOM->value           => '自定义',
        ];
    }
}
