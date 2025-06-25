# 邀请模块路由说明

## 概述

邀请模块的路由按照DDD架构和角色分离的原则进行组织，分为三个主要角色：用户(User)、商家(Shop)、管理员(Admin)。

## 路由结构

```
src/UI/Http/
├── InvitationRoute.php              # 统一路由注册类
├── Admin/
│   └── InvitationAdminRoute.php     # 管理员路由定义
├── User/
│   └── InvitationUserRoute.php      # 用户路由定义
└── Shop/
    └── InvitationShopRoute.php      # 商家路由定义
```

## 路由定义

### 1. 用户端路由 (InvitationUserRoute)

**基础路径**: `/user/invitation`

| 方法 | 路径 | 控制器方法 | 说明 |
|------|------|------------|------|
| GET | `/codes` | index | 获取用户邀请码列表 |
| POST | `/codes` | store | 创建邀请码 |
| GET | `/codes/{id}` | show | 获取邀请码详情 |
| PUT | `/codes/{id}` | update | 更新邀请码 |
| DELETE | `/codes/{id}` | destroy | 删除邀请码 |
| POST | `/codes/use` | use | 使用邀请码 |
| POST | `/codes/generate-url` | generateUrl | 生成邀请链接 |
| GET | `/statistics` | statistics | 获取用户邀请统计 |

### 2. 商家端路由 (InvitationShopRoute)

**基础路径**: `/shop/invitation`

| 方法 | 路径 | 控制器方法 | 说明 |
|------|------|------------|------|
| GET | `/codes` | index | 获取商家邀请码列表 |
| POST | `/codes` | store | 创建邀请码 |
| GET | `/codes/{id}` | show | 获取邀请码详情 |
| PUT | `/codes/{id}` | update | 更新邀请码 |
| DELETE | `/codes/{id}` | destroy | 删除邀请码 |
| POST | `/codes/generate` | generate | 生成邀请码 |
| GET | `/statistics` | statistics | 获取商家邀请统计 |
| GET | `/usage-records` | usageRecords | 获取使用记录 |

### 3. 管理员端路由 (InvitationAdminRoute)

**基础路径**: `/admin/invitation`

| 方法 | 路径 | 控制器方法 | 说明 |
|------|------|------------|------|
| GET | `/codes` | index | 获取所有邀请码列表 |
| POST | `/codes` | store | 创建邀请码 |
| GET | `/codes/{id}` | show | 获取邀请码详情 |
| PUT | `/codes/{id}` | update | 更新邀请码 |
| DELETE | `/codes/{id}` | destroy | 删除邀请码 |
| GET | `/statistics` | statistics | 获取邀请码统计 |
| GET | `/analytics` | analytics | 获取邀请码分析 |
| POST | `/codes/batch-delete` | batchDelete | 批量删除邀请码 |
| POST | `/codes/batch-update` | batchUpdate | 批量更新邀请码 |

## 使用方法

### 1. 在服务提供者中注册路由

```php
// 在 InvitationPackageServiceProvider.php 中
public function boot(): void
{
    // 注册API路由
    $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    
    // 注册Web路由（如果需要）
    $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
}
```

### 2. 直接使用统一路由注册类

```php
use RedJasmine\Invitation\UI\Http\InvitationRoute;

// 注册所有API路由
InvitationRoute::registerApiRoutes();

// 注册所有Web路由
InvitationRoute::registerWebRoutes();

// 注册所有路由
InvitationRoute::registerAllRoutes();
```

### 3. 单独注册特定角色的路由

```php
use RedJasmine\Invitation\UI\Http\User\InvitationUserRoute;
use RedJasmine\Invitation\UI\Http\Shop\InvitationShopRoute;
use RedJasmine\Invitation\UI\Http\Admin\InvitationAdminRoute;

// 注册用户路由
Route::prefix('user')->middleware(['auth:user'])->group(function () {
    InvitationUserRoute::api();
});

// 注册商家路由
Route::prefix('shop')->middleware(['auth:shop'])->group(function () {
    InvitationShopRoute::api();
});

// 注册管理员路由
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    InvitationAdminRoute::api();
});
```

## 中间件

所有路由都使用了相应的认证中间件：

- 用户路由: `auth:user`
- 商家路由: `auth:shop`
- 管理员路由: `auth:admin`

## 扩展说明

如果需要添加新的路由，可以：

1. 在对应的控制器中添加新方法
2. 在对应的路由定义类中添加路由
3. 如果需要跨角色使用，可以在统一路由注册类中添加

## 注意事项

1. 所有路由都遵循RESTful设计原则
2. 路由按角色分离，确保权限控制
3. 使用统一的路由注册类便于维护
4. 控制器方法需要实现相应的权限验证逻辑 