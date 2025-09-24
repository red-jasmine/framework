<?php

return [
    // 公告相关
    'announcement' => [
        'title' => '公告',
        'create' => '创建公告',
        'edit' => '编辑公告',
        'delete' => '删除公告',
        'publish' => '发布公告',
        'revoke' => '撤销公告',
        'submit_approval' => '提交审批',
        'approve' => '审批通过',
        'reject' => '审批拒绝',

        // 字段
        'fields' => [
            'title' => '标题',
            'content' => '内容',
            'image' => '封面图片',
            'content_type' => '内容类型',
            'publish_time' => '发布时间',
            'is_force_read' => '强制阅读',
            'attachments' => '附件',
            'scopes' => '发布范围',
            'channels' => '发布渠道',
            'status' => '状态',
            'approval_status' => '审批状态',
            'approval_comment' => '审批意见',
        ],

        // 状态
        'status' => [
            'draft' => '草稿',
            'published' => '已发布',
            'revoked' => '已撤销',
        ],

        // 审批状态
        'approval_status' => [
            'pending' => '待审批',
            'approved' => '已通过',
            'rejected' => '已拒绝',
        ],

        // 内容类型
        'content_type' => [
            'text' => '纯文本',
            'rich' => '富文本',
            'markdown' => 'Markdown',
        ],

        // 消息
        'messages' => [
            'created_successfully' => '公告创建成功',
            'updated_successfully' => '公告更新成功',
            'deleted_successfully' => '公告删除成功',
            'published_successfully' => '公告发布成功',
            'revoked_successfully' => '公告撤销成功',
            'submitted_approval_successfully' => '审批提交成功',
            'approved_successfully' => '审批通过成功',
            'rejected_successfully' => '审批拒绝成功',
            'not_found' => '公告不存在',
            'unauthorized' => '无权访问此公告',
            'cannot_publish' => '当前状态不允许发布',
            'cannot_revoke' => '当前状态不允许撤销',
            'cannot_submit_approval' => '当前状态不允许提交审批',
            'cannot_approve' => '当前状态不允许审批',
            'cannot_reject' => '当前状态不允许拒绝',
        ],
    ],

    // 分类相关
    'category' => [
        'title' => '分类',
        'create' => '创建分类',
        'edit' => '编辑分类',
        'delete' => '删除分类',
        'show' => '显示分类',
        'hide' => '隐藏分类',
        'move' => '移动分类',

        // 字段
        'fields' => [
            'name' => '分类名称',
            'description' => '描述',
            'image' => '图片',
            'cluster' => '集群',
            'sort' => '排序',
            'icon' => '图标',
            'color' => '颜色',
            'is_show' => '是否显示',
            'parent_id' => '父分类',
        ],

        // 消息
        'messages' => [
            'created_successfully' => '分类创建成功',
            'updated_successfully' => '分类更新成功',
            'deleted_successfully' => '分类删除成功',
            'shown_successfully' => '分类显示成功',
            'hidden_successfully' => '分类隐藏成功',
            'moved_successfully' => '分类移动成功',
            'not_found' => '分类不存在',
            'unauthorized' => '无权访问此分类',
            'cannot_delete_with_children' => '存在子分类，无法删除',
            'cannot_delete_with_announcements' => '存在公告，无法删除',
            'cannot_move_to_self' => '不能移动到自身',
            'cannot_move_to_child' => '不能移动到子分类',
        ],
    ],

    // 通用消息
    'common' => [
        'success' => '操作成功',
        'error' => '操作失败',
        'confirm_delete' => '确定要删除吗？',
        'confirm_publish' => '确定要发布吗？',
        'confirm_revoke' => '确定要撤销吗？',
        'confirm_submit_approval' => '确定要提交审批吗？',
        'confirm_approve' => '确定要审批通过吗？',
        'confirm_reject' => '确定要审批拒绝吗？',
        'loading' => '加载中...',
        'no_data' => '暂无数据',
        'search' => '搜索',
        'filter' => '筛选',
        'reset' => '重置',
        'submit' => '提交',
        'cancel' => '取消',
        'save' => '保存',
        'edit' => '编辑',
        'delete' => '删除',
        'view' => '查看',
        'back' => '返回',
        'next' => '下一步',
        'previous' => '上一步',
        'confirm' => '确认',
        'close' => '关闭',
    ],
];
