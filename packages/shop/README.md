# Red Jasmine Shop Package

电商店铺领域包，提供店铺管理的基础功能。

## 功能特性

- 店铺用户管理
- 店铺分组管理
- 店铺标签管理
- 店铺认证授权
- 店铺资料管理

## 安装

```bash
composer require red-jasmine/shop
```

## 配置

在 `config/auth.php` 中添加店铺守卫配置：

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

## 使用

### 路由

店铺领域会自动注册以下 API 路由（前缀：`/api/shop`）：

```php
// 店铺认证路由
Route::prefix('api/shop/auth')->group(function () {
    Route::post('login/login', 'LoginController@login');
    Route::post('login/captcha', 'LoginController@captcha');
    Route::post('register/captcha', 'RegisterController@captcha');
    Route::post('register/register', 'RegisterController@register');
    Route::post('forgot-password/captcha', 'ForgotPasswordController@captcha');
    Route::post('forgot-password/forgot-password', 'ForgotPasswordController@resetPassword');
});

// 店铺账户路由
Route::prefix('api/shop/account')->middleware('auth:shop')->group(function () {
    Route::get('info', 'AccountController@info');
    Route::put('base-info', 'AccountController@updateBaseInfo');
    Route::get('socialites', 'AccountController@socialites');
    Route::post('unbind-socialite', 'AccountController@unbindSocialite');
    Route::put('safety/password', 'AccountController@password');
    Route::post('safety/change-account/captcha', 'ChangeAccountController@captcha');
    Route::post('safety/change-account/verify', 'ChangeAccountController@verify');
    Route::post('safety/change-account/change', 'ChangeAccountController@change');
});
```

### 服务

```php
use RedJasmine\Shop\Application\Services\ShopApplicationService;

class ShopController extends Controller
{
    public function __construct(
        protected ShopApplicationService $shopService
    ) {
    }

    public function index()
    {
        $shops = $this->shopService->paginate(PaginateQuery::from(request()));
        return response()->json($shops);
    }
}
```

## 数据库迁移

包会自动运行数据库迁移，创建以下表：

- `shops` - 店铺主表
- `shop_groups` - 店铺分组表
- `shop_tags` - 店铺标签表
- `shop_tag_pivot` - 店铺标签关联表

## 许可证

MIT License 