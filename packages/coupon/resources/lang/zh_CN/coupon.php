<?php

return [

    'labels' => [
        'coupon'            => '优惠券',
        'discount'          => '优惠',
        'basic_info'        => '基础信息',
        'discount_settings' => '优惠设置',
        'validity_settings' => '有效期',
        'rules_settings'    => '规则配置',
    ],

    'label' => [
        'threshold' => [
            'amount_over'   => '满:amount',
            'quantity_over' => '满:quantity件',
        ],
        'discount'  => [
            'fixed_amount'      => '减:amount',
            'fixed_amount_yuan' => '减:amount元',
            'percentage'        => '打:rate折',
        ],
    ],

    'fields' => [
        'id'                     => 'ID',
        'name'                   => '优惠券名称',
        'label'                  => '标签',
        'description'            => '描述',
        'image'                  => '图片',
        'is_show'                => '是否显示',
        'status'                 => '状态',
        'discount_level'         => '优惠券级别',
        'discount_amount_type'   => '优惠类型',
        'discount_amount_value'  => '优惠值',
        'threshold_type'         => '门槛类型',
        'threshold_value'        => '门槛值',
        'max_discount_amount'    => '最大优惠金额',
        'validity_type'          => '有效期类型',
        'validity_start_time'    => '有效期开始时间',
        'validity_end_time'      => '有效期结束时间',
        'delayed_effective_time' => '延迟生效时间',
        'validity_time'          => '有效期时长',
        'usage_rules'            => '使用规则',
        'receive_rules'          => '领取规则',
        'cost_bearer'            => '成本承担方',
        'sort'                   => '排序',
        'remarks'                => '备注',
        'total_quantity'         => '总数量',
        'total_issued'           => '已发放数量',
        'total_used'             => '已使用数量',
        'start_time'             => '开始时间',
        'end_time'               => '结束时间',
        'created_at'             => '创建时间',
        'updated_at'             => '更新时间',

        // 用户优惠券字段
        'coupon_id'              => '优惠券ID',
        'coupon_name'            => '优惠券名称',
        'user_id'                => '用户ID',
        'user_name'              => '用户名称',
        'issue_time'             => '发放时间',
        'expire_time'            => '过期时间',
        'used_time'              => '使用时间',
        'order_id'               => '订单ID',
        'order_no'               => '订单号',

        // 使用记录字段
        'threshold_amount'       => '门槛金额',
        'discount_amount'        => '优惠金额',
        'final_discount_amount'  => '最终优惠金额',
        'used_at'                => '使用时间',

        // 统计字段
        'date'                   => '日期',
        'total_expired'          => '过期数量',
        'total_cost'             => '总成本',
        'last_updated'           => '最后更新时间',
    ],

    'commands' => [
        'publish'     => '发布',
        'pause'       => '暂停',
        'expire'      => '过期',
        'issue'       => '发放',
        'use'         => '使用',
        'batch_issue' => '批量发放',
        'export'      => '导出',
    ],

    'filters' => [
        'status'               => '状态',
        'validity_type'        => '有效期类型',
        'discount_amount_type' => '优惠类型',
        'date_range'           => '日期范围',
        'user_id'              => '用户ID',
    ],

    'tabs' => [
        'all'       => '全部',
        'draft'     => '草稿',
        'published' => '已发布',
        'paused'    => '已暂停',
        'expired'   => '已过期',
        'available' => '可用',
        'used'      => '已使用',
    ],

    'enums' => [
        'status'               => [
            'draft'     => '草稿',
            'published' => '已发布',
            'paused'    => '已暂停',
            'expired'   => '已过期',
        ],
        'discount_amount_type' => [
            'fixed_amount' => '满减',
            'percentage'   => '折扣',
        ],
        'threshold_type'       => [
            'amount'   => '金额',
            'quantity' => '件数',
        ],
        'discount_level'       => [
            'order'    => '订单',
            'product'  => '商品',
            'shipping' => '运费',
            'checkout' => '结算',
        ],
        'validity_type'        => [
            'absolute' => '绝对时间',
            'relative' => '相对时间',
        ],
        'issue_strategy'       => [
            'manual' => '手动发放',
            'auto'   => '自动发放',
            'code'   => '兑换码',
        ],
        'cost_bearer_type'     => [
            'platform'    => '平台',
            'merchant'    => '商家',
            'broadcaster' => '主播',
        ],
        'user_coupon_status'   => [
            'available' => '可用',
            'used'      => '已使用',
            'expired'   => '已过期',
        ],
        'rule_type'            => [
            'exclude' => '排除',
            'include' => '包含',
        ],
        'rule_object_type'     => [
            'product'    => '商品',
            'brand'      => '品牌',
            'category'   => '类目',
            'user_group' => '用户分组',
        ],
        'coupon_type'          => [
            'shop'   => '商家券',
            'system' => '平台券'
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