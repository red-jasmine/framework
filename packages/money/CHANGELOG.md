# Changelog

All notable changes to `red-jasmine/money` will be documented in this file.

## [1.0.0] - 2025-11-06

### Added

- 🎉 初始版本发布
- ✨ 支持 ISO 标准货币
- ✨ 支持比特币货币
- ✨ 支持自定义虚拟货币配置
- ✨ Eloquent Model 金额转换器 (`MoneyCast`)
- ✨ Spatie Laravel Data 集成支持
- ✨ 支持 decimal 和 bigint 两种存储类型
- ✨ 支持共享货币字段
- ✨ 聚合货币管理器 (`AggregateCurrencies`)
- ✨ 自定义货币类 (`CustomCurrencies`)
- ✨ 完善的异常处理机制
- ✨ 配置文件支持
- ✨ 中文语言包
- 📚 完整的文档和示例
- 📚 迁移指南

### Features

#### 货币支持

- ISO 货币：支持指定货币列表或全部 ISO 货币
- 比特币：可选的比特币支持
- 自定义货币：通过配置文件定义虚拟货币
  - 积分系统（POINTS, BONUS）
  - 游戏货币（GOLD, DIAMOND, SILVER）
  - 电商虚拟币（VOUCHER, COUPON, CREDIT）
  - 会员积分（MEMBER_POINTS, VIP_POINTS）
  - 更多自定义场景

#### 转换器功能

- **Eloquent Model 集成**
  - 自动转换数据库字段到 Money 对象
  - 支持自定义字段名
  - 支持共享货币字段
  
- **Spatie Data 集成**
  - Cast 输入数据转换
  - Transform 输出数据转换
  - 完整的类型安全

- **存储类型**
  - `decimal`: 小数格式存储（如 100.50）
  - `bigint`: 最小单位存储（如 10050 分）

- **输入支持**
  - Money 对象
  - 标量值（字符串、数字）
  - 数组格式 `['amount' => 100.50, 'currency' => 'CNY']`

#### 性能优化

- 单例模式缓存 `ISOCurrencies` 实例
- 单例模式缓存 `DecimalMoneyParser` 实例
- 避免重复创建对象

#### 错误处理

- 完善的异常捕获
- 优雅的错误降级
- 无效货币代码回退到默认货币
- 解析失败返回 null

### Technical Details

- PHP 版本要求：^8.2
- Laravel 版本支持：^11.0|^12.0
- 依赖：`moneyphp/money` ^4.6
- 依赖：`spatie/laravel-data` ^4.0|^5.0

### Documentation

- README.md - 完整使用文档
- MIGRATION.md - 迁移指南
- config/money.example.php - 配置示例
- 中文语言支持

### Backwards Compatibility

- 完全兼容原 `NewMoneyCast` API
- 无需修改数据库结构
- 提供别名支持平滑迁移

## [Unreleased]

### Planned

- [ ] 货币转换功能
- [ ] 货币格式化器扩展
- [ ] 更多语言包支持
- [ ] 单元测试覆盖
- [ ] 性能基准测试

---

## 版本说明

本项目遵循 [语义化版本](https://semver.org/lang/zh-CN/)。

格式基于 [Keep a Changelog](https://keepachangelog.com/zh-CN/1.0.0/)。

