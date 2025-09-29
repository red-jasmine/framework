<?php

return [
    'attributes' => [
        'id' => 'ID',
        'org_id' => '组织ID',
        'parent_id' => '父部门ID',
        'name' => '部门名称',
        'code' => '部门编码',
        'description' => '部门描述',
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
        'created_successfully' => '部门创建成功',
        'updated_successfully' => '部门更新成功',
        'deleted_successfully' => '部门删除成功',
        'not_found' => '部门不存在',
        'already_exists' => '部门已存在',
        'cannot_delete_with_children' => '不能删除有子部门的部门',
        'cannot_delete_with_members' => '不能删除有成员的部门',
        'code_exists' => '部门编码已存在',
        'invalid_parent' => '无效的父部门',
        'circular_reference' => '不能设置自己为父部门',
    ],
];
