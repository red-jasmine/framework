# Cursor Rules 规则文档

## 概述
本目录包含了 Red Jasmine Framework 项目的所有 Cursor 规则，这些规则被拆分为多个专注的文件，便于管理和维护。

## 规则文件列表

### 1. 项目架构规则
- **文件**: `project-architecture.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 项目概述和技术栈要求 (PHP 8.4+, Laravel 12.0+)
  - DDD 架构原则
  - 目录结构规范
  - 包命名规范

### 2. Support 包核心组件规则
- **文件**: `support-components.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - ApplicationService 应用服务基类
  - Data 数据传输对象基类
  - HasHooks 钩子机制
  - 仓库接口规范
  - 控制器动作Trait

### 3. 领域层规则
- **文件**: `domain-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 领域模型规范
  - 枚举规范
  - 值对象规范
  - 领域服务规范
  - 数据传输对象规范
  - 转换器规范

### 4. 应用层规则
- **文件**: `application-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 应用服务规范
  - 命令规范
  - 查询规范
  - 命令处理器规范
  - 查询处理器规范

### 5. 基础设施层规则
- **文件**: `infrastructure-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 仓库实现规范
  - 只读仓库实现规范
  - 过滤器配置规范
  - 排序配置规范
  - 查询构建器使用规范

### 6. 用户接口层规则
- **文件**: `ui-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 控制器规范
  - API资源规范
  - 请求验证规范
  - 路由定义规范
  - 中间件规范
  - 响应格式规范

### 7. 代码规范规则
- **文件**: `coding-standards.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 核心原则
  - PHP 8.4+ 特性使用
  - Laravel 最佳实践
  - 命名规范
  - 类型声明和错误处理
  - 数据验证
  - 数据库操作
  - 性能优化
  - 安全实践

### 8. PHP 文件专用规则
- **文件**: `php-files.mdc`
- **适用范围**: PHP 文件 (globs: ["*.php"])
- **内容**: 
  - PHP 文件基本要求
  - 文件结构规范
  - 严格类型声明
  - 命名空间和导入
  - 类结构顺序
  - 属性和方法声明
  - 代码格式
  - PHPDoc注释

### 9. 文档规范规则
- **文件**: `documentation.mdc`
- **适用范围**: 手动应用 (description: "文档规范和UML图标准，用于编写项目文档时手动应用")
- **内容**: 
  - 文档结构要求
  - 文档格式规范
  - 领域文档内容规范
  - UML图规范
  - 代码文档规范
  - 数据库设计文档
  - 文档维护规范

### 10. Filament 管理界面规范
- **文件**: `filament-admin.mdc`
- **适用范围**: Filament 包文件 (globs: ["packages/filament-*/src/**/*.php"])
- **内容**: 
  - 包结构和命名规范
  - 核心类实现规范 (ServiceProvider, Plugin, Cluster)
  - 资源类和页面类规范
  - 组件开发规范
  - 编码和命名规范
  - 国际化规范
  - 架构集成规范
  - 扩展功能规范
  - 测试和性能优化规范

## 规则应用机制

### 自动应用规则
以下规则会自动应用到所有文件：
- project-architecture.mdc
- support-components.mdc
- domain-layer.mdc
- application-layer.mdc
- infrastructure-layer.mdc
- ui-layer.mdc
- coding-standards.mdc

### 文件类型特定规则
- `php-files.mdc`: 只适用于 .php 文件 (globs: ["*.php"])
- `filament-admin.mdc`: 只适用于 Filament 包文件 (globs: ["packages/filament-*/src/**/*.php"])

### 手动应用规则
- `documentation.mdc`: 需要手动应用，用于文档编写时 (description: "文档规范和UML图标准，用于编写项目文档时手动应用")

## 最近优化

### 已修复的高优先级问题
1. **重复内容清理**: 清理了 `coding-standards.mdc` 和 `php-files.mdc` 中的重复内容
2. **版本统一**: 统一PHP版本为8.4+，Laravel版本为12.0+
3. **规则应用范围调整**: 调整了 `documentation.mdc` 的应用范围为手动应用
4. **项目架构文件修复**: 修复了 `project-architecture.mdc` 中的重复内容
5. **Filament 规范新增**: 新增了 `filament-admin.mdc` 规范，专门针对 Filament 管理界面开发

### 优化效果
- **编码标准文件**: 专注于整体编码原则、Laravel最佳实践、命名规范等
- **PHP文件规范**: 专注于PHP文件的格式、结构、声明等具体要求
- **版本一致性**: 确保所有文件中的技术栈要求保持一致
- **规则精确性**: 确保规则在正确的时机和范围内应用
- **Filament 专用规范**: 针对 Filament 管理界面开发的专门规范，确保管理界面的一致性和可维护性

## 使用建议

### 新建包时
重点关注：
- project-architecture.mdc
- support-components.mdc
- domain-layer.mdc

### 编写业务逻辑时
重点关注：
- application-layer.mdc
- infrastructure-layer.mdc
- ui-layer.mdc

### 代码审查时
重点关注：
- coding-standards.mdc
- php-files.mdc

### 编写文档时
重点关注：
- documentation.mdc

### 开发 Filament 管理界面时
重点关注：
- filament-admin.mdc

## 维护说明

### 规则更新
- 规则变更时，只需更新相关的特定文件
- 保持规则文件之间的一致性
- 定期检查规则的有效性

### 规则验证
- 新增规则后，验证其在实际项目中的应用效果
- 确保规则之间没有冲突
- 保持规则的简洁性和可理解性 