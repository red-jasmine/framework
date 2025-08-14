<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for various message-related
    | functionality in the application.
    |
    */

    'fields' => [
        'id' => 'ID',
        'biz' => '业务线',
        'category_id' => '分类ID',
        'receiver_id' => '接收人ID',
        'sender_id' => '发送人ID',
        'template_id' => '模板ID',
        'title' => '标题',
        'content' => '内容',
        'data' => '消息数据',
        'source' => '消息来源',
        'type' => '消息类型',
        'priority' => '优先级',
        'status' => '状态',
        'read_at' => '阅读时间',
        'channels' => '推送渠道',
        'push_status' => '推送状态',
        'is_urgent' => '强提醒',
        'is_burn_after_read' => '阅后即焚',
        'expires_at' => '过期时间',
        'owner_id' => '所属者ID',
        'operator_id' => '操作者ID',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
        'deleted_at' => '删除时间',
    ],

    'business_lines' => [
        'user' => '用户端',
        'merchant' => '商家端',
        'admin' => '管理端',
        'system' => '系统端',
    ],

    'sources' => [
        'system' => '系统',
        'user' => '用户',
        'api' => 'API',
    ],

    'types' => [
        'notification' => '通知',
        'alert' => '警告',
        'reminder' => '提醒',
    ],

    'priorities' => [
        'low' => '低',
        'normal' => '普通',
        'high' => '高',
        'urgent' => '紧急',
    ],

    'status' => [
        'unread' => '未读',
        'read' => '已读',
        'archived' => '已归档',
    ],

    'push_status' => [
        'pending' => '等待推送',
        'sent' => '已推送',
        'failed' => '推送失败',
    ],

    'channels' => [
        'in_app' => 'APP内消息',
        'push' => '推送通知',
        'email' => '邮件',
        'sms' => '短信',
    ],

    'category_status' => [
        'enable' => '启用',
        'disable' => '禁用',
    ],

    'template_status' => [
        'enable' => '启用',
        'disable' => '禁用',
    ],

    'actions' => [
        'send' => '发送消息',
        'batch_send' => '批量发送',
        'read' => '标记已读',
        'batch_read' => '批量已读',
        'archive' => '归档',
        'delete' => '删除',
        'create_category' => '创建分类',
        'update_category' => '更新分类',
        'delete_category' => '删除分类',
        'create_template' => '创建模板',
        'update_template' => '更新模板',
        'delete_template' => '删除模板',
    ],

    'messages' => [
        'sent_successfully' => '消息发送成功',
        'batch_sent_successfully' => '批量消息发送成功',
        'marked_as_read' => '消息已标记为已读',
        'batch_marked_as_read' => '消息已批量标记为已读',
        'archived_successfully' => '消息归档成功',
        'deleted_successfully' => '消息删除成功',
        'category_created' => '分类创建成功',
        'category_updated' => '分类更新成功',
        'category_deleted' => '分类删除成功',
        'template_created' => '模板创建成功',
        'template_updated' => '模板更新成功',
        'template_deleted' => '模板删除成功',
        'not_found' => '消息不存在',
        'access_denied' => '无权访问此消息',
        'expired' => '消息已过期',
        'already_read' => '消息已读',
        'category_not_found' => '分类不存在',
        'template_not_found' => '模板不存在',
        'invalid_receiver' => '接收人无效',
        'send_failed' => '消息发送失败',
        'push_failed' => '消息推送失败',
        'template_render_failed' => '模板渲染失败',
        'frequency_limit_exceeded' => '发送频率超限',
    ],

    'validations' => [
        'title_required' => '标题不能为空',
        'title_max' => '标题长度不能超过255个字符',
        'content_required' => '内容不能为空',
        'receiver_id_required' => '接收人ID不能为空',
        'receiver_id_invalid' => '接收人ID无效',
        'category_id_exists' => '分类不存在',
        'template_id_exists' => '模板不存在',
        'channels_array' => '推送渠道必须是数组',
        'channels_valid' => '推送渠道无效',
        'expires_at_date' => '过期时间必须是有效日期',
        'expires_at_after' => '过期时间必须是未来时间',
        'category_name_required' => '分类名称不能为空',
        'category_name_unique' => '分类名称已存在',
        'template_name_required' => '模板名称不能为空',
        'template_name_unique' => '模板名称已存在',
        'template_title_required' => '模板标题不能为空',
        'template_content_required' => '模板内容不能为空',
    ],
];
