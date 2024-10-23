<?php


return [

    'label'  => [
        'order' => '订单'
    ],
    'fields' => [
        'id'                     => '订单ID',
        'seller'                 => '卖家',
        'seller_type'            => '卖家类型',
        'seller_id'              => '卖家ID',
        'seller_nickname'        => '卖家昵称',
        'buyer'                  => '卖家',
        'buyer_type'             => '卖家类型',
        'buyer_id'               => '卖家ID',
        'buyer_nickname'         => '卖家昵称',
        'title'                  => '标题',
        'order_type'             => '订单类型',
        'pay_type'               => '支付类型',
        'order_status'           => '订单状态',
        'payment_status'         => '付款状态',
        'shipping_status'        => '发货状态',
        'refund_status'          => '退款状态',
        'rate_status'            => '评价状态',
        'settlement_status'      => '结算状态',
        'seller_custom_status'   => '卖家自定义状态',
        'product_amount'         => '商品金额',
        'cost_amount'            => '成本金额',
        'tax_amount'             => '税费金额',
        'commission_amount'      => '佣金',
        'product_payable_amount' => '商品应付金额',
        'freight_amount'         => '运费',
        'discount_amount'        => '订单优惠',
        'payable_amount'         => '应付金额',
        'payment_amount'         => '实付金额',
        'refund_amount'          => '退款金额',
        'service_amount'         => '服务费',
        'created_time'           => '创建时间',
        'payment_time'           => '付款时间',
        'close_time'             => '关闭时间',
        'shipping_time'          => '发货时间',
        'collect_time'           => '揽收时间',
        'dispatch_time'          => '派送时间',
        'signed_time'            => '签收时间',
        'confirm_time'           => '确认时间',
        'refund_time'            => '退款时间',
        'rate_time'              => '评价时间',
        'settlement_time'        => '结算时间',
        'channel_type'           => '渠道类型',
        'channel_id'             => '渠道ID',
        'channel_name'           => '渠道名称',
        'guide_type'             => '导购类型',
        'guide_id'               => '导购ID',
        'guide_name'             => '导购名称',
        'store_type'             => '门店类型',
        'store_id'               => '门店ID',
        'store_name'             => '门店名称',
        'client_type'            => '客户端类型',
        'client_version'         => '客户端版本',
        'client_ip'              => 'IP',
        'source_type'            => '来源类型',
        'source_id'              => '来源ID',
        'contact'                => '联系方式',
        'password'               => '查询密码',
        'star'                   => '加星',
        'is_seller_delete'       => '卖家删除',
        'is_buyer_delete'        => '买家删除',
        'outer_order_id'         => '外部订单号',
        'cancel_reason'          => '取消原因',
        'version'                => '版本',


        'product' => [
            'product_id' => '商品ID',
            'sku_id'     => '规格ID',
        ],

        'seller_remarks' => '卖家备注',
        'buyer_remarks'  => '买家备注',
        'buyer_message'  => '买家留言',
        'seller_message' => '卖家留言',
        'seller_expands' => '卖家扩展信息',
        'buyer_expands'  => '买家扩展信息',
        'other_expands'  => '其他扩展信息',
        'tools'          => '工具',

    ],
    'enums'  => [

        'order_type' => [
            'standard'       => '标准',
            'presale'        => '预售',
            'group_purchase' => '团购',
        ]

    ],
];
