# Red Jasmine Project Package

项目领域扩展包，提供完整的项目管理系统功能。

## 功能特性

- **项目管理**：项目的创建、配置、激活、归档等生命周期管理
- **成员管理**：项目成员的加入、角色分配、权限管理
- **角色管理**：项目角色的创建、权限配置、成员分配
- **多租户隔离**：基于项目的数据隔离和配置隔离
- **多态关联**：支持多种组织类型和成员类型

## 安装

```bash
composer require red-jasmine/project
```

## 配置

发布配置文件：

```bash
php artisan vendor:publish --tag=project-config
```

发布数据库迁移：

```bash
php artisan vendor:publish --tag=project-migrations
php artisan migrate
```

## 使用示例

### 创建项目

```php
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\Enums\ProjectType;
use RedJasmine\Project\Domain\Models\Enums\ProjectStatus;

$project = Project::create([
    'owner_type' => 'App\\Models\\Organization',
    'owner_id' => 1,
    'name' => '我的项目',
    'code' => 'MY_PROJECT',
    'project_type' => ProjectType::STANDARD,
    'status' => ProjectStatus::DRAFT,
]);
```

### 添加项目成员

```php
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Project\Domain\Models\Enums\ProjectMemberStatus;

$member = $project->addMember($user, $role);
```

### 创建项目角色

```php
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Project\Domain\Models\Enums\ProjectRoleStatus;

$role = ProjectRole::create([
    'project_id' => $project->id,
    'name' => '开发者',
    'code' => 'developer',
    'description' => '项目开发者角色',
    'permissions' => ['project.view', 'project.edit'],
    'status' => ProjectRoleStatus::ACTIVE,
]);
```

## 数据库表结构

### projects（项目表）
- `id` - 主键
- `owner_type` - 所有者类型（多态关联）
- `owner_id` - 所有者ID（多态关联）
- `parent_id` - 父项目ID（支持项目分组）
- `name` - 项目名称
- `code` - 项目代码（所有者内唯一）
- `project_type` - 项目类型
- `status` - 项目状态
- `config` - 项目配置（JSON）

### project_members（项目成员表）
- `id` - 主键
- `project_id` - 项目ID
- `member_type` - 成员类型（多态关联）
- `member_id` - 成员ID（多态关联）
- `role_id` - 角色ID
- `status` - 成员状态
- `joined_at` - 加入时间
- `left_at` - 离开时间

### project_roles（项目角色表）
- `id` - 主键
- `project_id` - 项目ID
- `name` - 角色名称
- `code` - 角色代码
- `permissions` - 角色权限（JSON）
- `status` - 角色状态

## 事件

包提供了以下领域事件：

- `ProjectCreated` - 项目创建
- `ProjectActivated` - 项目激活
- `ProjectPaused` - 项目暂停
- `ProjectArchived` - 项目归档
- `MemberJoined` - 成员加入
- `MemberLeft` - 成员离开
- `MemberRoleChanged` - 成员角色变更
- `ProjectRoleCreated` - 角色创建
- `ProjectRoleDeleted` - 角色删除

## 许可证

MIT License
