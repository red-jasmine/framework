<?php

return [
    'attributes' => [
        'id' => 'ID',
        'org_id' => '组织ID',
        'name' => '职位名称',
        'code' => '职位编码',
        'level' => '职位级别',
        'description' => '职位描述',
        'sort' => '排序',
        'status' => '状态',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],

    'status' => [
        'active' => '启用',
        'inactive' => '禁用',
    ],

    'messages' => [
        'created_successfully' => '职位创建成功',
        'updated_successfully' => '职位更新成功',
        'deleted_successfully' => '职位删除成功',
        'not_found' => '职位不存在',
        'already_exists' => '职位已存在',
        'cannot_delete_with_members' => '不能删除有成员的职位',
        'code_exists' => '职位编码已存在',
    ],
];
