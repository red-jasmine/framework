<?php

namespace RedJasmine\PointsMall\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PointsExchangeOrderStatusEnum: string
{
    use EnumsHelper;

    // 待付款状态（适用于混合支付模式）
    case PAYING = 'paying';

    // 待接单状态
    case ACCEPTING = 'accepting';

    // 待发货状态 发货中
    case SHIPPING = 'shipping';

    // 待收货状态
    case CONFIRMING = 'confirming';

    // 已完成状态
    case FINISHED = 'finished';

    // 已取消状态（未支付取消）
    case CANCEL = 'cancel';

    // 已关闭状态（已支付后退款关闭）
    case CLOSED = 'closed';

    public static function labels() : array
    {
        return [
            self::PAYING->value     => '待支付',
            self::ACCEPTING->value  => '待接单',
            self::SHIPPING->value   => '待发货',
            self::CONFIRMING->value => '待收货',
            self::FINISHED->value   => '已完成',
            self::CANCEL->value     => '已取消',
            self::CLOSED->value     => '已关闭',
        ];
    }

    public static function colors() : array
    {
        return [
            self::PAYING->value     => 'warning',
            self::ACCEPTING->value  => 'info',
            self::SHIPPING->value   => 'primary',
            self::CONFIRMING->value => 'success',
            self::FINISHED->value   => 'success',
            self::CANCEL->value     => 'danger',
            self::CLOSED->value     => 'secondary',
        ];
    }

    public static function icons() : array
    {
        return [
            self::PAYING->value     => 'heroicon-o-credit-card',
            self::ACCEPTING->value  => 'heroicon-o-bell-alert',
            self::SHIPPING->value   => 'heroicon-o-arrow-up-on-square-stack',
            self::CONFIRMING->value => 'heroicon-o-truck',
            self::FINISHED->value   => 'heroicon-o-check-badge',
            self::CANCEL->value     => 'heroicon-o-x-circle',
            self::CLOSED->value     => 'heroicon-o-archive-box-x-mark',
        ];
    }


}