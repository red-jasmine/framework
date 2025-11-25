<?php
return [
    'labels'  => [
        'product-stock' => '库存',
        'edit'          => '编辑库存'
    ],
    'fields'  => [
        'id'                => 'ID',
        'product_id'        => '商品ID',
        'variant_id'        => '变体ID',
        'warehouse_id'      => '仓库',
        'owner_type'        => '所属者类型',
        'owner_id'          => '所属者ID',
        'stock'             => '总库存',
        'available_stock'   => '可用库存',
        'locked_stock'      => '锁定库存',
        'reserved_stock'    => '预留库存',
        'safety_stock'      => '安全库存',
        'is_active'         => '是否启用',
        'priority'          => '优先级',
        'created_at'        => '创建时间',
        'updated_at'        => '更新时间',
        'creator_type'      => '创建者类型',
        'creator_id'        => '创建者ID',
    ],
    'options' => [
    ],
];
