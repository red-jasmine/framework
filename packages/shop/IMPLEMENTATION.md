# Shop 领域实现说明

## 概述

Shop 电商店铺领域已经完成基础代码实现，参照 Admin 领域的代码方式，基于 User 领域的大部分代码进行扩展。

## 已完成的代码结构

### 1. 领域层 (Domain)

#### 模型 (Models)
- `Shop.php` - 店铺主模型，继承 User 模型
- `ShopGroup.php` - 店铺分组模型
- `ShopTag.php` - 店铺标签模型
- `Enums/ShopStatusEnum.php` - 店铺状态枚举

#### 事件 (Events)
- `ShopLoginEvent.php` - 店铺登录事件
- `ShopRegisteredEvent.php` - 店铺注册事件
- `ShopCancelEvent.php` - 店铺注销事件

#### 仓库接口 (Repositories)
- `ShopRepositoryInterface.php` - 店铺仓库接口
- `ShopReadRepositoryInterface.php` - 店铺只读仓库接口
- `ShopGroupRepositoryInterface.php` - 店铺分组仓库接口
- `ShopGroupReadRepositoryInterface.php` - 店铺分组只读仓库接口
- `ShopTagRepositoryInterface.php` - 店铺标签仓库接口
- `ShopTagReadRepositoryInterface.php` - 店铺标签只读仓库接口

#### 转换器 (Transformers)
- `ShopTransformer.php` - 店铺数据转换器

#### 数据类 (Data)
- `ShopData.php` - 店铺数据传输对象

### 2. 应用层 (Application)

#### 应用服务 (Services)
- `ShopApplicationService.php` - 店铺应用服务
- `ShopGroupApplicationService.php` - 店铺分组应用服务
- `ShopTagApplicationService.php` - 店铺标签应用服务

#### 服务提供者
- `ShopApplicationServiceProvider.php` - 应用服务提供者

### 3. 基础设施层 (Infrastructure)

#### 仓库实现 (Repositories)
- `ShopRepository.php` - 店铺仓库实现
- `ShopGroupRepository.php` - 店铺分组仓库实现
- `ShopTagRepository.php` - 店铺标签仓库实现

#### 只读仓库实现 (ReadRepositories/Mysql)
- `ShopReadRepository.php` - 店铺只读仓库实现
- `ShopGroupReadRepository.php` - 店铺分组只读仓库实现
- `ShopTagReadRepository.php` - 店铺标签只读仓库实现

### 4. 用户接口层 (UI)

#### 控制器 (Controllers)
- `Controller.php` - 基础控制器
- `LoginController.php` - 登录控制器
- `RegisterController.php` - 注册控制器
- `ForgotPasswordController.php` - 忘记密码控制器
- `AccountController.php` - 账户控制器
- `ChangeAccountController.php` - 更换账户控制器

#### 路由
- `ShopRoute.php` - 店铺路由类

### 5. 配置文件

#### 包配置
- `composer.json` - 包依赖配置
- `config/shop.php` - 店铺配置
- `routes/api.php` - API 路由文件

#### 数据库迁移
- `database/migrations/create_shop_table.php` - 店铺表迁移（继承 User Migration 基类）

#### 语言文件
- `resources/lang/zh/shop.php` - 中文语言文件
- `resources/lang/en/shop.php` - 英文语言文件

#### 文档
- `README.md` - 使用说明
- `CHANGELOG.md` - 变更日志
- `LICENSE` - 许可证

## 主要特性

1. **继承 User 领域功能** - 完全继承 User 领域的基础功能
2. **店铺特有功能** - 提供店铺特有的状态管理和权限控制
3. **DDD 架构** - 严格按照领域驱动设计架构实现
4. **RESTful API** - 提供完整的 RESTful API 接口
5. **多语言支持** - 支持中英文国际化
6. **数据库迁移** - 继承 User Migration 基类，自动创建所有相关表
7. **包工具集成** - 使用 Spatie Laravel Package Tools 进行包管理

## 包服务提供者说明

Shop 领域使用 `Spatie\LaravelPackageTools\PackageServiceProvider` 作为包服务提供者，提供以下功能：

- **自动配置** - 自动加载配置文件、翻译文件、路由文件
- **迁移管理** - 自动运行数据库迁移
- **服务注册** - 在 `packageBooted()` 方法中注册应用服务提供者

## 数据库迁移说明

Shop 领域使用 User 领域的 Migration 基类，通过设置 `$name = 'shop'` 和 `$label = '店铺'`，自动创建以下表：

- `shops` - 店铺主表
- `shop_groups` - 店铺分组表
- `shop_tags` - 店铺标签表
- `shop_tag_pivot` - 店铺标签关联表

这种方式确保了与 User 领域的数据结构完全一致，同时减少了重复代码。

## 使用方式

### 1. 安装包

```bash
composer require red-jasmine/shop
```

### 2. 配置认证守卫

在 `config/auth.php` 中添加店铺守卫：

```php
'guards' => [
    'shop' => [
        'driver' => 'jwt',
        'provider' => 'shops',
    ],
],

'providers' => [
    'shops' => [
        'driver' => 'eloquent',
        'model' => RedJasmine\Shop\Domain\Models\Shop::class,
    ],
],
```

### 3. 运行迁移

```bash
php artisan migrate
```

### 4. 使用 API

店铺领域会自动注册以下 API 路由（前缀：`/api/shop`）：

- `POST /api/shop/auth/login/login` - 店铺登录
- `POST /api/shop/auth/login/captcha` - 获取登录验证码
- `POST /api/shop/auth/register/captcha` - 获取注册验证码
- `POST /api/shop/auth/register/register` - 店铺注册
- `POST /api/shop/auth/forgot-password/captcha` - 获取忘记密码验证码
- `POST /api/shop/auth/forgot-password/forgot-password` - 重置密码
- `GET /api/shop/account/info` - 获取店铺信息
- `PUT /api/shop/account/base-info` - 更新店铺基础信息
- `GET /api/shop/account/socialites` - 获取社交账号
- `POST /api/shop/account/unbind-socialite` - 解绑社交账号
- `PUT /api/shop/account/safety/password` - 修改密码
- `POST /api/shop/account/safety/change-account/captcha` - 获取更换账号验证码
- `POST /api/shop/account/safety/change-account/verify` - 验证更换账号
- `POST /api/shop/account/safety/change-account/change` - 更换账号

## 扩展说明

Shop 领域已经完成了基础的用户管理功能，后续可以根据业务需求添加：

1. **店铺信息管理** - 店铺详细信息、营业执照等
2. **店铺权限管理** - 基于角色的权限控制
3. **店铺审核流程** - 店铺注册审核、状态变更审核
4. **店铺统计功能** - 店铺数据统计和分析
5. **店铺设置功能** - 店铺个性化设置

## 注意事项

1. 所有代码都遵循 Red Jasmine Framework 的编码规范
2. 使用 DDD 架构，确保领域逻辑的清晰分离
3. 继承 User 领域的基础功能，避免重复代码
4. 提供完整的 API 接口，支持前端开发
5. 包含完整的数据库迁移和语言文件
6. 数据库迁移继承 User Migration 基类，确保数据结构一致性
7. 使用 Spatie Laravel Package Tools 进行包管理，提供更好的开发体验 