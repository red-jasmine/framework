# 消息领域存储层设计

## 1. 数据模型设计

### 1.1 核心数据表

#### 1.1.1 消息表 (messages)

消息系统的核心数据表，存储消息的完整信息

```sql
CREATE TABLE `messages`
(
    `id`                     bigint unsigned NOT NULL,
    `biz`                    varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务线',
    `category_id`            bigint unsigned DEFAULT NULL COMMENT '消息分类ID',
    `receiver_id`            varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '接收人ID',
    `sender_id`              varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '发送人ID',
    `template_id`            bigint unsigned DEFAULT NULL COMMENT '消息模板ID',
    `title`                  varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息标题',
    `content`                text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息内容',
    `data`                   json DEFAULT NULL COMMENT '消息数据',
    `source`                 enum('system','user','api') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system' COMMENT '消息来源',
    `type`                   enum('notification','alert','reminder') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'notification' COMMENT '消息类型',
    `priority`               enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '优先级',
    `status`                 enum('unread','read','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread' COMMENT '消息状态',
    `read_at`                timestamp NULL DEFAULT NULL COMMENT '阅读时间',
    `channels`               json DEFAULT NULL COMMENT '推送渠道配置',
    `push_status`            enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '推送状态',
    `is_urgent`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否强提醒',
    `is_burn_after_read`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否阅后即焚',
    `expires_at`             timestamp NULL DEFAULT NULL COMMENT '过期时间',
    `owner_id`               varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所属者ID',
    `operator_id`            varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '操作者ID',
    `created_at`             timestamp NULL DEFAULT NULL,
    `updated_at`             timestamp NULL DEFAULT NULL,
    `deleted_at`             timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY                      `idx_receiver_status` (`receiver_id`,`status`,`created_at`),
    KEY                      `idx_owner_biz` (`owner_id`,`biz`,`created_at`),
    KEY                      `idx_push_status` (`push_status`,`created_at`),
    KEY                      `idx_category` (`category_id`),
    KEY                      `idx_template` (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='消息表';
```

#### 1.1.2 消息分类表 (message_categories)

消息的分类管理表，提供消息的组织和展示结构

```sql
CREATE TABLE `message_categories`
(
    `id`          bigint unsigned NOT NULL AUTO_INCREMENT,
    `biz`         varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务线',
    `name`        varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
    `description` text COLLATE utf8mb4_unicode_ci COMMENT '分类描述',
    `icon`        varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图标',
    `color`       varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '颜色',
    `sort`        int NOT NULL DEFAULT '0' COMMENT '排序',
    `status`      enum('enable','disable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enable' COMMENT '状态',
    `owner_id`    varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所属者ID',
    `created_at`  timestamp NULL DEFAULT NULL,
    `updated_at`  timestamp NULL DEFAULT NULL,
    `deleted_at`  timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_owner_biz_name` (`owner_id`,`biz`,`name`),
    KEY           `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='消息分类表';
```

#### 1.1.3 消息模板表 (message_templates)

消息内容的模板化管理表，支持变量替换和内容标准化

```sql
CREATE TABLE `message_templates`
(
    `id`               bigint unsigned NOT NULL AUTO_INCREMENT,
    `name`             varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模板名称',
    `title_template`   text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题模板',
    `content_template` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容模板',
    `variables`        json DEFAULT NULL COMMENT '模板变量定义',
    `status`           enum('enable','disable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enable' COMMENT '状态',
    `created_at`       timestamp NULL DEFAULT NULL,
    `updated_at`       timestamp NULL DEFAULT NULL,
    `deleted_at`       timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_name` (`name`),
    KEY                `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='消息模板表';
```

#### 1.1.4 推送日志表 (message_push_logs)

消息推送过程的详细记录表，用于状态跟踪和问题排查

```sql
CREATE TABLE `message_push_logs`
(
    `id`            bigint unsigned NOT NULL AUTO_INCREMENT,
    `message_id`    bigint unsigned NOT NULL COMMENT '消息ID',
    `channel`       enum('in_app','push','email','sms') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '推送渠道',
    `status`        enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '推送状态',
    `pushed_at`     timestamp NULL DEFAULT NULL COMMENT '推送时间',
    `error_message` text COLLATE utf8mb4_unicode_ci COMMENT '错误信息',
    `retry_count`   int NOT NULL DEFAULT '0' COMMENT '重试次数',
    `created_at`    timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY             `idx_message` (`message_id`),
    KEY             `idx_channel_status` (`channel`,`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='推送日志表';
```

### 1.2 数据关系图

<!--@include: ./database-relation.puml-->

### 1.3 索引设计说明

#### 1.3.1 消息表索引

- **idx_receiver_status**: 用于用户查询自己的消息，按状态和时间排序
- **idx_owner_biz**: 用于按所属者和业务线统计消息数据
- **idx_push_status**: 用于推送任务处理，查询待推送的消息
- **idx_category**: 用于按分类查询消息
- **idx_template**: 用于按模板查询消息使用情况

#### 1.3.2 消息分类表索引

- **uk_owner_biz_name**: 确保同一所有者同一业务线下分类名称唯一
- **idx_status**: 用于查询启用状态的分类

#### 1.3.3 消息模板表索引

- **uk_name**: 确保模板名称全局唯一
- **idx_status**: 用于查询启用状态的模板

#### 1.3.4 推送日志表索引

- **idx_message**: 用于查询特定消息的推送记录
- **idx_channel_status**: 用于按渠道和状态统计推送效果

## 2. 基础设施层设计

### 2.1 仓库实现

#### 2.1.1 消息写操作仓库 (MessageRepository)

**功能描述**: 消息实体的持久化存储和基本写操作
**核心方法**:
- store(): 存储消息实体
- update(): 更新消息信息
- delete(): 删除消息（软删除）
- findByReceiver(): 根据接收人查找消息
- markAsRead(): 批量标记消息为已读

#### 2.1.2 消息只读仓库 (MessageReadRepository)

**功能描述**: 消息的复杂查询和统计分析操作
**核心方法**:
- paginate(): 分页查询消息
- findList(): 根据ID列表查找消息
- getUnreadCount(): 获取未读消息数量
- getStatistics(): 获取消息统计数据
- searchMessages(): 全文搜索消息

### 2.2 过滤器配置

#### 2.2.1 允许的过滤器

- **精确匹配过滤器**: biz、category_id、receiver_id、sender_id、status、type、priority、is_urgent、owner_id
- **部分匹配过滤器**: title、content（支持模糊搜索）
- **范围过滤器**: created_between、read_between（时间范围查询）
- **回调过滤器**: 自定义复杂查询条件

#### 2.2.2 允许的排序

- **基础排序**: id、created_at、read_at、updated_at
- **业务排序**: priority（优先级排序）、status（状态排序）
- **自定义排序**: 支持多字段组合排序

#### 2.2.3 允许的关联

- **基础关联**: category（消息分类）、template（消息模板）
- **用户关联**: sender（发送人）、receiver（接收人）
- **扩展关联**: pushLogs（推送日志）

## 3. 数据迁移策略

### 3.1 版本管理

- **迁移文件命名**: 使用时间戳+描述的格式，确保迁移顺序
- **向前兼容**: 新版本迁移必须兼容旧版本数据结构
- **回滚支持**: 每个迁移都必须提供对应的回滚脚本

### 3.2 向后兼容

- **字段添加**: 新增字段必须设置默认值或允许NULL
- **字段修改**: 避免直接修改字段类型，采用新增+数据迁移+删除旧字段的方式
- **索引变更**: 新增索引在业务低峰期执行，删除索引需要确认无业务影响

### 3.3 数据备份

- **迁移前备份**: 执行重要迁移前必须进行全量数据备份
- **增量备份**: 建立定期的增量备份机制
- **备份验证**: 定期验证备份数据的完整性和可恢复性

### 3.4 回滚策略

- **快速回滚**: 对于可逆操作，提供快速回滚脚本
- **数据回滚**: 对于数据变更，准备数据回滚方案
- **服务回滚**: 配合应用版本回滚，确保数据结构兼容性

## 4. 性能优化

### 4.1 查询优化

- **索引优化**: 根据查询模式设计合适的复合索引
- **分区策略**: 对于大表可考虑按时间或业务线进行分区
- **查询缓存**: 对于频繁查询的数据使用Redis缓存

### 4.2 存储优化

- **数据压缩**: 对于大文本字段启用压缩
- **冷热分离**: 将历史数据迁移到归档表
- **清理策略**: 定期清理过期和已删除的数据

### 4.3 并发控制

- **乐观锁**: 使用版本号控制并发更新
- **分布式锁**: 对于关键操作使用分布式锁
- **读写分离**: 读操作使用只读副本，减轻主库压力

## 5. 数据安全

### 5.1 敏感数据保护

- **内容加密**: 敏感消息内容进行加密存储
- **访问控制**: 严格的数据访问权限控制
- **审计日志**: 记录所有数据访问和修改操作

### 5.2 数据完整性

- **外键约束**: 使用外键保证数据引用完整性
- **数据校验**: 在应用层和数据库层都进行数据校验
- **事务控制**: 使用事务保证数据操作的原子性

### 5.3 备份恢复

- **定期备份**: 建立自动化的数据备份机制
- **灾难恢复**: 制定完整的灾难恢复方案
- **备份测试**: 定期测试备份数据的可恢复性

## 6. 监控告警

### 6.1 性能监控

- **查询性能**: 监控慢查询和查询响应时间
- **存储使用**: 监控磁盘空间和表大小增长
- **连接数**: 监控数据库连接数和连接池状态

### 6.2 业务监控

- **消息量**: 监控消息发送量和增长趋势
- **推送成功率**: 监控各渠道推送成功率
- **用户活跃度**: 监控用户消息阅读行为

### 6.3 异常告警

- **错误告警**: 数据库错误和异常情况告警
- **性能告警**: 性能指标超过阈值时告警
- **容量告警**: 存储容量接近上限时告警
