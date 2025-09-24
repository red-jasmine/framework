# Red Jasmine Announcement Package

公告管理模块，提供完整的公告发布、分类管理、审批流程等功能。

## 功能特性

- **公告管理**: 支持创建、编辑、删除、发布、撤销公告
- **分类管理**: 支持多级分类，树形结构管理
- **审批流程**: 完整的审批流程，支持提交、审批、拒绝
- **发布控制**: 支持定时发布、强制阅读等功能
- **多业务线**: 支持多业务线隔离
- **权限控制**: 基于所有者的权限控制

## 安装

```bash
composer require red-jasmine/announcement
```

## 配置

发布配置文件：

```bash
php artisan vendor:publish --provider="RedJasmine\Announcement\AnnouncementServiceProvider"
```

## 数据库迁移

```bash
php artisan migrate
```

## 使用方法

### 基本使用

```php
use RedJasmine\Announcement\Application\Services\AnnouncementApplicationService;

// 创建公告
$announcement = $announcementService->create($data);

// 发布公告
$announcement = $announcementService->publish($id);

// 撤销公告
$announcement = $announcementService->revoke($id);
```

### API 接口

#### 公告管理

- `GET /api/announcement/announcements` - 获取公告列表
- `POST /api/announcement/announcements` - 创建公告
- `GET /api/announcement/announcements/{id}` - 获取公告详情
- `PUT /api/announcement/announcements/{id}` - 更新公告
- `DELETE /api/announcement/announcements/{id}` - 删除公告
- `PATCH /api/announcement/announcements/{id}/publish` - 发布公告
- `PATCH /api/announcement/announcements/{id}/revoke` - 撤销公告
- `PATCH /api/announcement/announcements/{id}/submit-approval` - 提交审批
- `PATCH /api/announcement/announcements/{id}/approve` - 审批通过
- `PATCH /api/announcement/announcements/{id}/reject` - 审批拒绝

#### 分类管理

- `GET /api/announcement/categories` - 获取分类列表
- `POST /api/announcement/categories` - 创建分类
- `GET /api/announcement/categories/{id}` - 获取分类详情
- `PUT /api/announcement/categories/{id}` - 更新分类
- `DELETE /api/announcement/categories/{id}` - 删除分类
- `PATCH /api/announcement/categories/{id}/show` - 显示分类
- `PATCH /api/announcement/categories/{id}/hide` - 隐藏分类
- `PATCH /api/announcement/categories/{id}/move` - 移动分类
- `GET /api/announcement/categories/tree` - 获取分类树

## 领域模型

### Announcement (公告)

公告聚合根，包含以下核心功能：

- 基本信息管理（标题、内容、封面等）
- 状态管理（草稿、已发布、已撤销）
- 审批流程管理
- 发布控制（定时发布、强制阅读）
- 人群范围和发布渠道管理

### Category (分类)

分类聚合根，支持多级分类管理：

- 树形结构管理
- 显示/隐藏控制
- 排序管理
- 关联公告管理

## 枚举类型

- `AnnouncementStatus`: 公告状态（draft, published, revoked）
- `ApprovalStatus`: 审批状态（pending, approved, rejected）
- `ContentType`: 内容类型（text, rich, markdown）

## 值对象

- `Title`: 标题值对象，包含验证逻辑

## 仓库接口

- `AnnouncementRepositoryInterface`: 公告仓库
- `CategoryRepositoryInterface`: 分类仓库


## 应用服务

- `AnnouncementApplicationService`: 公告应用服务
- `CategoryApplicationService`: 分类应用服务

## 转换器

- `AnnouncementTransformer`: 公告数据转换器
- `CategoryTransformer`: 分类数据转换器

## 事件

- `AnnouncementPublished`: 公告发布事件
- `AnnouncementRevoked`: 公告撤销事件
- `AnnouncementApproved`: 公告审批通过事件
- `AnnouncementRejected`: 公告审批拒绝事件

## 许可证

MIT License
