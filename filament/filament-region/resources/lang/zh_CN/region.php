<?php

return [
    'label' => '地区管理',

    'labels' => [
        'title' => '行政区划',
    ],

    'fields' => [
        'code' => '代码',
        'parent_code' => '上级区划',
        'parent_code_placeholder' => '选择上级区划（留空为顶级）',
        'parent_code_helper' => '请先选择国家，再选择父级区域',
        'country_code' => '国家',
        'type' => '类型',
        'name' => '名称',
        'region' => '大区',
        'level' => '层级',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
];

