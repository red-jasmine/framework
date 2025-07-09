---
title: 优惠券存储设计
description: 优惠券领域的数据存储结构设计，包括数据库表结构、索引设计和数据分区策略
outline: deep
order: 3
lastUpdated: true
---

# 优惠券存储设计

## 数据库表结构

### 1. 优惠券表 (coupons)

```sql
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL COMMENT '优惠券ID',
  `name` varchar(100) NOT NULL COMMENT '优惠券名称',
  `description` text COMMENT '优惠券描述',
  `image` varchar(255) COMMENT '优惠券图片',
  `status` enum('draft','published','paused','expired') NOT NULL DEFAULT 'draft' COMMENT '状态',
  
  -- 优惠规则
  `discount_type` enum('fixed_amount','percentage','free_shipping') NOT NULL COMMENT '优惠类型',
  `discount_value` decimal(10,2) NOT NULL COMMENT '优惠值',
  `max_discount_amount` decimal(10,2) COMMENT '最大优惠金额',
  `is_ladder` tinyint(1) DEFAULT 0 COMMENT '是否阶梯优惠',
  `ladder_rules` json COMMENT '阶梯规则配置',
  
  -- 门槛规则
  `threshold_type` enum('order_amount','product_amount','shipping_amount','cross_store_amount') NOT NULL COMMENT '门槛类型',
  `threshold_value` decimal(10,2) NOT NULL DEFAULT 0 COMMENT '门槛值',
  `is_threshold_required` tinyint(1) DEFAULT 1 COMMENT '是否需要门槛',
  
  -- 有效期规则
  `validity_type` enum('absolute','relative') NOT NULL COMMENT '有效期类型',
  `start_time` datetime COMMENT '开始时间',
  `end_time` datetime COMMENT '结束时间',
  `relative_days` int COMMENT '相对天数',
  
  -- 使用限制
  `max_usage_per_user` int DEFAULT 1 COMMENT '每用户最大使用次数',
  `max_usage_total` int COMMENT '总使用次数限制',
  
  -- 使用规则
  `usage_rules` json COMMENT '使用规则配置',
  
  -- 领取规则
  `collect_rules` json COMMENT '领取规则配置',
  
  -- 成本承担方
  `cost_bearer_type` enum('platform','merchant','broadcaster') NOT NULL COMMENT '成本承担方类型',
  `cost_bearer_id` varchar(50) NOT NULL COMMENT '成本承担方ID',
  `cost_bearer_name` varchar(100) NOT NULL COMMENT '成本承担方名称',
  
  -- 发放控制
  `issue_strategy` enum('auto','manual','code') NOT NULL DEFAULT 'manual' COMMENT '发放策略',
  `total_issue_limit` int COMMENT '总发放限制',
  `current_issue_count` int DEFAULT 0 COMMENT '当前发放数量',
  
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  
  PRIMARY KEY (`id`),
  KEY `idx_status_time` (`status`, `start_time`, `end_time`),
  KEY `idx_cost_bearer` (`cost_bearer_type`, `cost_bearer_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='优惠券表';
```

### 2. 用户优惠券表 (user_coupons)

```sql
CREATE TABLE `user_coupons` (
  `id` bigint(20) unsigned NOT NULL COMMENT '用户优惠券ID',
  `coupon_id` bigint(20) unsigned NOT NULL COMMENT '优惠券ID',
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `status` enum('available','used','expired') NOT NULL DEFAULT 'available' COMMENT '状态',
  `issue_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发放时间',
  `expire_time` timestamp NOT NULL COMMENT '过期时间',
  `used_time` timestamp NULL COMMENT '使用时间',
  `order_id` bigint(20) unsigned COMMENT '使用订单ID',
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coupon_user` (`coupon_id`, `user_id`),
  KEY `idx_user_status` (`user_id`, `status`),
  KEY `idx_expire_time` (`expire_time`),
  KEY `idx_used_time` (`used_time`),
  KEY `idx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户优惠券表';
```

### 3. 优惠券使用记录表 (coupon_usages)

```sql
CREATE TABLE `coupon_usages` (
  `id` bigint(20) unsigned NOT NULL COMMENT '使用记录ID',
  `coupon_id` bigint(20) unsigned NOT NULL COMMENT '优惠券ID',
  `user_coupon_id` bigint(20) unsigned NOT NULL COMMENT '用户优惠券ID',
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `order_id` bigint(20) unsigned NOT NULL COMMENT '订单ID',
  `threshold_amount` decimal(10,2) NOT NULL COMMENT '门槛金额',
  `original_amount` decimal(10,2) NOT NULL COMMENT '原始金额',
  `discount_amount` decimal(10,2) NOT NULL COMMENT '优惠金额',
  `final_amount` decimal(10,2) NOT NULL COMMENT '最终金额',
  `used_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '使用时间',
  
  -- 成本承担方信息
  `cost_bearer_type` enum('platform','merchant','broadcaster') NOT NULL COMMENT '成本承担方类型',
  `cost_bearer_id` varchar(50) NOT NULL COMMENT '成本承担方ID',
  `cost_bearer_name` varchar(100) NOT NULL COMMENT '成本承担方名称',
  
  PRIMARY KEY (`id`),
  KEY `idx_coupon_id` (`coupon_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_used_at` (`used_at`),
  KEY `idx_cost_bearer` (`cost_bearer_type`, `cost_bearer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='优惠券使用记录表';
```

### 4. 优惠券发放统计表 (coupon_issue_stats)

```sql
CREATE TABLE `coupon_issue_stats` (
  `coupon_id` bigint(20) unsigned NOT NULL COMMENT '优惠券ID',
  `total_issued` int NOT NULL DEFAULT 0 COMMENT '总发放数量',
  `total_used` int NOT NULL DEFAULT 0 COMMENT '总使用数量',
  `total_expired` int NOT NULL DEFAULT 0 COMMENT '总过期数量',
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '总成本',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  
  PRIMARY KEY (`coupon_id`),
  KEY `idx_last_updated` (`last_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='优惠券发放统计表';
```

## 索引设计

### 主要索引说明

1. **优惠券表索引**
   - `idx_status_time`: 支持按状态和时间范围查询可用优惠券
   - `idx_cost_bearer`: 支持按成本承担方查询优惠券
   - `idx_created_at`: 支持按创建时间排序

2. **用户优惠券表索引**
   - `uk_coupon_user`: 确保用户对同一优惠券只能领取一次
   - `idx_user_status`: 支持查询用户的可用优惠券
   - `idx_expire_time`: 支持过期优惠券的清理
   - `idx_used_time`: 支持使用时间统计

3. **使用记录表索引**
   - `idx_coupon_id`: 支持按优惠券查询使用记录
   - `idx_user_id`: 支持按用户查询使用历史
   - `idx_order_id`: 支持按订单查询优惠券使用
   - `idx_used_at`: 支持按时间统计使用情况
   - `idx_cost_bearer`: 支持按成本承担方统计成本

### 复合索引优化

```sql
-- 优惠券查询优化
ALTER TABLE `coupons` ADD INDEX `idx_status_time_bearer` (`status`, `start_time`, `end_time`, `cost_bearer_type`);

-- 使用规则查询优化
ALTER TABLE `coupon_usage_rules` ADD INDEX `idx_coupon_object_rule` (`coupon_id`, `object_type`, `rule_type`);

-- 领取规则查询优化
ALTER TABLE `coupon_collect_rules` ADD INDEX `idx_coupon_object_rule` (`coupon_id`, `object_type`, `rule_type`);

-- 用户优惠券查询优化
ALTER TABLE `user_coupons` ADD INDEX `idx_user_status_expire` (`user_id`, `status`, `expire_time`);

-- 使用记录统计优化
ALTER TABLE `coupon_usages` ADD INDEX `idx_bearer_used_at` (`cost_bearer_type`, `cost_bearer_id`, `used_at`);
```

## 数据分区策略

### 1. 用户优惠券表分区

```sql
-- 按用户ID哈希分区
ALTER TABLE `user_coupons` PARTITION BY HASH(`user_id`) PARTITIONS 16;
```

### 2. 使用记录表分区

```sql
-- 按使用时间范围分区
ALTER TABLE `coupon_usages` PARTITION BY RANGE (YEAR(`used_at`)) (
    PARTITION p2023 VALUES LESS THAN (2024),
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p_future VALUES LESS THAN MAXVALUE
);
```

## 数据迁移

### 1. 创建表结构

```sql
-- 创建所有表结构
SOURCE create_coupon_tables.sql;

-- 创建索引
SOURCE create_coupon_indexes.sql;

-- 设置分区
SOURCE create_coupon_partitions.sql;
```

### 2. 数据初始化

```sql
-- 初始化优惠券状态枚举
INSERT INTO `system_enums` (`type`, `code`, `name`, `sort`) VALUES
('coupon_status', 'draft', '草稿', 1),
('coupon_status', 'published', '已发布', 2),
('coupon_status', 'paused', '已暂停', 3),
('coupon_status', 'expired', '已过期', 4);

-- 初始化优惠类型枚举
INSERT INTO `system_enums` (`type`, `code`, `name`, `sort`) VALUES
('discount_type', 'fixed_amount', '固定金额', 1),
('discount_type', 'percentage', '百分比折扣', 2),
('discount_type', 'free_shipping', '包邮', 3);
```

### 3. JSON规则数据格式

#### 使用规则数据格式 (usage_rules)
```json
{
  "product_include": ["product_001", "product_002"],
  "product_exclude": ["product_003"],
  "category_include": ["category_001"],
  "category_exclude": ["category_002"],
  "user_group_include": ["vip", "new_user"],
  "user_group_exclude": ["blacklist"]
}
```

#### 领取规则数据格式 (collect_rules)
```json
{
  "product_include": ["product_001", "product_002"],
  "product_exclude": ["product_003"],
  "category_include": ["category_001"],
  "category_exclude": ["category_002"],
  "user_group_include": ["vip", "new_user"],
  "user_group_exclude": ["blacklist"]
}
```

#### 规则数据示例
```sql
-- 插入带有规则的优惠券示例
INSERT INTO coupons (
    id, name, description, status, 
    discount_type, discount_value, threshold_type, threshold_value,
    usage_rules, collect_rules,
    cost_bearer_type, cost_bearer_id, cost_bearer_name
) VALUES (
    1, '新人专享券', '新用户专享满100减20', 'published',
    'fixed_amount', 20.00, 'order_amount', 100.00,
    '{"product_include": ["electronics", "books"], "user_group_include": ["new_user"]}',
    '{"user_group_include": ["new_user"], "user_group_exclude": ["blacklist"]}',
    'platform', 'platform_001', '平台'
);
```

## 性能优化策略

### 1. 查询优化

```sql
-- 优化用户可用优惠券查询（避免JOIN，使用子查询）
SELECT c.*, uc.id as user_coupon_id, uc.expire_time
FROM coupons c
INNER JOIN user_coupons uc ON c.id = uc.coupon_id
WHERE uc.user_id = ? 
  AND uc.status = 'available'
  AND uc.expire_time > NOW()
  AND c.status = 'published'
ORDER BY uc.expire_time ASC;

-- 优化优惠券使用统计查询
SELECT 
    cost_bearer_type,
    cost_bearer_id,
    SUM(discount_amount) as total_cost,
    COUNT(*) as usage_count
FROM coupon_usages
WHERE used_at BETWEEN ? AND ?
GROUP BY cost_bearer_type, cost_bearer_id;
```

### 2. 索引优化策略

```sql
-- 优化用户优惠券查询
ALTER TABLE user_coupons 
ADD INDEX idx_user_status_expire_coupon (user_id, status, expire_time, coupon_id);

-- 优化统计查询
ALTER TABLE coupon_usages 
ADD INDEX idx_time_bearer_amount (used_at, cost_bearer_type, cost_bearer_id, discount_amount);
```

### 3. 缓存策略

```sql
-- 优惠券基础信息缓存
-- Key: coupon:info:{coupon_id}
-- TTL: 1小时
-- 包含：基础信息、优惠规则、有效期规则

-- 优惠券规则缓存
-- Key: coupon:rules:{coupon_id}
-- TTL: 2小时
-- 包含：使用规则和领取规则

-- 用户优惠券列表缓存
-- Key: user:coupons:{user_id}
-- TTL: 30分钟
-- 包含：用户可用优惠券列表

-- 优惠券使用统计缓存
-- Key: coupon:stats:{coupon_id}
-- TTL: 5分钟
-- 包含：发放数量、使用数量、成本统计
```

### 4. 读写分离优化

```sql
-- 写操作（主库）
-- 优惠券创建、更新、删除
-- 用户优惠券发放、使用
-- 使用记录创建

-- 读操作（从库）
-- 优惠券列表查询
-- 用户优惠券查询
-- 规则验证查询
-- 统计报表查询
```

### 5. 数据清理与维护

```sql
-- 清理过期的用户优惠券
UPDATE user_coupons 
SET status = 'expired' 
WHERE status = 'available' 
  AND expire_time < NOW();

-- 清理历史使用记录（保留1年）
DELETE FROM coupon_usages 
WHERE used_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);

-- 清理无效的JSON规则数据（定期执行）
UPDATE coupons 
SET usage_rules = NULL 
WHERE usage_rules = '{}' OR usage_rules = '' OR usage_rules IS NULL;

UPDATE coupons 
SET collect_rules = NULL 
WHERE collect_rules = '{}' OR collect_rules = '' OR collect_rules IS NULL;

-- 重建统计数据
INSERT INTO coupon_issue_stats (coupon_id, total_issued, total_used, total_cost)
SELECT 
    c.id,
    COALESCE(issued.cnt, 0) as total_issued,
    COALESCE(used.cnt, 0) as total_used,
    COALESCE(cost.amount, 0) as total_cost
FROM coupons c
LEFT JOIN (
    SELECT coupon_id, COUNT(*) as cnt 
    FROM user_coupons 
    GROUP BY coupon_id
) issued ON c.id = issued.coupon_id
LEFT JOIN (
    SELECT coupon_id, COUNT(*) as cnt 
    FROM user_coupons 
    WHERE status = 'used' 
    GROUP BY coupon_id
) used ON c.id = used.coupon_id
LEFT JOIN (
    SELECT coupon_id, SUM(discount_amount) as amount 
    FROM coupon_usages 
    GROUP BY coupon_id
) cost ON c.id = cost.coupon_id
ON DUPLICATE KEY UPDATE
    total_issued = VALUES(total_issued),
    total_used = VALUES(total_used),
    total_cost = VALUES(total_cost);
```

### 6. 分库分表策略

```sql
-- 按业务维度分库
-- coupon_core: 优惠券核心数据（coupons）
-- coupon_user: 用户相关数据（user_coupons）
-- coupon_log: 日志数据（coupon_usages, coupon_issue_stats）

-- 大表分表策略
-- user_coupons: 按user_id哈希分表
-- coupon_usages: 按used_at时间分表
```

## 数据一致性保证

### 1. 逻辑外键约束
由于不使用物理外键，需要在应用层保证数据一致性：

```sql
-- 优惠券删除时的级联处理（应用层实现）
-- 1. 处理用户优惠券（软删除或状态更新）
UPDATE user_coupons SET status = 'expired' WHERE coupon_id = ? AND status = 'available';

-- 2. 删除统计信息
DELETE FROM coupon_issue_stats WHERE coupon_id = ?;

-- 3. 最后删除优惠券
DELETE FROM coupons WHERE id = ?;
```

### 2. 事务控制

```sql
-- 优惠券使用事务
START TRANSACTION;

-- 检查优惠券状态
SELECT status FROM user_coupons WHERE id = ? FOR UPDATE;

-- 验证优惠券关联存在性
SELECT COUNT(*) FROM coupons WHERE id = ? AND status = 'published';

-- 更新优惠券状态
UPDATE user_coupons SET status = 'used', used_time = NOW(), order_id = ? WHERE id = ?;

-- 创建使用记录
INSERT INTO coupon_usages (...) VALUES (...);

-- 更新统计信息
UPDATE coupon_issue_stats SET total_used = total_used + 1, total_cost = total_cost + ? WHERE coupon_id = ?;

COMMIT;
```

### 3. 数据完整性检查

```sql
-- 定期数据一致性检查脚本

-- 检查无效的规则数据
SELECT COUNT(*) FROM coupons 
WHERE (usage_rules IS NOT NULL AND JSON_VALID(usage_rules) = 0)
   OR (collect_rules IS NOT NULL AND JSON_VALID(collect_rules) = 0);

-- 检查孤立的用户优惠券
SELECT COUNT(*) FROM user_coupons uc 
LEFT JOIN coupons c ON uc.coupon_id = c.id 
WHERE c.id IS NULL;

-- 检查孤立的使用记录
SELECT COUNT(*) FROM coupon_usages cu 
LEFT JOIN coupons c ON cu.coupon_id = c.id 
WHERE c.id IS NULL;

-- 检查孤立的统计记录
SELECT COUNT(*) FROM coupon_issue_stats cis 
LEFT JOIN coupons c ON cis.coupon_id = c.id 
WHERE c.id IS NULL;
```

### 4. 数据约束

```sql
-- 添加检查约束
ALTER TABLE coupons ADD CONSTRAINT chk_discount_value CHECK (discount_value > 0);
ALTER TABLE coupons ADD CONSTRAINT chk_threshold_value CHECK (threshold_value >= 0);
ALTER TABLE user_coupons ADD CONSTRAINT chk_expire_time CHECK (expire_time > issue_time);
ALTER TABLE coupon_usages ADD CONSTRAINT chk_amounts CHECK (discount_amount <= original_amount);

-- 添加唯一约束
ALTER TABLE coupon_usage_rules ADD CONSTRAINT uk_coupon_object_value UNIQUE (coupon_id, object_type, object_value, rule_type);
ALTER TABLE coupon_collect_rules ADD CONSTRAINT uk_coupon_object_value UNIQUE (coupon_id, object_type, object_value, rule_type);
```

### 5. 应用层数据验证

```php
// 应用层数据一致性验证示例
class CouponDataValidator 
{
    public function validateCouponExists($couponId): bool 
    {
        return DB::table('coupons')->where('id', $couponId)->exists();
    }
    
    public function validateUserCouponConsistency($userCouponId): bool 
    {
        $userCoupon = DB::table('user_coupons')->find($userCouponId);
        if (!$userCoupon) return false;
        
        return DB::table('coupons')
            ->where('id', $userCoupon->coupon_id)
            ->exists();
    }
    
    public function cleanupInvalidRules(): void 
    {
        // 清理无效的JSON规则数据
        DB::table('coupons')
            ->where(function($query) {
                $query->whereRaw('usage_rules IS NOT NULL AND JSON_VALID(usage_rules) = 0')
                      ->orWhereRaw('collect_rules IS NOT NULL AND JSON_VALID(collect_rules) = 0');
            })
            ->update([
                'usage_rules' => null,
                'collect_rules' => null
            ]);
    }
}
```

## 监控指标与运维

### 1. 性能监控

- **查询响应时间**
  - 优惠券查询平均响应时间 < 100ms
  - 用户优惠券列表查询响应时间 < 200ms
  - 优惠券使用事务响应时间 < 500ms
  - 规则验证查询响应时间 < 50ms

- **数据库性能**
  - 数据库连接池使用率 < 80%
  - 慢查询数量监控
  - 索引使用率监控
  - 表锁等待时间监控

### 2. 业务监控

- **优惠券发放统计**
  - 每日优惠券发放数量
  - 优惠券领取转化率
  - 不同类型优惠券发放占比

- **优惠券使用统计**
  - 优惠券使用率
  - 平均优惠金额
  - 成本承担方成本统计
  - 过期优惠券清理数量

- **规则配置监控**
  - 规则数量统计
  - 规则匹配成功率
  - 规则配置变更频率

### 3. 异常监控

- **数据一致性监控**
  - 孤立数据检查（每日）
  - 数据完整性校验
  - 统计数据准确性验证

- **业务异常监控**
  - 优惠券重复使用检测
  - 成本计算异常
  - 规则验证失败率
  - 并发冲突检测

### 4. 运维自动化

```sql
-- 自动化数据清理脚本
-- 每日执行：清理过期优惠券
CREATE EVENT IF NOT EXISTS cleanup_expired_coupons
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
UPDATE user_coupons 
SET status = 'expired' 
WHERE status = 'available' 
  AND expire_time < NOW();

-- 每周执行：清理无效规则数据
CREATE EVENT IF NOT EXISTS cleanup_invalid_rules
ON SCHEDULE EVERY 1 WEEK
STARTS CURRENT_TIMESTAMP
DO
BEGIN
  UPDATE coupons 
  SET usage_rules = NULL 
  WHERE usage_rules IS NOT NULL AND JSON_VALID(usage_rules) = 0;
  
  UPDATE coupons 
  SET collect_rules = NULL 
  WHERE collect_rules IS NOT NULL AND JSON_VALID(collect_rules) = 0;
END;

-- 每月执行：清理历史数据
CREATE EVENT IF NOT EXISTS cleanup_historical_data
ON SCHEDULE EVERY 1 MONTH
STARTS CURRENT_TIMESTAMP
DO
DELETE FROM coupon_usages 
WHERE used_at < DATE_SUB(NOW(), INTERVAL 12 MONTH);
```

### 5. 备份与恢复策略

```sql
-- 核心数据备份（每日）
-- 优惠券基础数据
mysqldump --single-transaction coupon_db coupons coupon_usage_rules coupon_collect_rules > coupons_core_backup.sql

-- 用户数据备份（每日）
mysqldump --single-transaction coupon_db user_coupons > user_coupons_backup.sql

-- 日志数据备份（每周）
mysqldump --single-transaction coupon_db coupon_usages coupon_issue_stats > coupon_logs_backup.sql

-- 增量备份策略
mysqlbinlog --start-datetime="2024-01-01 00:00:00" --stop-datetime="2024-01-02 00:00:00" mysql-bin.000001 > incremental_backup.sql
```

### 6. 容灾方案

```sql
-- 主从复制配置
-- 主库：负责写操作
-- 从库：负责读操作和备份

-- 读写分离配置
-- 应用层配置读写分离
-- 写操作路由到主库
-- 读操作路由到从库

-- 故障切换策略
-- 主库故障时自动切换到从库
-- 数据同步延迟监控
-- 故障恢复后的数据一致性校验
```

### 7. 性能调优建议

```sql
-- MySQL配置优化
-- innodb_buffer_pool_size = 70% of RAM
-- innodb_log_file_size = 256MB
-- innodb_flush_log_at_trx_commit = 2
-- query_cache_size = 128MB
-- max_connections = 1000

-- 表结构优化建议
-- 使用适当的数据类型
-- 避免NULL值
-- 合理设置字符集
-- 定期分析表统计信息

ANALYZE TABLE coupons;
ANALYZE TABLE user_coupons;
ANALYZE TABLE coupon_usage_rules;
ANALYZE TABLE coupon_collect_rules;
ANALYZE TABLE coupon_usages;
``` 