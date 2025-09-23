# Red Jasmine JWT Auth

JWT认证包，为Red Jasmine框架提供灵活的JWT认证功能。

## 特性

- 自定义UserProvider，支持从token直接解析用户信息
- 支持多模型配置，可根据不同用户类型使用不同的模型
- 无需数据库查询即可从token中获取用户信息
- 兼容Laravel认证系统

## 安装

```bash
composer require red-jasmine/jwt-auth
```

## 配置

### 1. 发布配置文件

```bash
php artisan vendor:publish --provider="RedJasmine\JwtAuth\JwtAuthServiceProvider" --tag="config"
```

### 2. 配置认证提供者

在 `config/auth.php` 中添加JWT提供者配置：

```php
'providers' => [
    'jwt' => [
        'driver' => 'jwt',
        'models' => [
            'user' => \App\Models\User::class,
            'admin' => \App\Models\Admin::class,
            // 更多类型...
        ],
    ],
],
```

### 3. 配置守卫

```php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'jwt',
    ],
],
```

## 使用

### 生成Token

```php
use Illuminate\Support\Facades\Auth;

// 登录用户
$user = User::find(1);
$token = Auth::guard('api')->login($user);
```

### 验证Token

```php
use Illuminate\Support\Facades\Auth;

// 从请求头中获取token
$user = Auth::guard('api')->user();
```

### 多模型支持

```php
// 为用户类型生成token
$token = Auth::guard('api')->login($user, 'user');

// 为管理员类型生成token
$token = Auth::guard('api')->login($admin, 'admin');
```

## 架构

本包采用以下架构设计：

- **JwtUserProvider**: 自定义用户提供者，实现从token解析用户信息
- **JwtGuard**: JWT守卫，处理token验证和用户认证
- **JwtAuthServiceProvider**: 服务提供者，注册认证驱动和配置

## 许可证

MIT License
