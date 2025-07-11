<?php

return [
    'enums'    => [
        'status'             => [
            'draft'     => '草稿',
            'published' => '已发布',
            'paused'    => '已暂停',
            'expired'   => '已过期',
        ],
        'discount_type'      => [
            'fixed_amount' => '固定金额',
            'percentage'   => '百分比',
        ],
        'threshold_type'     => [
            'order_amount'       => '订单金额',
            'product_amount'     => '商品金额',
            'shipping_amount'    => '运费金额',
            'cross_store_amount' => '跨店金额',
        ],
        'validity_type'      => [
            'absolute' => '绝对时间',
            'relative' => '相对时间',
        ],
        'issue_strategy'     => [
            'manual' => '手动发放',
            'auto'   => '自动发放',
            'code'   => '兑换码',
        ],
        'cost_bearer_type'   => [
            'platform'    => '平台',
            'merchant'    => '商家',
            'broadcaster' => '主播',
        ],
        'user_coupon_status' => [
            'available' => '可用',
            'used'      => '已使用',
            'expired'   => '已过期',
        ],
    ],
    'label' => [
        'threshold' => [
            'amount_over' => '满:amount',
            'quantity_over' => '满:quantity件',
        ],
        'discount' => [
            'fixed_amount' => '减:amount',
            'fixed_amount_yuan' => '减:amount元',
            'percentage' => '打:rate折',
        ],
    ],
    'messages' => [
        'created'         => '优惠券创建成功',
        'updated'         => '优惠券更新成功',
        'deleted'         => '优惠券删除成功',
        'published'       => '优惠券发布成功',
        'paused'          => '优惠券暂停成功',
        'issued'          => '优惠券发放成功',
        'received'        => '优惠券领取成功',
        'used'            => '优惠券使用成功',
        'expired'         => '优惠券已过期',
        'not_found'       => '优惠券不存在',
        'not_available'   => '优惠券不可用',
        'already_used'    => '优惠券已使用',
        'already_expired' => '优惠券已过期',
        'limit_reached'   => '优惠券领取限制已达',
        'invalid_code'    => '无效的兑换码',
    ],
]; 