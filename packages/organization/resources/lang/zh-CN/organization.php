<?php

return [
    'attributes' => [
        'id' => 'ID',
        'owner_id' => '所有者ID',
        'name' => '组织名称',
        'code' => '组织编码',
        'type' => '组织类型',
        'description' => '组织描述',
        'logo' => '组织Logo',
        'website' => '网站',
        'phone' => '电话',
        'email' => '邮箱',
        'address' => '地址',
        'status' => '状态',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],

    'type' => [
        'company' => '公司',
        'department' => '部门',
        'team' => '团队',
        'project' => '项目',
    ],

    'status' => [
        'active' => '启用',
        'inactive' => '禁用',
    ],

    'messages' => [
        'created_successfully' => '组织创建成功',
        'updated_successfully' => '组织更新成功',
        'deleted_successfully' => '组织删除成功',
        'not_found' => '组织不存在',
        'already_exists' => '组织已存在',
        'cannot_delete_with_departments' => '不能删除有部门的组织',
        'cannot_delete_with_members' => '不能删除有成员的组织',
        'code_exists' => '组织编码已存在',
    ],
];
