---
title: 存储层表设计
outline: deep
order: 10
---

# 邀请领域存储层表设计

## 数据库表概览

邀请领域包含以下核心数据表：

- `invitation_codes` - 邀请码主表
- `invitation_destinations` - 邀请去向配置表  
- `invitation_templates` - 邀请模板表
- `invitation_usage_logs` - 邀请使用记录表
- `invitation_statistics` - 邀请统计表

## 表结构设计

### 邀请码表 (invitation_codes)

```sql
CREATE TABLE `invitation_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `code` varchar(50) NOT NULL COMMENT '邀请码',
  `inviter_type` varchar(50) NOT NULL COMMENT '邀请人类型',
  `inviter_id` varchar(100) NOT NULL COMMENT '邀请人ID',
  `inviter_name` varchar(100) NOT NULL COMMENT '邀请人名称',
  `title` varchar(200) NOT NULL COMMENT '邀请标题',
  `description` text COMMENT '邀请描述',
  `slogan` varchar(500) COMMENT '广告语',
  `generate_type` enum('custom','system') NOT NULL DEFAULT 'system' COMMENT '生成类型',
  `max_usage` int unsigned NOT NULL DEFAULT 0 COMMENT '最大使用次数，0表示无限制',
  `used_count` int unsigned NOT NULL DEFAULT 0 COMMENT '已使用次数',
  `expires_at` timestamp NULL COMMENT '过期时间',
  `status` enum('active','disabled','expired') NOT NULL DEFAULT 'active' COMMENT '状态',
  `tags` json COMMENT '标签信息',
  `extra_data` json COMMENT '扩展数据',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  KEY `idx_inviter` (`inviter_type`, `inviter_id`),
  KEY `idx_status_expires` (`status`, `expires_at`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请码表';
```

### 邀请去向表 (invitation_destinations)

```sql
CREATE TABLE `invitation_destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `invitation_code_id` bigint unsigned NOT NULL COMMENT '邀请码ID',
  `destination_type` enum('register','product','activity','home','custom') NOT NULL COMMENT '去向类型',
  `destination_id` varchar(100) COMMENT '目标ID',
  `destination_url` varchar(1000) COMMENT '目标URL',
  `platform_type` enum('web','h5','miniprogram','app') NOT NULL COMMENT '平台类型',
  `platform_config` json COMMENT '平台配置',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否默认去向',
  `sort_order` int unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_invitation_code_id` (`invitation_code_id`),
  KEY `idx_platform_type` (`platform_type`),
  CONSTRAINT `fk_destinations_code_id` FOREIGN KEY (`invitation_code_id`) REFERENCES `invitation_codes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请去向表';
```

### 邀请模板表 (invitation_templates)

```sql
CREATE TABLE `invitation_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(100) NOT NULL COMMENT '模板名称',
  `type` enum('poster','link','message') NOT NULL COMMENT '模板类型',
  `category` varchar(50) NOT NULL COMMENT '模板分类',
  `template_data` json NOT NULL COMMENT '模板数据',
  `preview_image` varchar(500) COMMENT '预览图片URL',
  `sort_order` int unsigned NOT NULL DEFAULT 0 COMMENT '排序',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否启用',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_type_category` (`type`, `category`),
  KEY `idx_active_sort` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请模板表';
```

### 邀请使用记录表 (invitation_usage_logs)

```sql
CREATE TABLE `invitation_usage_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `invitation_code_id` bigint unsigned NOT NULL COMMENT '邀请码ID',
  `invitation_code` varchar(50) NOT NULL COMMENT '邀请码',
  `user_type` varchar(50) COMMENT '用户类型',
  `user_id` varchar(100) COMMENT '用户ID',
  `user_name` varchar(100) COMMENT '用户名称',
  `visitor_id` varchar(100) COMMENT '访客ID',
  `session_id` varchar(100) COMMENT '会话ID',
  `action_type` enum('visit','register','order','share') NOT NULL COMMENT '操作类型',
  `platform_type` enum('web','h5','miniprogram','app') NOT NULL COMMENT '平台类型',
  `ip_address` varchar(45) COMMENT 'IP地址',
  `user_agent` text COMMENT '用户代理',
  `referer` varchar(1000) COMMENT '来源页面',
  `extra_data` json COMMENT '额外数据',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_invitation_code_id` (`invitation_code_id`),
  KEY `idx_invitation_code` (`invitation_code`),
  KEY `idx_user` (`user_type`, `user_id`),
  KEY `idx_visitor_id` (`visitor_id`),
  KEY `idx_action_type` (`action_type`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请使用记录表';
```

### 邀请统计表 (invitation_statistics)

```sql
CREATE TABLE `invitation_statistics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `invitation_code_id` bigint unsigned NOT NULL COMMENT '邀请码ID',
  `stat_date` date NOT NULL COMMENT '统计日期',
  `visit_count` int unsigned NOT NULL DEFAULT 0 COMMENT '访问次数',
  `unique_visitor_count` int unsigned NOT NULL DEFAULT 0 COMMENT '独立访客数',
  `register_count` int unsigned NOT NULL DEFAULT 0 COMMENT '注册数',
  `order_count` int unsigned NOT NULL DEFAULT 0 COMMENT '下单数',
  `order_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '订单金额',
  `share_count` int unsigned NOT NULL DEFAULT 0 COMMENT '分享次数',
  `conversion_rate` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT '转化率',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code_date` (`invitation_code_id`, `stat_date`),
  KEY `idx_stat_date` (`stat_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请统计表';
```

## 索引策略

### 主要查询场景索引

1. **邀请码查询**
   - `uk_code` - 按邀请码唯一查询
   - `idx_inviter` - 按邀请人查询
   - `idx_status_expires` - 按状态和过期时间查询

2. **去向配置查询**
   - `idx_invitation_code_id` - 按邀请码ID查询
   - `idx_platform_type` - 按平台类型查询

3. **使用记录查询**
   - `idx_invitation_code_id` - 按邀请码ID查询历史
   - `idx_user` - 按用户查询记录
   - `idx_created_at` - 按时间范围查询

4. **统计数据查询**
   - `uk_code_date` - 按邀请码和日期的唯一约束
   - `idx_stat_date` - 按日期范围查询统计

## 数据分区策略

### 时间分区

对于数据量大的表采用时间分区策略：

```sql
-- 使用记录表按月分区
ALTER TABLE invitation_usage_logs 
PARTITION BY RANGE (YEAR(created_at) * 100 + MONTH(created_at)) (
    PARTITION p202401 VALUES LESS THAN (202402),
    PARTITION p202402 VALUES LESS THAN (202403),
    -- ... 更多分区
    PARTITION pfuture VALUES LESS THAN MAXVALUE
);

-- 统计表按年分区  
ALTER TABLE invitation_statistics
PARTITION BY RANGE (YEAR(stat_date)) (
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    -- ... 更多分区
    PARTITION pfuture VALUES LESS THAN MAXVALUE
);
```

## 数据清理策略

### 历史数据归档

1. **使用记录归档**
   - 保留最近12个月的热数据
   - 超过12个月的数据迁移至归档表

2. **统计数据保留**
   - 日统计数据保留3年
   - 月度汇总数据永久保留

3. **过期邀请码处理**
   - 过期超过1年的邀请码标记为历史状态
   - 相关联的配置数据保留用于分析

## 性能优化建议

### 读写分离

- 统计查询使用只读从库
- 实时数据写入主库
- 报表分析使用专门的分析库

### 缓存策略

- 热点邀请码数据缓存1小时
- 模板数据缓存24小时
- 统计数据缓存30分钟

### 查询优化

- 避免全表扫描，合理使用索引
- 大数据量查询使用分页
- 统计查询优先使用汇总表 