# 邀请领域包

红茉莉框架的邀请领域包，提供完整的邀请码和邀请链接功能。

## 特性

- 🎯 **邀请码管理** - 支持自定义和系统生成两种邀请码类型
- 🔒 **使用控制** - 支持使用次数限制和过期时间控制
- 🔗 **邀请链接** - 支持生成可配置跳转目标的邀请链接
- 📊 **邀请统计** - 提供完整的邀请效果统计和分析
- 📝 **邀请记录** - 详细记录邀请关系和使用情况
- 🔐 **安全机制** - 内置签名验证和防滥用机制

## 安装

```bash
composer require red-jasmine/invitation
```

## 配置

发布配置文件：

```bash
php artisan vendor:publish --tag=invitation-config
```

发布迁移文件：

```bash
php artisan vendor:publish --tag=invitation-migrations
```

运行迁移：

```bash
php artisan migrate
```

## 使用示例

### 创建邀请码

```php
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeTypeEnum;
use RedJasmine\Support\Data\UserData;

$service = app(InvitationCodeApplicationService::class);

// 创建系统生成的邀请码
$invitationCode = $service->create(InvitationCodeData::from([
    'owner' => UserData::from(['type' => 'user', 'id' => 1]),
    'codeType' => InvitationCodeTypeEnum::SYSTEM,
    'maxUsage' => 100,
    'expiredAt' => now()->addDays(30),
    'description' => '推广活动邀请码',
]));

// 创建自定义邀请码
$customCode = $service->create(InvitationCodeData::from([
    'owner' => UserData::from(['type' => 'user', 'id' => 1]),
    'code' => 'WELCOME2024',
    'codeType' => InvitationCodeTypeEnum::CUSTOM,
    'maxUsage' => 50,
    'description' => '欢迎新用户邀请码',
]));
```

### 使用邀请码

```php
use RedJasmine\Invitation\Domain\Data\UseInvitationCodeData;

// 使用邀请码
$record = $service->use(UseInvitationCodeData::from([
    'code' => 'ABC12345',
    'invitee' => UserData::from(['type' => 'user', 'id' => 2]),
    'context' => [
        'source' => 'web',
        'ip' => '127.0.0.1',
        'device' => 'desktop',
    ],
    'targetUrl' => 'https://example.com/register',
    'targetType' => 'register',
]));
```

### 生成邀请链接

```php
// 生成邀请链接
$invitationUrl = $invitationCode->generateInvitationUrl(
    'https://example.com/register',
    'register'
);

echo $invitationUrl;
// 输出：https://example.com/invitation?code=ABC12345&target=https%3A//example.com/register&type=register&t=1640995200&sig=abc123...
```

### 查询和统计

```php
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Support\Application\Queries\PaginateQuery;

$readRepository = app(InvitationCodeReadRepositoryInterface::class);

// 分页查询邀请码
$codes = $readRepository->paginate(PaginateQuery::from([
    'page' => 1,
    'perPage' => 10,
    'filter' => [
        'owner_type' => 'user',
        'owner_id' => 1,
        'status' => 'active',
    ],
]));

// 获取邀请统计
$stats = $readRepository->getInvitationStats(1, 'user');
// 返回：['total_codes' => 5, 'active_codes' => 3, 'total_usage' => 25, ...]

// 获取热门邀请码
$popularCodes = $readRepository->getPopularCodes(10);
```

## 配置说明

### 邀请码生成配置

```php
'code_generation' => [
    'length' => 8,                    // 邀请码长度
    'characters' => 'ABCD...0123...',  // 字符集
    'prefix' => '',                   // 前缀
    'suffix' => '',                   // 后缀
    'retry_times' => 10,              // 生成重试次数
],
```

### 邀请链接配置

```php
'link' => [
    'base_url' => env('APP_URL'),              // 基础URL
    'path' => '/invitation',                   // 邀请链接路径
    'signature_key' => env('APP_KEY'),         // 签名密钥
    'link_ttl' => 3600 * 24 * 7,              // 链接有效期
    'target_types' => [                       // 支持的目标类型
        'register' => '注册页面',
        'product' => '商品页面',
        'activity' => '活动页面',
        'custom' => '自定义页面',
    ],
],
```

### 安全配置

```php
'security' => [
    'rate_limit' => 60,           // 使用频率限制（每分钟）
    'ip_daily_limit' => 100,      // IP每日使用限制
    'device_daily_limit' => 50,   // 设备每日使用限制
    'enable_signature' => true,   // 启用签名验证
],
```

## 领域事件

系统内置了以下领域事件：

- `InvitationCodeCreated` - 邀请码创建事件
- `InvitationCodeUsed` - 邀请码使用事件
- `InvitationCompleted` - 邀请完成事件

可以通过监听这些事件来实现自定义逻辑，如奖励发放、通知发送等。

## 架构设计

本包采用领域驱动设计（DDD）架构：

- **领域层** - 包含核心业务逻辑和领域模型
- **应用层** - 编排业务用例和流程
- **基础设施层** - 数据持久化和外部集成
- **用户接口层** - API接口和用户交互

## 许可证

MIT License 