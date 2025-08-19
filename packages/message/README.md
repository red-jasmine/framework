# Red Jasmine Message Domain Package

## 概述

消息领域包是Red Jasmine Framework中的核心业务领域之一，提供统一、高效、可扩展的消息管理和推送功能。

## 主要功能

- **消息管理**: 消息的创建、发送、查询、阅读、归档等完整生命周期管理
- **消息分类**: 支持多层级的消息分类体系，便于消息的组织和管理
- **消息模板**: 提供可视化的模板编辑和变量替换功能
- **多渠道推送**: 统一管理APP内消息、推送通知、邮件、短信等多种推送渠道
- **推送日志**: 详细记录推送过程，支持状态跟踪和问题排查
- **权限控制**: 基于业务线和用户的细粒度权限控制
- **频率限制**: 防止消息骚扰的智能频率控制机制

## 技术架构

基于领域驱动设计(DDD)架构，包含以下层次：

- **领域层(Domain)**: 核心业务逻辑和领域模型
- **应用层(Application)**: 应用服务和业务编排
- **基础设施层(Infrastructure)**: 数据持久化和外部服务集成
- **用户接口层(UI)**: RESTful API和Web界面

## 核心实体

### 消息聚合 (Message Aggregate)
- **消息实体**: 消息的核心信息和状态管理
- **消息内容值对象**: 标题、内容、附件等内容信息
- **推送配置值对象**: 推送渠道、参数、重试配置等
- **消息数据值对象**: 业务数据和模板变量值

### 消息分类聚合 (MessageCategory Aggregate)
- **消息分类实体**: 分类信息和层级管理

### 消息模板聚合 (MessageTemplate Aggregate)
- **消息模板实体**: 模板内容和变量管理
- **模板变量值对象**: 变量定义和验证规则

### 推送日志聚合 (MessagePushLog Aggregate)
- **推送日志实体**: 推送过程的详细记录
- **推送结果值对象**: 推送状态和响应信息
- **错误信息值对象**: 错误详情和处理建议

## 数据库表结构

- `messages`: 消息主表
- `message_categories`: 消息分类表
- `message_templates`: 消息模板表
- `message_push_logs`: 推送日志表

## 安装使用

### 1. 安装包
```bash
composer require red-jasmine/message
```

### 2. 发布配置文件
```bash
php artisan vendor:publish --tag=message-config
```

### 3. 运行数据库迁移
```bash
php artisan migrate
```

### 4. 发布语言文件（可选）
```bash
php artisan vendor:publish --tag=message-lang
```

## 配置说明

主要配置项包括：

- **推送渠道配置**: 配置不同推送渠道的启用状态和参数
- **业务线配置**: 定义支持的业务线类型
- **消息类型配置**: 定义支持的消息类型
- **优先级配置**: 定义消息优先级等级
- **模板引擎配置**: 配置模板渲染引擎
- **队列配置**: 配置消息队列处理
- **缓存配置**: 配置消息缓存策略
- **频率限制配置**: 配置消息发送频率限制
- **归档配置**: 配置消息自动归档策略

## API接口

### 用户端接口
- `GET /api/user/messages` - 获取消息列表
- `GET /api/user/messages/{id}` - 获取消息详情
- `PATCH /api/user/messages/{id}/read` - 标记消息已读
- `PATCH /api/user/messages/batch-read` - 批量标记已读
- `GET /api/user/messages/unread-count` - 获取未读数量

### 管理端接口
- `GET /api/admin/messages` - 管理消息列表
- `POST /api/admin/messages` - 发送消息
- `GET /api/admin/message-categories` - 管理消息分类
- `POST /api/admin/message-categories` - 创建消息分类
- `GET /api/admin/message-templates` - 管理消息模板
- `POST /api/admin/message-templates` - 创建消息模板

### 商家端接口
- `GET /api/shop/messages` - 商家消息列表
- `POST /api/shop/messages` - 发送商家消息

## 使用示例

### 发送消息

```php
use RedJasmine\Message\Application\Services\Message\MessageApplicationService;

$messageService = app(MessageApplicationService::class);

$command = new MessageSendCommand([
    'biz' => 'user',
    'receiver_id' => '12345',
    'title' => '订单支付成功',
    'content' => '您的订单已支付成功，订单号：ORD123456',
    'type' => 'notification',
    'priority' => 'normal',
    'channels' => ['in_app', 'push'],
]);

$message = $messageService->send($command);
```

### 查询消息
```php
use RedJasmine\Message\Application\Services\Queries\MessagePaginateQuery;

$query = new MessagePaginateQuery([
    'receiver_id' => '12345',
    'status' => 'unread',
    'page' => 1,
    'per_page' => 20,
]);

$messages = $messageService->paginate($query);
```

### 标记已读
```php
use RedJasmine\Message\Application\Services\Commands\MessageReadCommand;

$command = new MessageReadCommand([
    'id' => $messageId,
    'reader_id' => '12345',
]);

$messageService->read($command);
```

## 扩展开发

### 自定义推送渠道
1. 实现推送渠道接口
2. 注册到服务容器
3. 配置渠道参数

### 自定义消息模板引擎
1. 实现模板引擎接口
2. 配置模板引擎类
3. 扩展模板变量处理

### 自定义业务规则
1. 创建领域服务
2. 实现业务规则逻辑
3. 注册到钩子系统

## 许可证

MIT License

## 贡献

欢迎提交Issue和Pull Request来帮助改进这个包。

## 支持

如果您在使用过程中遇到问题，请通过以下方式获取支持：

- 查看文档
- 提交Issue
- 加入社区讨论
