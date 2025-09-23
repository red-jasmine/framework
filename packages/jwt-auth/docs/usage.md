# JWT认证包使用指南

## 安装和配置

### 1. 安装包

```bash
composer require red-jasmine/jwt-auth
```

### 2. 发布配置文件

```bash
php artisan vendor:publish --provider="RedJasmine\JwtAuth\JwtAuthServiceProvider" --tag="config"
```

### 3. 配置认证提供者

在 `config/auth.php` 中添加JWT提供者配置：

```php
'providers' => [
    'jwt' => [
        'driver' => 'jwt',
        'models' => [
            'user' => \App\Models\User::class,
            'admin' => \App\Models\Admin::class,
            'merchant' => \App\Models\Merchant::class,
            // 可以添加更多用户类型
        ],
    ],
],

'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'jwt',
    ],
],
```

## 基本使用

### 生成Token

```php
use Illuminate\Support\Facades\Auth;
use RedJasmine\JwtAuth\Facades\JwtAuth;

// 方式1：使用Auth门面
$user = User::find(1);
$token = Auth::guard('api')->login($user, 'user');

// 方式2：使用JwtAuth门面
$token = JwtAuth::generateToken($user, 'user');

// 方式3：不指定用户类型（自动推断）
$token = JwtAuth::generateToken($user);
```

### 验证Token

```php
use Illuminate\Support\Facades\Auth;
use RedJasmine\JwtAuth\Facades\JwtAuth;

// 方式1：使用Auth门面
$user = Auth::guard('api')->user();

// 方式2：使用JwtAuth门面
$user = JwtAuth::getUserFromToken();

// 方式3：验证特定token
$isValid = JwtAuth::validateToken($token);

// 获取的用户是JwtUser实例，同时实现Authenticatable和UserInterface接口
if ($user) {
    // Authenticatable接口方法
    $userId = $user->getAuthIdentifier();
    
    // UserInterface接口方法
    $userType = $user->getType();
    $nickname = $user->getNickname();
    $avatar = $user->getAvatar();
    $userData = $user->getUserData();
    
    // 动态属性访问
    $name = $user->name;
    $email = $user->email;
}
```

### 刷新Token

```php
use RedJasmine\JwtAuth\Facades\JwtAuth;

$newToken = JwtAuth::refreshToken();
```

### 使Token失效

```php
use RedJasmine\JwtAuth\Facades\JwtAuth;

$success = JwtAuth::invalidateToken();
```

## 通用用户模型

JWT认证包提供了一个通用的`JwtUser`模型，该模型同时实现了`Authenticatable`和`UserInterface`接口，用于从token解析用户信息。

### JwtUser特性

```php
use RedJasmine\JwtAuth\Models\JwtUser;

// 创建JwtUser实例
$user = new JwtUser([
    'id' => 1,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'avatar' => 'https://example.com/avatar.jpg',
], 'user');

// Authenticatable接口方法
$userId = $user->getAuthIdentifier(); // 1
$authName = $user->getAuthIdentifierName(); // 'id'
$password = $user->getAuthPassword(); // ''

// UserInterface接口方法
$type = $user->getType(); // 'user'
$id = $user->getID(); // '1'
$nickname = $user->getNickname(); // 'John Doe'
$avatar = $user->getAvatar(); // 'https://example.com/avatar.jpg'
$userData = $user->getUserData(); // ['id' => 1, 'name' => 'John Doe', ...]

// 动态属性访问
$name = $user->name; // 'John Doe'
$email = $user->email; // 'john@example.com'

// 属性操作
$user->setAttribute('phone', '1234567890');
$phone = $user->getAttribute('phone'); // '1234567890'
$hasPhone = $user->hasAttribute('phone'); // true

// 用户类型操作
$user->setUserType('admin');
$userType = $user->getUserType(); // 'admin'

// 序列化支持
$array = $user->toArray();
$json = $user->toJson();
```

### 昵称和头像的智能回退

`JwtUser`模型提供了智能的昵称和头像获取逻辑：

```php
// 昵称回退顺序：nickname -> name -> username
$user1 = new JwtUser(['nickname' => 'Nick'], 'user');
$user1->getNickname(); // 'Nick'

$user2 = new JwtUser(['name' => 'Name'], 'user');
$user2->getNickname(); // 'Name'

$user3 = new JwtUser(['username' => 'Username'], 'user');
$user3->getNickname(); // 'Username'

// 头像回退顺序：avatar -> avatar_url -> profile_image
$user4 = new JwtUser(['avatar' => 'avatar.jpg'], 'user');
$user4->getAvatar(); // 'avatar.jpg'

$user5 = new JwtUser(['avatar_url' => 'avatar_url.jpg'], 'user');
$user5->getAvatar(); // 'avatar_url.jpg'

$user6 = new JwtUser(['profile_image' => 'profile.jpg'], 'user');
$user6->getAvatar(); // 'profile.jpg'
```

## 多用户类型支持

### 配置多用户类型

```php
// config/auth.php
'providers' => [
    'jwt' => [
        'driver' => 'jwt',
        'models' => [
            'user' => \App\Models\User::class,
            'admin' => \App\Models\Admin::class,
            'merchant' => \App\Models\Merchant::class,
        ],
    ],
],
```

### 为不同用户类型生成Token

```php
// 普通用户
$user = User::find(1);
$userToken = JwtAuth::generateToken($user, 'user');

// 管理员
$admin = Admin::find(1);
$adminToken = JwtAuth::generateToken($admin, 'admin');

// 商家
$merchant = Merchant::find(1);
$merchantToken = JwtAuth::generateToken($merchant, 'merchant');
```

### 获取Token中的用户类型

```php
$userType = JwtAuth::getUserTypeFromToken();
// 返回: 'user', 'admin', 'merchant' 等
```

## 中间件使用

### 基本认证中间件

```php
// 在路由中使用
Route::middleware('jwt.auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
});

// 在控制器中使用
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
}
```

### 用户类型验证中间件

JWT认证包提供了两种用户类型验证中间件：

#### 1. 基于Token的用户类型验证 (`jwt.auth.type`)

```php
// 只允许管理员访问
Route::middleware('jwt.auth.type:api,admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// 允许多种用户类型
Route::middleware('jwt.auth.type:api,admin,merchant')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

#### 2. 基于当前用户的用户类型验证 (`jwt.auth.user.type`)

```php
// 只允许管理员访问（从当前用户对象获取类型）
Route::middleware('jwt.auth.user.type:api,admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// 允许多种用户类型
Route::middleware('jwt.auth.user.type:api,admin,merchant')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

#### 中间件区别

- **`jwt.auth.type`**: 直接从JWT token中解析用户类型，性能更好
- **`jwt.auth.user.type`**: 从当前用户对象获取类型，支持实现`UserInterface`接口的用户模型

## API控制器示例

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\JwtAuth\Facades\JwtAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'nullable|in:user,admin,merchant',
        ]);

        $userType = $credentials['user_type'] ?? 'user';
        unset($credentials['user_type']);

        if (Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();
            $token = JwtAuth::generateToken($user, $userType);

            return response()->json([
                'token' => $token,
                'user' => $user,
                'user_type' => $userType,
            ]);
        }

        return response()->json(['message' => '登录失败'], 401);
    }

    public function profile()
    {
        $user = Auth::guard('api')->user();
        $userType = JwtAuth::getUserTypeFromToken();

        return response()->json([
            'user' => $user,
            'user_type' => $userType,
        ]);
    }

    public function refresh()
    {
        $newToken = JwtAuth::refreshToken();

        if (!$newToken) {
            return response()->json(['message' => 'Token刷新失败'], 401);
        }

        return response()->json(['token' => $newToken]);
    }

    public function logout()
    {
        $success = JwtAuth::invalidateToken();

        if ($success) {
            return response()->json(['message' => '登出成功']);
        }

        return response()->json(['message' => '登出失败'], 500);
    }
}
```

## 高级用法

### 自定义Token声明

```php
use Tymon\JWTAuth\Facades\JWTAuth;

$user = User::find(1);
$token = JWTAuth::customClaims([
    'user_type' => 'user',
    'custom_claims' => [
        'name' => $user->name,
        'email' => $user->email,
        'permissions' => $user->permissions,
        'role' => $user->role,
    ]
])->fromUser($user);
```

### 检查用户类型权限

```php
class UserController extends Controller
{
    public function index()
    {
        $userType = JwtAuth::getUserTypeFromToken();
        
        if (!in_array($userType, ['admin', 'super_admin'])) {
            return response()->json(['message' => '权限不足'], 403);
        }

        // 管理员逻辑
    }
}
```

### 获取Token信息

```php
$payload = JwtAuth::getTokenPayload();
$userId = $payload['sub'];
$userType = $payload['user_type'];
$customClaims = $payload['custom_claims'] ?? [];
```

## 配置选项

### JWT认证配置

```php
// config/jwt-auth.php
return [
    'default_user_type' => 'user',
    
    'models' => [
        'user' => \App\Models\User::class,
        'admin' => \App\Models\Admin::class,
    ],
    
    'token' => [
        'ttl' => 60 * 24 * 7, // 7天
        'refresh_ttl' => 60 * 24 * 30, // 30天
        'blacklist_enabled' => true,
    ],
    
    'custom_claims' => [
        'include' => ['id', 'name', 'email', 'avatar'],
        'exclude' => ['password', 'remember_token'],
    ],
    
    'debug' => false,
];
```

## 错误处理

```php
try {
    $user = JwtAuth::getUserFromToken($token);
} catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    // Token过期
    return response()->json(['message' => 'Token已过期'], 401);
} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    // Token无效
    return response()->json(['message' => 'Token无效'], 401);
} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    // JWT异常
    return response()->json(['message' => '认证失败'], 401);
}
```
