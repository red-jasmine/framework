<?php

return [
    'attributes' => [
        'id' => 'ID',
        'org_id' => '组织ID',
        'member_no' => '成员编号',
        'name' => '姓名',
        'nickname' => '昵称',
        'avatar' => '头像',
        'mobile' => '手机号',
        'email' => '邮箱',
        'gender' => '性别',
        'telephone' => '座机',
        'hired_at' => '入职时间',
        'resigned_at' => '离职时间',
        'status' => '状态',
        'position_name' => '主职位名称',
        'position_level' => '主职位级别',
        'main_department_id' => '主部门ID',
        'departments' => '有效部门集合',
        'leader_id' => '上级ID',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],

    'status' => [
        'active' => '在职',
        'inactive' => '离职',
        'suspended' => '停职',
    ],

    'gender' => [
        'male' => '男',
        'female' => '女',
        'other' => '其他',
    ],

    'messages' => [
        'created_successfully' => '成员创建成功',
        'updated_successfully' => '成员更新成功',
        'deleted_successfully' => '成员删除成功',
        'not_found' => '成员不存在',
        'already_exists' => '成员已存在',
        'cannot_delete_self' => '不能删除自己',
        'cannot_set_self_as_leader' => '不能将自己设为上级',
        'invalid_leader' => '无效的上级',
        'invalid_department' => '无效的部门',
        'member_no_exists' => '成员编号已存在',
        'email_exists' => '邮箱已存在',
        'mobile_exists' => '手机号已存在',
    ],
];
