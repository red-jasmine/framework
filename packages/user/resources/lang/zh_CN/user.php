<?php
return [
    'labels' => [

        'title' => '用户'
    ],


    'fields' => [
        'id'                => '用户ID',
        'name'              => '用户名',
        'email'             => '邮箱',
        'phone'            => '手机号',
        'password'          => '密码',
        'remember_token'    => '记住我',
        'created_at'        => '创建时间',
        'updated_at'        => '更新时间',
        'deleted_at'        => '删除时间',
        'roles'             => '角色',
        'permissions'       => '权限',
        'avatar'            => '头像',
        'nickname'          => '昵称',
        'gender'            => '性别',
        'birthday'          => '生日',
        'biography'         => '个人介绍',
        'type'              => '类型',
        'status'            => '状态',
        'email_verified_at' => '邮箱验证时间',
        'last_active_at'    => '活跃时间',


    ],

    'enums' => [
        'status' => [
            'unactivated' => '未激活',
            'activated'   => '正常',
            'suspended'   => '停用',
            'disabled'    => '禁用',
            'canceled'    => '已注销',
        ],
        'type'   => [
            'personal'     => '个人',
            'company'      => '公司',
            'organization' => '机构',

        ],
        'gender' => [
            'male'    => '男',
            'female'  => '女',
            'secrecy' => '保密',
            'other'   => '其他',
        ],

    ],

];