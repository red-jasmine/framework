<?php

return [
    'labels' => [

        'user_coupon'   => '用户优惠券',
        'my_coupons'    => '我的优惠券',
        'coupon_wallet' => '优惠券钱包',
    ],

    'fields' => [
        'id'                  => 'ID',
        'coupon_id'           => '优惠券ID',
        'coupon_name'         => '优惠券名称',
        'coupon_no'           => '优惠券编号',
        'user_id'             => '用户ID',
        'user_name'           => '用户名称',
        'user_type'           => '用户类型',
        'owner_type'          => '所有者类型',
        'owner_id'            => '所有者ID',
        'status'              => '状态',
        'issue_time'          => '发放时间',
        'validity_start_time' => '有效期开始时间',
        'validity_end_time'   => '有效期结束时间',
        'expire_time'         => '过期时间',
        'used_time'           => '使用时间',
        'order_id'            => '订单ID',
        'order_no'            => '订单号',
        'remaining_days'      => '剩余天数',
        'remaining_hours'     => '剩余小时',
        'display_name'        => '显示名称',
        'created_at'          => '创建时间',
        'updated_at'          => '更新时间',
    ],

    'enums' => [
        'status' => [
            'available' => '可用',
            'used'      => '已使用',
            'expired'   => '已过期',
        ],
    ],

    'commands' => [
        'receive'     => '领取',
        'use'         => '使用',
        'view_detail' => '查看详情',
        'batch_issue' => '批量发放',
        'export'      => '导出',
        'expire'      => '过期',
    ],

    'filters' => [
        'status'      => '状态',
        'user_id'     => '用户ID',
        'coupon_id'   => '优惠券ID',
        'issue_time'  => '发放时间',
        'expire_time' => '过期时间',
        'used_time'   => '使用时间',
        'owner_type'  => '所有者类型',
        'owner_id'    => '所有者ID',
    ],

    'tabs' => [
        'all'           => '全部',
        'available'     => '可用',
        'used'          => '已使用',
        'expired'       => '已过期',
        'expiring_soon' => '即将过期',
    ],

    'messages' => [
        'issued'            => '优惠券发放成功',
        'received'          => '优惠券领取成功',
        'used'              => '优惠券使用成功',
        'expired'           => '用户优惠券已过期',
        'batch_issued'      => '批量发放优惠券成功',
        'not_available'     => '用户优惠券不可用',
        'not_found'         => '用户优惠券不存在',
        'already_used'      => '优惠券已使用',
        'already_expired'   => '优惠券已过期',
        'remaining_time'    => '剩余:days天:hours小时',
        'expiring_soon'     => '优惠券即将过期',
        'operation_success' => '操作成功',
        'operation_failed'  => '操作失败',
        'permission_denied' => '权限不足',
        'validation_failed' => '验证失败',
    ],

    'descriptions' => [
        'user_coupon'      => '用户拥有的优惠券，包含发放时间、过期时间、使用状态等信息',
        'available_status' => '可用状态的优惠券，用户可以在订单中使用',
        'used_status'      => '已使用状态的优惠券，已在订单中使用过',
        'expired_status'   => '已过期状态的优惠券，超过有效期无法使用',
        'remaining_days'   => '优惠券剩余的有效天数',
        'remaining_hours'  => '优惠券剩余的有效小时数',
        'display_name'     => '优惠券的显示名称，包含优惠券名称和状态',
    ],

    'validations' => [
        'coupon_id_required'      => '优惠券ID不能为空',
        'user_id_required'        => '用户ID不能为空',
        'issue_time_required'     => '发放时间不能为空',
        'expire_time_required'    => '过期时间不能为空',
        'expire_time_after_issue' => '过期时间必须晚于发放时间',
        'status_invalid'          => '状态值无效',
        'coupon_not_exists'       => '优惠券不存在',
        'user_not_exists'         => '用户不存在',
        'already_issued'          => '优惠券已发放给该用户',
        'issue_limit_exceeded'    => '发放数量超过限制',
    ],
]; 