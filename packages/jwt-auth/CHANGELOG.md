# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2024-01-01

### Added
- 初始版本发布
- 自定义JwtUserProvider，支持从token直接解析用户信息
- JwtGuard实现，支持多用户类型认证
- JwtHelper辅助类，提供便捷的JWT操作
- 支持多模型配置，可根据不同用户类型使用不同模型
- 中间件支持，包括基本认证和用户类型验证
- 完整的测试用例
- 详细的使用文档和示例

### Features
- **无数据库查询**: 通过`retrieveByToken`方法直接从token解析用户信息，无需查询数据库
- **多用户类型支持**: 支持配置多种用户类型（user、admin、merchant等）
- **灵活的配置**: 通过`auth.providers.jwt.models`配置不同用户类型对应的模型
- **完整的Laravel集成**: 完全兼容Laravel认证系统
- **类型安全**: 使用强类型声明和PHPDoc注释
- **易于使用**: 提供门面和辅助类简化操作

### Technical Details
- 基于tymon/jwt-auth包构建
- 实现Laravel的StatefulGuard接口
- 支持JWT token的生成、验证、刷新和失效
- 支持自定义声明和用户类型识别
- 提供完整的错误处理和异常管理
