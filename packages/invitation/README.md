# 邀请领域包 (Invitation Package)

## 概述

邀请领域包是一个基于Laravel的完整邀请营销解决方案，支持邀请码管理、邀请链接生成、多平台适配、统计分析等功能。

## 功能特性

### 🎯 核心功能
- ✅ 邀请码生成与管理
- ✅ 多平台邀请链接生成
- ✅ 邀请去向配置
- ✅ 使用记录跟踪
- ✅ 统计数据分析
- ✅ 标签分类管理

### 📊 支持平台
- Web网页版
- H5移动端
- 小程序
- 原生APP

### 🔧 技术特点
- DDD领域驱动设计
- CQRS命令查询分离
- 事件驱动架构
- 充血模型设计
- 完整的仓储模式

## 安装

```bash
composer require red-jasmine/invitation
```

## 配置

发布配置文件：
```bash
php artisan vendor:publish --tag=invitation-config
```

发布数据库迁移：
```bash
php artisan vendor:publish --tag=invitation-migrations
```

运行数据库迁移：
```bash
php artisan migrate
```

## 基本使用

### 创建邀请码

```php
use RedJasmine\Invitation\Application\Data\InvitationCodeCreateCommand;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;

$service = app(InvitationCodeApplicationService::class);

$command = new InvitationCodeCreateCommand(
    inviterType: 'user',
    inviterId: '123',
    inviterName: '张三',
    title: '邀请好友注册',
    description: '邀请好友注册送好礼',
    destinations: [
        [
            'destinationType' => 'register',
            'platformType' => 'web',
            'isDefault' => true,
        ]
    ]
);

$invitationCode = $service->create($command);
```

### 使用邀请码

```php
$invitationCode = $service->useCode('ABC123');
```

### 生成邀请链接

```php
$link = $service->generateLink('ABC123', 'h5', [
    'utm_source' => 'wechat',
    'utm_campaign' => 'spring_festival'
]);
```

## API接口

### 创建邀请码
```http
POST /api/invitation/codes
Content-Type: application/json

{
    "inviterType": "user",
    "inviterId": "123",
    "inviterName": "张三",
    "title": "邀请好友注册",
    "description": "邀请好友注册送好礼",
    "generateType": "system",
    "maxUsage": 100,
    "expiresAt": "2024-12-31 23:59:59",
    "destinations": [
        {
            "destinationType": "register",
            "platformType": "web",
            "isDefault": true
        }
    ]
}
```

### 查看邀请码详情
```http
GET /api/invitation/codes/{code}
```

### 使用邀请码
```http
POST /api/invitation/codes/{code}/use
```

### 生成邀请链接
```http
POST /api/invitation/codes/{code}/link
Content-Type: application/json

{
    "platform": "h5",
    "parameters": {
        "utm_source": "wechat"
    }
}
```

## 配置说明

主要配置项：

```php
// config/invitation.php

return [
    'code' => [
        'default_length' => 8,           // 默认邀请码长度
        'custom_min_length' => 4,        // 自定义邀请码最小长度
        'custom_max_length' => 20,       // 自定义邀请码最大长度
        'charset' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',  // 生成字符集
        'generate_retry_times' => 3,     // 生成重试次数
        'default_expires_days' => 30,    // 默认有效期（天）
        'expire_grace_hours' => 24,      // 过期宽限期（小时）
    ],
    
    'link' => [
        'domains' => [
            'web' => 'https://example.com',
            'h5' => 'https://m.example.com',
            'miniprogram' => 'https://mp.example.com',
            'app' => 'https://app.example.com',
        ],
    ],
    
    'cache' => [
        'ttl' => [
            'invitation_code' => 3600,   // 邀请码缓存时间
            'invitation_link' => 1800,   // 邀请链接缓存时间
        ],
    ],
];
```

## 扩展开发

### 自定义邀请码生成器

```php
use RedJasmine\Invitation\Infrastructure\Services\InvitationCodeGenerator;

class CustomCodeGenerator extends InvitationCodeGenerator
{
    public function generate(): string
    {
        // 自定义生成逻辑
        return 'CUSTOM' . time();
    }
}

// 在服务提供者中绑定
$this->app->bind(InvitationCodeGenerator::class, CustomCodeGenerator::class);
```

### 监听领域事件

```php
// 监听邀请码创建事件
Event::listen(InvitationCodeCreated::class, function ($event) {
    // 发送通知、更新缓存等
});
```

## 许可证

MIT License 