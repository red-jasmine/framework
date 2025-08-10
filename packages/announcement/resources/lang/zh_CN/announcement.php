<?php

return [
    // 通用
    'announcement' => '公告',
    'category' => '分类',
    'title' => '标题',
    'content' => '内容',
    'status' => '状态',
    'created_at' => '创建时间',
    'updated_at' => '更新时间',
    
    // 状态
    'status_draft' => '草稿',
    'status_published' => '已发布',
    'status_revoked' => '已撤销',
    
    // 审批状态
    'approval_status_pending' => '待审批',
    'approval_status_approved' => '已通过',
    'approval_status_rejected' => '已拒绝',
    
    // 内容类型
    'content_type_text' => '文本',
    'content_type_rich' => '富文本',
    'content_type_markdown' => 'Markdown',
    
    // 操作
    'create' => '创建',
    'edit' => '编辑',
    'delete' => '删除',
    'publish' => '发布',
    'revoke' => '撤销',
    'submit_approval' => '提交审批',
    'approve' => '审批通过',
    'reject' => '审批拒绝',
    'show' => '显示',
    'hide' => '隐藏',
    'move' => '移动',
    
    // 消息
    'create_success' => '创建成功',
    'update_success' => '更新成功',
    'delete_success' => '删除成功',
    'publish_success' => '发布成功',
    'revoke_success' => '撤销成功',
    'submit_approval_success' => '提交审批成功',
    'approve_success' => '审批通过',
    'reject_success' => '审批拒绝',
    'show_success' => '显示成功',
    'hide_success' => '隐藏成功',
    'move_success' => '移动成功',
    
    // 错误消息
    'not_found' => '记录不存在',
    'no_permission' => '无权限操作',
    'invalid_status' => '状态无效',
    'cannot_publish' => '无法发布',
    'cannot_revoke' => '无法撤销',
    'cannot_approve' => '无法审批',
    'cannot_reject' => '无法拒绝',
    
    // 验证消息
    'title_required' => '标题不能为空',
    'title_max' => '标题长度不能超过255个字符',
    'content_required' => '内容不能为空',
    'category_id_exists' => '分类不存在',
    'name_required' => '分类名称不能为空',
    'name_max' => '分类名称长度不能超过100个字符',
];
