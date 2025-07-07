---
title: 优惠券领域存储设计
description: 优惠券领域数据库表结构设计和索引优化方案
outline: deep
order: 2
lastUpdated: true
tags: [优惠券, 存储设计, 数据库]
author: Red Jasmine Team
---

# 优惠券领域存储设计

## 概述

优惠券领域的存储设计采用关系型数据库作为主要存储方案，结合Redis缓存提升性能。数据库设计遵循第三范式，同时考虑查询性能优化，为优惠券的创建、发放、使用、统计等核心功能提供高效的数据存储支持。

## 数据库表结构

### 1. 优惠券主表 (coupons)

```sql
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL COMMENT '优惠券ID',
  `name` varchar(100) NOT NULL COMMENT '优惠券名称',
  `description` text COMMENT '优惠券描述',
  `coupon_type` enum('DISCOUNT','FULL_REDUCTION','FREE_SHIPPING') NOT NULL COMMENT '优惠券类型：折扣券/满减券/包邮券',
  `cost_bearer` enum('PLATFORM','MERCHANT','ANCHOR') NOT NULL COMMENT '成本承担方',
  `status` enum('DRAFT','PUBLISHED','DISABLED','DELETED') NOT NULL DEFAULT 'DRAFT' COMMENT '状态',
  `owner_type` varchar(50) NOT NULL COMMENT '所有者类型',
  `owner_id` bigint unsigned NOT NULL COMMENT '所有者ID',
  `operator_type` varchar(50) DEFAULT NULL COMMENT '操作者类型',
  `operator_id` bigint unsigned DEFAULT NULL COMMENT '操作者ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_owner` (`owner_type`,`owner_id`),
  KEY `idx_status` (`status`),
  KEY `idx_coupon_type` (`coupon_type`),
  KEY `idx_cost_bearer` (`cost_bearer`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠券主表';
```

### 2. 优惠券配置表 (coupon_configs)

```sql
CREATE TABLE `coupon_configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `coupon_id` bigint unsigned NOT NULL COMMENT '优惠券ID',
  `threshold_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用门槛金额',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `discount_rate` decimal(5,4) DEFAULT NULL COMMENT '折扣比例',
  `validity_type` enum('ABSOLUTE','RELATIVE') NOT NULL COMMENT '有效期类型',
  `start_time` timestamp NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `relative_days` int DEFAULT NULL COMMENT '相对天数',
  `max_discount_amount` decimal(10,2) DEFAULT NULL COMMENT '最大优惠金额',
  `min_order_amount` decimal(10,2) DEFAULT NULL COMMENT '最小订单金额',
  `max_order_amount` decimal(10,2) DEFAULT NULL COMMENT '最大订单金额',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coupon_id` (`coupon_id`),
  KEY `idx_start_time` (`start_time`),
  KEY `idx_end_time` (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠券配置表';
```

### 3. 发放策略表 (issue_strategies)

```sql
CREATE TABLE `issue_strategies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '策略ID',
  `coupon_id` bigint unsigned NOT NULL COMMENT '优惠券ID',
  `issue_method` enum('AUTO','MANUAL','ACTIVITY') NOT NULL COMMENT '发放方式',
  `total_quantity` int NOT NULL DEFAULT '0' COMMENT '总发放数量',
  `issued_quantity` int NOT NULL DEFAULT '0' COMMENT '已发放数量',
  `daily_limit` int DEFAULT NULL COMMENT '每日限量',
  `personal_limit` int NOT NULL DEFAULT '1' COMMENT '个人限领数量',
  `start_time` timestamp NULL DEFAULT NULL COMMENT '发放开始时间',
  `end_time` timestamp NULL DEFAULT NULL COMMENT '发放结束时间',
  `auto_issue_condition` json DEFAULT NULL COMMENT '自动发放条件',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coupon_id` (`coupon_id`),
  KEY `idx_start_time` (`start_time`),
  KEY `idx_end_time` (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='发放策略表';
```

### 4. 使用限制表 (usage_restrictions)

```sql
CREATE TABLE `usage_restrictions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '限制ID',
  `coupon_id` bigint unsigned NOT NULL COMMENT '优惠券ID',
  `user_restriction` enum('ALL','NEW_USER','MEMBER','SPECIFIC_USER') NOT NULL DEFAULT 'ALL' COMMENT '用户限制',
  `product_restriction` enum('ALL','SPECIFIC_PRODUCT','SPECIFIC_CATEGORY','EXCLUDE_PRODUCT','EXCLUDE_CATEGORY') NOT NULL DEFAULT 'ALL' COMMENT '商品限制',
  `overlay_rule` enum('ALLOW','DISALLOW','LIMIT') NOT NULL DEFAULT 'DISALLOW' COMMENT '叠加规则',
  `user_level_min` int DEFAULT NULL COMMENT '用户等级最小值',
  `user_level_max` int DEFAULT NULL COMMENT '用户等级最大值',
  `register_days_min` int DEFAULT NULL COMMENT '注册天数最小值',
  `register_days_max` int DEFAULT NULL COMMENT '注册天数最大值',
  `consumption_amount_min` decimal(10,2) DEFAULT NULL COMMENT '消费金额最小值',
  `consumption_amount_max` decimal(10,2) DEFAULT NULL COMMENT '消费金额最大值',
  `specific_user_ids` json DEFAULT NULL COMMENT '指定用户ID列表',
  `specific_product_ids` json DEFAULT NULL COMMENT '指定商品ID列表',
  `specific_category_ids` json DEFAULT NULL COMMENT '指定类目ID列表',
  `exclude_product_ids` json DEFAULT NULL COMMENT '排除商品ID列表',
  `exclude_category_ids` json DEFAULT NULL COMMENT '排除类目ID列表',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coupon_id` (`coupon_id`),
  KEY `idx_user_restriction` (`user_restriction`),
  KEY `idx_product_restriction` (`product_restriction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='使用限制表';
```

### 5. 用户优惠券表 (user_coupons)

```sql
CREATE TABLE `user_coupons` (
  `id` bigint unsigned NOT NULL COMMENT '用户优惠券ID',
  `user_id` bigint unsigned NOT NULL COMMENT '用户ID',
  `coupon_id` bigint unsigned NOT NULL COMMENT '优惠券ID',
  `coupon_code` varchar(50) NOT NULL COMMENT '优惠券码',
  `status` enum('UNUSED','USED','EXPIRED','REFUNDED') NOT NULL DEFAULT 'UNUSED' COMMENT '状态',
  `issue_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发放时间',
  `use_time` timestamp NULL DEFAULT NULL COMMENT '使用时间',
  `expire_time` timestamp NOT NULL COMMENT '过期时间',
  `order_id` bigint unsigned DEFAULT NULL COMMENT '使用订单ID',
  `use_amount` decimal(10,2) DEFAULT NULL COMMENT '使用金额',
  `discount_amount` decimal(10,2) DEFAULT NULL COMMENT '优惠金额',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coupon_code` (`coupon_code`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_coupon_id` (`coupon_id`),
  KEY `idx_status` (`status`),
  KEY `idx_expire_time` (`expire_time`),
  KEY `idx_issue_time` (`issue_time`),
  KEY `idx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户优惠券表';
```

### 6. 优惠券使用记录表 (coupon_usage_records)

```sql
CREATE TABLE `coupon_usage_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `user_coupon_id` bigint unsigned NOT NULL COMMENT '用户优惠券ID',
  `order_id` bigint unsigned NOT NULL COMMENT '订单ID',
  `use_amount` decimal(10,2) NOT NULL COMMENT '使用金额',
  `discount_amount` decimal(10,2) NOT NULL COMMENT '优惠金额',
  `use_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '使用时间',
  `verify_time` timestamp NULL DEFAULT NULL COMMENT '核销时间',
  `refund_time` timestamp NULL DEFAULT NULL COMMENT '退款时间',
  `refund_amount` decimal(10,2) DEFAULT NULL COMMENT '退款金额',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_coupon_id` (`user_coupon_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_use_time` (`use_time`),
  KEY `idx_verify_time` (`verify_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠券使用记录表';
```

### 7. 优惠券统计表 (coupon_statistics)

```sql
CREATE TABLE `coupon_statistics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '统计ID',
  `coupon_id` bigint unsigned NOT NULL COMMENT '优惠券ID',
  `issued_quantity` int NOT NULL DEFAULT '0' COMMENT '发放数量',
  `used_quantity` int NOT NULL DEFAULT '0' COMMENT '使用数量',
  `usage_rate` decimal(5,4) NOT NULL DEFAULT '0.0000' COMMENT '使用率',
  `conversion_rate` decimal(5,4) NOT NULL DEFAULT '0.0000' COMMENT '转化率',
  `total_discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '总优惠金额',
  `total_cost_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '总成本金额',
  `roi` decimal(8,4) NOT NULL DEFAULT '0.0000' COMMENT '投资回报率',
  `daily_stats` json DEFAULT NULL COMMENT '每日统计数据',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coupon_id` (`coupon_id`),
  KEY `idx_usage_rate` (`usage_rate`),
  KEY `idx_conversion_rate` (`conversion_rate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠券统计表';
```

### 8. 优惠券发放日志表 (coupon_issue_logs)

```sql
CREATE TABLE `coupon_issue_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `coupon_id` bigint unsigned NOT NULL COMMENT '优惠券ID',
  `user_id` bigint unsigned NOT NULL COMMENT '用户ID',
  `issue_method` enum('AUTO','MANUAL','ACTIVITY') NOT NULL COMMENT '发放方式',
  `issue_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发放时间',
  `operator_type` varchar(50) DEFAULT NULL COMMENT '操作者类型',
  `operator_id` bigint unsigned DEFAULT NULL COMMENT '操作者ID',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP地址',
  `user_agent` text DEFAULT NULL COMMENT '用户代理',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_coupon_id` (`coupon_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_issue_time` (`issue_time`),
  KEY `idx_issue_method` (`issue_method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠券发放日志表';
```

## 索引设计

### 1. 主键索引
- 所有表都使用自增ID或雪花ID作为主键
- 优惠券主表使用雪花ID，其他表使用自增ID

### 2. 唯一索引
- `coupon_configs.coupon_id`：确保每个优惠券只有一个配置
- `issue_strategies.coupon_id`：确保每个优惠券只有一个发放策略
- `usage_restrictions.coupon_id`：确保每个优惠券只有一个使用限制
- `user_coupons.coupon_code`：确保优惠券码唯一性
- `coupon_statistics.coupon_id`：确保每个优惠券只有一个统计记录

### 3. 普通索引
- **优惠券主表**：
  - `idx_owner`：按所有者查询
  - `idx_status`：按状态查询
  - `idx_coupon_type`：按类型查询
  - `idx_cost_bearer`：按成本承担方查询
  - `idx_created_at`：按创建时间查询

- **用户优惠券表**：
  - `idx_user_id`：按用户查询
  - `idx_coupon_id`：按优惠券查询
  - `idx_status`：按状态查询
  - `idx_expire_time`：按过期时间查询
  - `idx_issue_time`：按发放时间查询
  - `idx_order_id`：按订单查询

- **使用记录表**：
  - `idx_user_coupon_id`：按用户优惠券查询
  - `idx_order_id`：按订单查询
  - `idx_use_time`：按使用时间查询
  - `idx_verify_time`：按核销时间查询

### 4. 复合索引
- `idx_owner_status`：按所有者和状态组合查询
- `idx_user_status_expire`：按用户、状态、过期时间组合查询
- `idx_coupon_status_time`：按优惠券、状态、时间组合查询

## 分表策略

### 1. 用户优惠券表分表
由于用户优惠券表数据量较大，采用按用户ID分表：

```sql
-- 分表规则：user_id % 100
-- 表名：user_coupons_00 到 user_coupons_99
```

### 2. 使用记录表分表
优惠券使用记录表按时间分表：

```sql
-- 分表规则：按月份分表
-- 表名：coupon_usage_records_202401, coupon_usage_records_202402, ...
```

### 3. 发放日志表分表
发放日志表按时间分表：

```sql
-- 分表规则：按月份分表
-- 表名：coupon_issue_logs_202401, coupon_issue_logs_202402, ...
```

## 缓存策略

### 1. Redis缓存设计

#### 优惠券基本信息缓存
```
Key: coupon:info:{coupon_id}
TTL: 1小时
Value: JSON格式的优惠券信息
```

#### 用户优惠券列表缓存
```
Key: user:coupons:{user_id}:{status}
TTL: 30分钟
Value: 用户优惠券列表
```

#### 发放数量计数器缓存
```
Key: coupon:issued_count:{coupon_id}
TTL: 永久
Value: 已发放数量
```

#### 每日发放计数器缓存
```
Key: coupon:daily_issued:{coupon_id}:{date}
TTL: 24小时
Value: 当日发放数量
```

### 2. 缓存更新策略

#### 写入时更新
- 优惠券创建/更新时，清除相关缓存
- 用户领取优惠券时，更新用户优惠券缓存
- 优惠券使用时，更新使用统计缓存

#### 定时更新
- 每日凌晨更新过期优惠券状态
- 每小时更新优惠券统计数据
- 每5分钟更新热门优惠券缓存

## 数据备份策略

### 1. 全量备份
- 每日凌晨进行全量备份
- 保留最近30天的备份文件
- 使用压缩存储节省空间

### 2. 增量备份
- 每小时进行增量备份
- 记录binlog用于数据恢复
- 保留最近7天的增量备份

### 3. 备份验证
- 定期进行备份恢复测试
- 验证备份数据的完整性
- 记录备份和恢复时间

## 性能优化建议

### 1. 查询优化
- 使用覆盖索引减少回表查询
- 避免使用SELECT *，只查询需要的字段
- 合理使用LIMIT限制查询结果数量

### 2. 写入优化
- 批量插入减少数据库连接次数
- 使用INSERT IGNORE避免重复插入
- 合理设置自增ID步长

### 3. 索引优化
- 定期分析慢查询日志
- 根据查询模式调整索引
- 删除不必要的索引

### 4. 连接池优化
- 合理设置连接池大小
- 监控连接池使用情况
- 及时释放空闲连接

这个存储设计方案为优惠券领域提供了高效、可扩展的数据存储支持，能够满足大规模电商平台的优惠券业务需求。 