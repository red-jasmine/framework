<?php

namespace RedJasmine\PointsMall\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PointsExchangeOrderStatusEnum: string
{
    use EnumsHelper;

    case EXCHANGED = 'exchanged';
    case ORDER_CREATED = 'order_created';
    case ORDER_PAID = 'order_paid';
    case ORDER_ACCEPTED = 'order_accepted';
    case ORDER_SHIPPED = 'order_shipped';
    case ORDER_FINISHED = 'order_finished';
    case ORDER_CANCELED = 'order_canceled';

    public static function labels(): array
    {
        return [
            self::EXCHANGED->value => '已兑换',
            self::ORDER_CREATED->value => '订单已创建',
            self::ORDER_PAID->value => '订单已支付',
            self::ORDER_ACCEPTED->value => '订单已接单',
            self::ORDER_SHIPPED->value => '订单已发货',
            self::ORDER_FINISHED->value => '订单已完成',
            self::ORDER_CANCELED->value => '订单已取消',
        ];
    }

    public static function colors(): array
    {
        return [
            self::EXCHANGED->value => 'blue',
            self::ORDER_CREATED->value => 'yellow',
            self::ORDER_PAID->value => 'green',
            self::ORDER_ACCEPTED->value => 'purple',
            self::ORDER_SHIPPED->value => 'indigo',
            self::ORDER_FINISHED->value => 'green',
            self::ORDER_CANCELED->value => 'red',
        ];
    }

    public static function icons(): array
    {
        return [
            self::EXCHANGED->value => 'heroicon-o-star',
            self::ORDER_CREATED->value => 'heroicon-o-document-text',
            self::ORDER_PAID->value => 'heroicon-o-credit-card',
            self::ORDER_ACCEPTED->value => 'heroicon-o-check-circle',
            self::ORDER_SHIPPED->value => 'heroicon-o-truck',
            self::ORDER_FINISHED->value => 'heroicon-o-check-circle',
            self::ORDER_CANCELED->value => 'heroicon-o-x-circle',
        ];
    }
} 