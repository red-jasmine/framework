# 用户通用组件包 (User Common Package)

## 简介

用户通用组件包是一个基于 Red Jasmine Framework 的用户相关通用功能组件，提供用户资料管理、头像上传、资料完整度检查等通用功能。

## 功能特性

- **用户资料管理**: 提供完整的用户资料管理功能
- **头像上传**: 支持用户头像上传和管理
- **资料完整度**: 自动计算用户资料完整度
- **偏好设置**: 支持用户个性化偏好设置
- **多语言支持**: 内置中文语言包
- **RESTful API**: 提供完整的 RESTful API 接口

## 安装

```bash
composer require red-jasmine/user-common
```

## 配置

发布配置文件：

```bash
php artisan vendor:publish --tag=user-common-config
```

## 使用方法

### API 接口

#### 获取用户资料
```http
GET /api/user-common/profile
```

#### 更新用户资料
```http
PUT /api/user-common/profile
Content-Type: application/json

{
    "nickname": "新昵称",
    "realname": "真实姓名",
    "gender": "male",
    "biography": "个人介绍"
}
```

#### 上传头像
```http
POST /api/user-common/profile/avatar
Content-Type: multipart/form-data

avatar: [文件]
```

#### 获取资料完整度
```http
GET /api/user-common/profile/completion
```

### 服务使用

```php
use RedJasmine\UserCommon\Application\Services\UserCommonApplicationService;

$service = app(UserCommonApplicationService::class);

// 获取用户资料
$profile = $service->getProfile($query);

// 更新用户资料
$profile = $service->updateProfile($command);

// 获取资料完整度
$completion = $service->getProfileCompletion($query);
```

## 配置选项

在 `config/user-common.php` 中可以配置以下选项：

- `features`: 功能开关配置
- `upload`: 文件上传配置
- `cache`: 缓存配置
- `validation`: 验证规则配置

## 数据库迁移

运行数据库迁移：

```bash
php artisan migrate
```

## 语言包

发布语言文件：

```bash
php artisan vendor:publish --tag=user-common-lang
```

## 视图文件

发布视图文件：

```bash
php artisan vendor:publish --tag=user-common-views
```

## 开发

### 运行测试

```bash
composer test
```

### 代码格式化

```bash
composer format
```

### 静态分析

```bash
composer analyse
```

## 许可证

MIT License
