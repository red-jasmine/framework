# 优惠券 Filament 管理面板安装指南

## 安装

1. 通过 Composer 安装包：

```bash
composer require red-jasmine/filament-coupon
```

2. 在您的 Filament 面板提供商中注册插件：

```php
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RedJasmine\FilamentCoupon\FilamentCouponPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentCouponPlugin::make(),
            ]);
    }
}
```

## 功能特性

### 1. 优惠券管理

- **创建优惠券**: 支持创建多种类型的优惠券
  - 固定金额优惠
  - 百分比优惠
  - 满减优惠
  - 免运费优惠

- **优惠券状态管理**: 
  - 草稿状态
  - 已发布状态
  - 暂停状态
  - 过期状态

- **有效期设置**:
  - 绝对时间：设置具体的开始和结束时间
  - 相对时间：设置有效期时长

### 2. 用户优惠券管理

- **查看用户优惠券**: 查看用户领取的所有优惠券
- **优惠券状态跟踪**: 可用、已使用、已过期
- **批量操作**: 批量发放、批量过期

### 3. 使用记录统计

- **使用记录查看**: 查看优惠券的使用记录
- **数据分析**: 使用时间、订单信息、优惠金额等
- **导出功能**: 支持导出使用记录数据

### 4. 发放统计

- **统计分析**: 
  - 发放数量统计
  - 使用率统计
  - 过期率统计
  - 成本统计

- **图表展示**: 直观的数据图表展示

## 配置选项

发布配置文件：

```bash
php artisan vendor:publish --tag="red-jasmine-filament-coupon-config"
```

配置文件位置：`config/red-jasmine-filament-coupon.php`

### 主要配置选项：

```php
return [
    // 集群导航图标
    'cluster_navigation_icon' => 'heroicon-o-ticket',
    
    // 资源配置
    'resources' => [
        'coupon' => [
            'enabled' => true,
            'navigation_sort' => 1,
        ],
        'user_coupon' => [
            'enabled' => true,
            'navigation_sort' => 2,
        ],
        'coupon_usage' => [
            'enabled' => true,
            'navigation_sort' => 3,
        ],
        'coupon_issue_statistic' => [
            'enabled' => true,
            'navigation_sort' => 4,
        ],
    ],
    
    // 其他配置...
];
```

## 权限控制

该包使用 RedJasmine 框架的权限控制系统，支持：

- 基于所有者的数据隔离
- 资源级别的权限控制
- 操作级别的权限控制

## 自定义扩展

您可以通过以下方式扩展功能：

1. **自定义资源**: 继承现有资源类并重写方法
2. **自定义页面**: 创建自定义页面类
3. **自定义操作**: 添加自定义表格操作和表单操作
4. **自定义过滤器**: 添加自定义过滤器

## 依赖要求

- PHP 8.1+
- Laravel 10.0+
- Filament 3.0+
- red-jasmine/coupon 包
- red-jasmine/filament-core 包

## 支持

如果您遇到任何问题或需要帮助，请：

1. 查看 [GitHub Issues](https://github.com/red-jasmine/filament-coupon/issues)
2. 提交新的 Issue
3. 参与社区讨论

## 更新日志

请查看 [CHANGELOG.md](../CHANGELOG.md) 了解详细的更新记录。 