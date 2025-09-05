# Red Jasmine Framework

[![PHP Version](https://img.shields.io/badge/php-%5E8.4-787CB5?logo=php)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/laravel-%5E12.0-FF2D20?logo=laravel)](https://laravel.com/)
[![License](https://img.shields.io/github/license/red-jasmine/framework.svg?style=flat-square)](https://github.com/red-jasmine/framework/blob/main/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/red-jasmine/framework.svg?style=flat-square)](https://packagist.org/packages/red-jasmine/framework)

> 基于 Laravel 12.0+ 和 PHP 8.4+ 的现代化SaaS电商框架，采用领域驱动设计（DDD）和模块化架构，为构建可扩展的电商系统提供完整解决方案。

## ✨ 特性

- 🏗️ **模块化架构** - 基于DDD设计，支持模块独立开发和部署
- 🎯 **开箱即用** - 提供完整的电商业务模块（用户、商品、订单、支付等）
- 🔧 **Filament管理后台** - 集成现代化的管理界面
- 🌐 **多端支持** - 统一的API服务于Web、移动端、小程序等
- 🔐 **权限管理** - 细粒度的角色权限控制系统
- 💰 **支付集成** - 支持多种支付方式和分账功能
- 📦 **物流系统** - 完整的物流管理和跟踪功能
- 🎁 **营销工具** - 优惠券、积分商城、VIP等营销功能
- 🌍 **国际化** - 支持多语言、多货币、多地区
- 🚀 **高性能** - 基于现代PHP特性，支持缓存和队列优化

## 📋 系统要求

- **PHP**: >= 8.4
- **Laravel**: >= 12.0
- **Composer**: 最新版本
- **数据库**: MySQL 8.0+ / PostgreSQL 13+
- **缓存**: Redis 6.0+ (推荐)
- **队列**: Redis / Database

## 🏗️ 架构概述

### 技术栈

- **核心框架**: Laravel 12.x
- **PHP版本**: 8.4+
- **数据封装**: Spatie Laravel Data
- **查询构建**: Spatie Laravel Query Builder
- **管理后台**: Filament Admin Panel
- **测试框架**: PestPHP + Orchestra Testbench
- **代码规范**: PSR-12

### 模块化设计

采用DDD（领域驱动设计）架构，每个业务领域都是独立的Composer包：

```
framework/
├── packages/          # 核心业务模块
│   ├── support/       # 基础支持包
│   ├── user/          # 用户管理
│   ├── product/       # 商品管理
│   ├── order/         # 订单管理
│   ├── payment/       # 支付系统
│   ├── logistics/     # 物流管理
│   ├── wallet/        # 钱包系统
│   ├── coupon/        # 优惠券
│   ├── message/       # 消息系统
│   └── ...
├── filament/          # Filament管理模块
│   ├── filament-admin/
│   ├── filament-user/
│   ├── filament-product/
│   └── ...
└── workbench/         # 开发测试环境
```

## 🚀 快速开始

### 安装

```bash
# 克隆项目
git clone https://github.com/red-jasmine/framework.git
cd framework

# 安装依赖
composer install

# 构建开发环境
composer build
```

### 启动开发服务器

```bash
# 启动本地开发服务器
composer serve
# 服务器将运行在 http://localhost:8088
```

### 运行测试

```bash
# 运行所有测试
composer test

# 代码静态分析
composer lint
```

## 📦 核心模块

### 基础模块

| 模块 | 描述 | 状态 |
|-----|------|------|
| **Support** | 基础支持包，提供DDD架构基础设施 | ✅ 完成 |
| **Admin** | 管理员系统，角色权限管理 | ✅ 完成 |
| **User** | 用户管理，认证授权 | ✅ 完成 |
| **Region** | 地区管理，国际化支持 | 🚧 开发中 |
| **Address** | 地址管理 | ✅ 完成 |

### 电商核心

| 模块 | 描述 | 状态 |
|-----|------|------|
| **Product** | 商品管理，SKU，规格属性 | ✅ 完成 |
| **Order** | 订单管理，订单流程 | ✅ 完成 |
| **Payment** | 支付系统，多渠道支付，分账 | ✅ 完成 |
| **Logistics** | 物流管理，运费计算 | ✅ 完成 |
| **Shopping** | 购物车，商品搜索 | ✅ 完成 |
| **Shop** | 店铺管理 | ✅ 完成 |

### 营销工具

| 模块 | 描述 | 状态 |
|-----|------|------|
| **Coupon** | 优惠券系统 | ✅ 完成 |
| **Promotion** | 促销活动管理 | ✅ 完成 |
| **Points Mall** | 积分商城 | ✅ 完成 |
| **VIP** | 会员等级系统 | ✅ 完成 |
| **Distribution** | 分销系统 | 🚧 开发中 |

### 扩展功能

| 模块 | 描述 | 状态 |
|-----|------|------|
| **Wallet** | 钱包系统，充值提现 | ✅ 完成 |
| **Message** | 消息系统，推送通知 | ✅ 完成 |
| **Article** | 文章内容管理 | ✅ 完成 |
| **Community** | 社区功能 | ✅ 完成 |
| **Announcement** | 公告系统 | ✅ 完成 |
| **Card** | 卡券系统 | ✅ 完成 |
| **Captcha** | 验证码服务 | ✅ 完成 |
| **Invitation** | 邀请系统 | 🚧 开发中 |
| **Interaction** | 互动功能 | 🚧 开发中 |
| **Socialite** | 社交登录 | 🚧 开发中 |
| **Resource Usage** | 资源使用统计 | 🚧 开发中 |

### 管理后台 (Filament)

每个核心模块都配备了对应的Filament管理界面：

- `filament-admin` - 核心管理功能
- `filament-user` - 用户管理界面
- `filament-product` - 商品管理界面
- `filament-order` - 订单管理界面
- `filament-coupon` - 优惠券管理界面
- ... 等等

## 🔧 开发指南

### 目录结构

每个领域包都遵循统一的DDD架构：

```
{domain}/
├── src/
│   ├── Domain/                 # 领域层
│   │   ├── Models/             # 领域模型
│   │   ├── Services/           # 领域服务
│   │   ├── Repositories/       # 仓库接口
│   │   ├── Events/             # 领域事件
│   │   └── Data/               # 数据传输对象
│   ├── Application/            # 应用层
│   │   └── Services/           # 应用服务
│   ├── Infrastructure/         # 基础设施层
│   │   ├── Repositories/       # 仓库实现
│   │   └── ReadRepositories/   # 查询仓库
│   └── UI/                     # 用户接口层
│       └── Http/               # HTTP控制器
├── database/migrations/        # 数据库迁移
├── config/                     # 配置文件
├── resources/                  # 资源文件
└── routes/                     # 路由定义
```

### 开发脚本

```bash
# 启动开发服务器
composer serve

# 构建工作台
composer build

# 清理缓存
composer clear

# 运行测试
composer test

# 代码分析
composer lint
```

### API文档

所有API都遵循RESTful规范，支持多角色访问：

- **用户端API**: `/api/user/*`
- **商家端API**: `/api/shop/*` 
- **管理端API**: `/api/admin/*`

每个模块提供完整的CRUD操作和业务特定的端点。

## 📚 文档

- [项目架构](docs/architecture.md)
- [API文档](docs/api.md)
- [部署指南](docs/deployment.md)
- [开发规范](docs/development-standards.md)

### 参考资源

- [Laravel 12.x 文档](https://laravel.com/docs/12.x/)
- [Spatie Laravel Data](https://spatie.be/docs/laravel-data/v4/)
- [Spatie Query Builder](https://spatie.be/docs/laravel-query-builder/v6/)
- [Filament Admin Panel](https://filamentphp.com/)
- [VitePress 文档](https://vitepress.dev/)

## 🤝 贡献

欢迎贡献代码！请遵循以下步骤：

1. Fork 本仓库
2. 创建功能分支 (`git checkout -b feature/amazing-feature`)
3. 提交更改 (`git commit -m 'Add some amazing feature'`)
4. 推送到分支 (`git push origin feature/amazing-feature`)
5. 创建 Pull Request

### 开发规范

- 遵循 PSR-12 编码标准
- 使用 PHPDoc 注释
- 编写单元测试
- 更新相关文档

## 📄 许可证

本项目采用 [MIT 许可证](LICENSE.md)。

## 👥 作者

- **liushoukun** - [liushoukun66@gmail.com](mailto:liushoukun66@gmail.com)

## 🙏 致谢

感谢以下开源项目的支持：

- [Laravel](https://laravel.com/) - 优雅的PHP Web框架
- [Filament](https://filamentphp.com/) - 现代化的管理面板
- [Spatie](https://spatie.be/) - 优秀的Laravel生态包
- [PestPHP](https://pestphp.com/) - 优雅的测试框架

---

<div align="center">
  <strong>构建下一代电商系统 🚀</strong>
</div>
