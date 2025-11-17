<?php

return [
    'label' => '仓库管理',
    'labels' => [
        'warehouse' => '仓库',
        'navigation_group' => '仓库管理',
        'markets' => '市场/门店关联',
        'markets_desc' => '配置仓库关联的市场和门店信息',
    ],
    'fields' => [
        'id' => 'ID',
        'code' => '仓库编码',
        'name' => '仓库名称',
        'warehouse_type' => '仓库类型',
        'address' => '地址',
        'contact_phone' => '联系电话',
        'contact_person' => '联系人',
        'is_active' => '是否启用',
        'is_default' => '是否默认仓库',
        'markets' => '市场/门店',
        'market' => '市场代码',
        'store' => '门店代码',
        'market_is_active' => '是否启用',
        'market_is_primary' => '是否主要',
    ],
];

