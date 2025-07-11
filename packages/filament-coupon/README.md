# Filament Coupon Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/red-jasmine/filament-coupon.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-coupon)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-coupon/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/red-jasmine/filament-coupon/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/red-jasmine/filament-coupon/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/red-jasmine/filament-coupon/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/red-jasmine/filament-coupon.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/filament-coupon)

Red Jasmine 优惠券系统的 Filament 管理面板扩展。

## 安装

您可以通过 Composer 安装该包：

```bash
composer require red-jasmine/filament-coupon
```

## 使用方法

### 注册插件

在您的 Filament 面板提供商中注册插件：

```php
use RedJasmine\FilamentCoupon\FilamentCouponPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentCouponPlugin::make(),
        ]);
}
```

### 功能特性

- 优惠券管理
- 用户优惠券查看
- 优惠券使用记录
- 优惠券发放统计
- 完整的 CRUD 操作
- 支持多种优惠券类型
- 灵活的规则配置

## 测试

```bash
composer test
```

## 变更日志

请参阅 [CHANGELOG](CHANGELOG.md) 了解最近的更改。

## 贡献

请参阅 [CONTRIBUTING](CONTRIBUTING.md) 了解详细信息。

## 安全漏洞

请审查 [我们的安全政策](../../security/policy) 了解如何报告安全漏洞。

## 许可证

MIT 许可证 (MIT)。请参阅 [许可证文件](LICENSE.md) 了解更多信息。 