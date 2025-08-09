# 公告领域存储层设计

## 1. 概述

公告领域的存储层设计遵循数据库设计规范，主要包括公告表和公告分类表。设计考虑了公告的扩展性需求，使用JSON字段存储复杂结构数据。

## 2. 核心数据表

### 2.1 公告表 (announcement_announcements)

#### 建表语句

公告表用于存储系统中的公告信息，包括公告内容、状态、范围等。

```sql
CREATE TABLE `announcement_announcements` (
    `id` bigint unsigned NOT NULL COMMENT '公告ID',
    `biz` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务线',
    `owner_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所有者类型',
    `owner_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所有者ID',
    `category_id` bigint unsigned DEFAULT NULL COMMENT '公告分类ID',
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公告标题',
    `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '公告封面',
    `content` json NOT NULL COMMENT '公告内容',
    `scopes` json NOT NULL COMMENT '人群范围',
    `channels` json NOT NULL COMMENT '发布渠道',
    `publish_time` timestamp NULL DEFAULT NULL COMMENT '发布时间',
    `status` enum('draft','published','revoked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT '公告状态: draft(草稿),published(已发布),revoked(已撤销)',
    `attachments` json NOT NULL COMMENT '附件信息',
    `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT '审批状态: pending(待审批),approved(已通过),rejected(已拒绝)',
    `is_force_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否强制阅读',
    `version` bigint unsigned NOT NULL DEFAULT '0' COMMENT '版本号',
    `creator_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '创建者类型',
    `creator_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '创建者ID',
    `updater_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '更新者类型',
    `updater_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '更新者ID',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_biz_owner` (`biz`, `owner_type`, `owner_id`),
    KEY `idx_category` (`category_id`),
    KEY `idx_status` (`status`),
    KEY `idx_approval_status` (`approval_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='公告表';
```

#### 核心字段说明

| 字段名 | 类型 | 说明 |
|-------|------|------|
| id | bigint unsigned | 主键，公告ID |
| biz | varchar(32) | 业务线标识，用于区分不同业务线的公告 |
| owner_type | varchar(32) | 所有者类型，如platform(平台)、merchant(商家) |
| owner_id | varchar(32) | 所有者ID |
| category_id | bigint unsigned | 公告分类ID |
| title | varchar(255) | 公告标题 |
| cover | varchar(255) | 公告封面图片URL |
| content | json | 公告内容，支持多种内容类型 |
| scopes | json | 人群范围定义 |
| channels | json | 发布渠道列表 |
| publish_time | timestamp | 发布时间，支持定时发布 |
| status | enum | 公告状态：draft(草稿)、published(已发布)、revoked(已撤销) |
| attachments | json | 附件信息列表 |
| approval_status | enum | 审批状态：pending(待审批)、approved(已通过)、rejected(已拒绝) |
| is_force_read | tinyint(1) | 是否强制阅读 |

### 2.2 公告分类表 (announcement_categories)

#### 建表语句

公告分类表用于管理公告的分类信息。

```sql
CREATE TABLE `announcement_categories` (
    `id` bigint unsigned NOT NULL COMMENT '分类ID',
    `biz` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '业务线',
    `owner_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所有者类型',
    `owner_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '所有者ID',
    `parent_id` bigint unsigned DEFAULT NULL COMMENT '父级分类ID',
    `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
    `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分类描述',
    `sort` int NOT NULL DEFAULT '0' COMMENT '排序',
    `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
    `version` bigint unsigned NOT NULL DEFAULT '0' COMMENT '版本号',
    `creator_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '创建者类型',
    `creator_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '创建者ID',
    `updater_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '更新者类型',
    `updater_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '更新者ID',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_biz_owner` (`biz`, `owner_type`, `owner_id`),
    KEY `idx_parent` (`parent_id`),
    KEY `idx_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='公告分类表';
```

#### 核心字段说明

| 字段名 | 类型 | 说明 |
|-------|------|------|
| id | bigint unsigned | 主键，分类ID |
| biz | varchar(32) | 业务线标识 |
| owner_type | varchar(32) | 所有者类型 |
| owner_id | varchar(32) | 所有者ID |
| parent_id | bigint unsigned | 父级分类ID，支持分类层级 |
| name | varchar(100) | 分类名称 |
| description | varchar(255) | 分类描述 |
| sort | int | 排序值 |
| is_show | tinyint(1) | 是否显示 |

## 3. 数据关系图

<!--@include: ./database-relation.puml-->

## 4. 索引设计

### 4.1 公告表索引
1. 主键索引：`id`
2. 业务索引：`idx_biz_owner` (`biz`, `owner_type`, `owner_id`) - 提高按业务线和所有者查询效率
3. 分类索引：`idx_category` (`category_id`) - 提高按分类查询效率
4. 状态索引：`idx_status` (`status`) - 提高按状态查询效率
5. 审批索引：`idx_approval_status` (`approval_status`) - 提高按审批状态查询效率

### 4.2 分类表索引
1. 主键索引：`id`
2. 业务索引：`idx_biz_owner` (`biz`, `owner_type`, `owner_id`) - 提高按业务线和所有者查询效率
3. 父级索引：`idx_parent` (`parent_id`) - 提高分类树查询效率
4. 显示索引：`idx_show` (`is_show`) - 提高显示分类查询效率

## 5. 数据迁移策略

### 5.1 版本管理
- 使用Laravel Migration进行数据库版本管理
- 每个功能变更对应一个Migration文件
- 遵循向后兼容原则

### 5.2 数据备份
- 重要数据变更前进行数据备份
- 提供回滚脚本

### 5.3 回滚策略
- 每个Migration都需要提供down方法
- 确保数据可以安全回滚