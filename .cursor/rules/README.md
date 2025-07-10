# Cursor Rules 规则文档

## 概述
本目录包含了 Red Jasmine Framework 项目的所有 Cursor 规则，这些规则被拆分为多个专注的文件，便于管理和维护。

## 规则文件列表

### 1. 项目架构规则
- **文件**: `01-project-architecture.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 项目概述和技术栈要求
  - DDD 架构原则
  - 目录结构规范
  - 包命名规范

### 2. Support 包核心组件规则
- **文件**: `02-support-components.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - ApplicationService 应用服务基类
  - Data 数据传输对象基类
  - HasHooks 钩子机制
  - 仓库接口规范
  - 控制器动作Trait

### 3. 领域层规则
- **文件**: `03-domain-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 领域模型规范
  - 枚举规范
  - 值对象规范
  - 领域服务规范
  - 数据传输对象规范
  - 转换器规范

### 4. 应用层规则
- **文件**: `04-application-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 应用服务规范
  - 命令规范
  - 查询规范
  - 命令处理器规范
  - 查询处理器规范

### 5. 基础设施层规则
- **文件**: `05-infrastructure-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 仓库实现规范
  - 只读仓库实现规范
  - 过滤器配置规范
  - 排序配置规范
  - 查询构建器使用规范

### 6. 用户接口层规则
- **文件**: `06-ui-layer.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - 控制器规范
  - API资源规范
  - 请求验证规范
  - 路由定义规范
  - 中间件规范
  - 响应格式规范

### 7. 代码规范规则
- **文件**: `07-coding-standards.mdc`
- **适用范围**: 所有文件 (alwaysApply: true)
- **内容**: 
  - PHP 核心原则
  - PHP 8.4+ 特性使用
  - Laravel 最佳实践
  - 命名规范
  - 类型声明
  - 错误处理
  - 数据验证
  - 性能优化
  - 安全实践

### 8. 文档规范规则
- **文件**: `08-documentation.mdc`
- **适用范围**: 手动应用 (description: "文档规范和UML图标准")
- **内容**: 
  - 文档结构要求
  - 文档格式规范
  - 领域文档内容规范
  - UML图规范
  - 代码文档规范
  - 数据库设计文档
  - 文档维护规范

### 9. PHP 文件专用规则
- **文件**: `php-files.mdc`
- **适用范围**: PHP 文件 (globs: *.php)
- **内容**: 
  - PHP 文件基本要求
  - 命名空间和类规范
  - 严格类型声明
  - 类结构顺序
  - 类型声明
  - 错误处理
  - 注释规范
  - 代码格式

## 规则应用机制

### 自动应用规则
以下规则会自动应用到所有文件：
- 01-project-architecture.mdc
- 02-support-components.mdc
- 03-domain-layer.mdc
- 04-application-layer.mdc
- 05-infrastructure-layer.mdc
- 06-ui-layer.mdc
- 07-coding-standards.mdc

### 文件类型特定规则
- `php-files.mdc`: 只适用于 .php 文件

### 手动应用规则
- `08-documentation.mdc`: 需要手动应用，用于文档编写时

## 规则优化说明

### 原始问题
原来的规则文件存在以下问题：
1. 单个文件过大（超过2000行）
2. 内容混杂，难以维护
3. 不便于针对特定场景应用规则

### 优化方案
1. **按层级拆分**: 根据DDD架构的不同层级创建专门的规则文件
2. **按职责拆分**: 将项目架构、代码规范、文档规范等分离
3. **按应用场景拆分**: 创建全局规则、特定文件类型规则、手动应用规则
4. **保持一致性**: 所有规则文件遵循统一的格式和结构

### 优化效果
1. **易于维护**: 每个规则文件专注于特定领域
2. **灵活应用**: 可根据需要应用特定规则
3. **提高效率**: AI 可以更精准地应用相关规则
4. **便于扩展**: 新增规则时不会影响现有规则

## 使用建议

### 新建包时
重点关注：
- 01-project-architecture.mdc
- 02-support-components.mdc
- 03-domain-layer.mdc

### 编写业务逻辑时
重点关注：
- 04-application-layer.mdc
- 05-infrastructure-layer.mdc
- 06-ui-layer.mdc

### 代码审查时
重点关注：
- 07-coding-standards.mdc
- php-files.mdc

### 编写文档时
重点关注：
- 08-documentation.mdc

## 维护说明

### 规则更新
- 规则变更时，只需更新相关的特定文件
- 保持规则文件之间的一致性
- 定期检查规则的有效性

### 规则验证
- 新增规则后，验证其在实际项目中的应用效果
- 确保规则之间没有冲突
- 保持规则的简洁性和可理解性 