<?php


return [
    'label'   => '售后',
    'labels'  => [
        'refund' => '售后',
        'status' => '状态',
        'amount' => '金额',
    ],
    'fields'  => [
        'id' => '售后编号',

        'order_product_type'   => '商品类型',
        'shipping_type'        => '发货类型',
        'refund_type'          => '售后类型',
        'phase'                => '售后阶段',
        'has_good_return'      => '是否需要退货',
        'good_status'          => '货物状态',
        'reason'               => '原因',
        'outer_refund_id'      => '外部退款单ID',
        'refund_amount'        => '退款金额',
        'freight_amount'       => '退邮费',
        'total_refund_amount'  => '总退款金额',
        'refund_status'        => '退款状态',
        'shipping_status'      => '发货状态',
        'created_time'         => '创建时间',
        'end_time'             => '完结时间',
        'seller_custom_status' => '卖家自定义状态',

        'star' => '加星',

        'title' => '标题',

        'product'          => '商品',
        'image'            => '商品图片',
        'product_type'     => '商品ID类型',
        'product_id'       => '商品ID',
        'sku_id'           => '规格ID',
        'outer_product_id' => '外部商品ID',
        'outer_sku_id'     => '外部规格ID',
        'barcode'          => '条码',
        'quantity'         => '数量',
        'unit'             => '单位',
        'unit_quantity'    => '单位数量',
        'category_id'      => '类目',
        'product_group_id' => '分组',
        'title'            => '商品名称',
        'sku_name'         => '规格名称',
        'price'            => '销售价',
        'cost_price'       => '成本价',

        'product_amount' => '商品金额',

        'tax_amount'             => '税额',
        'discount_amount'        => '优惠金额',
        'payable_amount'         => '应付金额',
        'payment_amount'         => '实付金额',
        'divided_payment_amount' => '分摊后实付金额',
        'progress'               => '进度',
        'progress_total'         => '进度总数',


        'seller_remarks' => '卖家备注',
        'buyer_remarks'  => '买家备注',
        'description'    => '描述',
        'images'         => '图片',
        'reject_reason'  => '拒绝理由',
        'extra'        => '扩展',

        // |--------------公共部分-------------------
        'order_id'         => '订单编号',
        'order_product_id' => '订单商品项编号',
        'refund_id'        => '售后编号',
        'entity_type'      => '对象类型',
        'entity_id'        => '对象编号',
        'seller_type'      => '卖家类型',
        'seller_id'        => '卖家ID',
        'seller_nickname'  => '卖家昵称',
        'buyer_type'       => '买家类型',
        'buyer_id'         => '买家ID',
        'buyer_nickname'   => '买家昵称',
        'version'          => '版本',
        'urge'             => '催单',
        'urge_time'        => '催单时间',
        'seller'           => '卖家',
        'buyer'            => '买家',
        'channel'          => '渠道',
        'store'            => '门店',
        'guide'            => '导购',
        'products'         => '商品',
        // |--------------公共部分-------------------


    ],
    'enums'   => [

        'order_type'     => [
            'standard'       => '标准',
            'presale'        => '预售',
            'group_purchase' => '团购',
        ],
        'refund_status'  => [
            'wait_seller_agree'        => '待卖家同意',
            'wait_seller_agree_return' => '待卖家同意退货',
            'wait_buyer_return_goods'  => '待买家退货',
            'wait_seller_reshipment'   => '待卖家发补',
            'wait_seller_confirm'      => '待卖家确认',
            'seller_reject_buyer'      => '卖家拒绝',
            'finished'                 => '已完成',
            'cancel'                   => '已取消',
            'closed'                   => '已关闭',
        ],
        'payment_status' => [
            'wait_pay'   => '待支付',
            'paying'     => '支付中',
            'part_pay'   => '部分支付',
            'paid'       => '已支付',
            'no_payment' => '无需支付',
        ],
        'accept_status'  => [
            'wait_accept' => '待接单',
            'accepted'    => '已接单',
            'rejected'    => '已拒单',
        ],

    ],
    'actions' => [
        'agree'            => '同意退款',
        'reject'           => '拒绝',
        'agree-reshipment' => '同意补发',
        'reshipment'       => '补发',
    ],

    'scopes' => [
        'all'                 => '全部',
        'wait_seller_agree'   => '待卖家处理',
        'wait_seller_handle'  => '待卖家处理',
        'wait_seller_confirm' => '待卖家确认',
        'wait_buyer_handle'   => '待买家处理',
        'refund_success'      => '已完成',
        'refund_cancel'       => '已取消',
    ],
];
