<?php
return [
    'labels'  => [

        'product-stock-log' => '库存变更记录',
    ],
    'fields'  => [
        'id'            => '序号',
        'owner_type'    => '所属者类型',
        'owner_id'      => '所属者UID',
        'action_type'   => '操作类型',
        'action_stock'  => '操作数量',
        'change_type'   => '更变类型',
        'change_detail' => '变更明细',
        'product_id'    => '商品ID',
        'sku_id'        => '规格ID',
        'lock_stock'    => '锁定库存',
        'channel_type'  => '渠道类型',
        'channel_id'    => '渠道ID',
        'creator_type'  => '创建者类型',
        'creator_id'    => '创建者UID',
        'created_at'    => '创建时间',
        'updated_at'    => '更新时间',
    ],
    'options' => [
    ],
];
