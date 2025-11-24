<?php

namespace RedJasmine\Product\Domain\Stock\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStockActionTypeEnum: string
{

    use EnumsHelper;

    // ========== 商家手动操作（直接操作总库存）==========
    // 增加: stock += quantity, available_stock 自动计算 (商家手动增加库存)
    // 减少: stock -= quantity, available_stock 自动计算 (商家手动减少库存)
    // 重置: stock = value, available_stock 自动计算 (商家设置库存)

    // ========== 订单流程操作（不直接操作总库存）==========
    // 锁定: locked_stock += quantity, available_stock 自动计算 (下单时锁定库存)
    // 解锁: locked_stock -= quantity, available_stock 自动计算 (订单取消时解锁)
    // 预留: locked_stock -= quantity, reserved_stock += quantity, available_stock 自动计算 (支付成功时预留)
    // 释放: reserved_stock -= quantity, available_stock 自动计算 (订单退款时释放预留)
    // 扣减: reserved_stock -= quantity, stock -= quantity, available_stock 自动计算 (发货时扣减)

    // 注意：available_stock = stock - locked_stock - reserved_stock，所有操作后都会自动重新计算

    // ========== 商家手动操作 ==========
    case ADD = 'add';      // 增加库存
    case SUB = 'sub';      // 减少库存（商家手动扣减）
    case RESET = 'reset';  // 重置库存

    // ========== 订单流程操作 ==========
    case LOCK = 'lock';        // 锁定库存（下单）
    case UNLOCK = 'unlock';    // 解锁库存（订单取消）
    case RESERVE = 'reserve';  // 预留库存（支付成功，原 CONFIRM）
    case RELEASE = 'release';  // 释放库存（订单退款）
    case DEDUCT = 'deduct';    // 扣减库存（发货）

    // ========== 向后兼容（已废弃，建议使用 RESERVE）==========
    /** @deprecated 使用 RESERVE 替代 */
    case CONFIRM = 'confirm';






    public static function labels() : array
    {
        return [
            // 商家手动操作
            self::RESET->value   => '设置',
            self::ADD->value     => '增加',
            self::SUB->value     => '减少',
            // 订单流程操作
            self::LOCK->value    => '锁定',
            self::UNLOCK->value  => '解锁',
            self::RESERVE->value => '预留',
            self::RELEASE->value => '释放',
            self::DEDUCT->value  => '扣减',

        ];
    }


    /**
     * 获取允许商家手动操作的类型
     * 用于前端表单限制，只允许商家进行增加、减少、设置操作
     */
    public static function allowActionTypes() : array
    {
        return [
            self::RESET->value => '设置',
            self::ADD->value   => '增加',
            self::SUB->value   => '减少',
        ];
    }

    /**
     * 判断是否为商家手动操作
     */
    public function isManualOperation() : bool
    {
        return in_array($this, [self::ADD, self::SUB, self::RESET], true);
    }

    /**
     * 判断是否为订单流程操作
     */
    public function isOrderOperation() : bool
    {
        return in_array($this, [self::LOCK, self::UNLOCK, self::RESERVE, self::RELEASE, self::DEDUCT], true);
    }
}
