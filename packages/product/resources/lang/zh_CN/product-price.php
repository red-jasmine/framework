<?php

return [
    'labels'     => [
        'price'               => '价格管理',
        'basic_info'          => '基本信息',
        'basic_info_desc'     => '设置价格的基本信息和维度',
        'price_info'          => '价格信息',
        'price_info_desc'     => '设置销售价、市场价和成本价',
        'variants_price'      => '规格价格',
        'variants_price_desc' => '为商品的所有规格设置价格',
    ],
    'fields'     => [
        'variants'     => '规格列表',
        'id'           => 'ID',
        'product'      => '商品',
        'variant'      => '规格',
        'market'       => '市场',
        'store'        => '门店',
        'user_level'   => '用户等级',
        'currency'     => '货币',
        'price'        => '销售价',
        'market_price' => '市场价',
        'cost_price'   => '成本价',
        'quantity'     => '数量',
        'created_at'   => '创建时间',
        'updated_at'   => '更新时间',
    ],
    'helpers'    => [
        'product'      => '选择要设置价格的商品',
        'variant'      => '选择要设置价格的规格（SKU）',
        'market'       => '选择适用的市场，* 表示所有市场',
        'store'        => '选择适用的门店，* 表示所有门店',
        'user_level'   => '选择适用的用户等级，* 表示所有用户等级',
        'price'        => '设置销售价格',
        'market_price' => '设置市场参考价（可选）',
        'cost_price'   => '设置成本价（可选）',
        'quantity'     => '阶梯定价使用的数量',
    ],
    'market'     => [
        'all' => '所有市场',
        'cn'  => '中国市场',
        'us'  => '美国市场',
        'de'  => '德国市场',
    ],
    'store'      => [
        'all'     => '所有门店',
        'default' => '默认门店',
    ],
    'user_level' => [
        'all'      => '所有用户',
        'default'  => '普通用户',
        'vip'      => 'VIP会员',
        'gold'     => '黄金会员',
        'platinum' => '白金会员',
    ],
];

