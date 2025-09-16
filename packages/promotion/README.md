# Red Jasmine Promotion Package

## 概述

Red Jasmine Promotion Package 是一个基于领域驱动设计（DDD）的电商活动管理包，支持多种活动类型的可扩展架构。

## 特性

- 🚀 **可扩展架构**: 基于策略模式，轻松添加新的活动类型
- 🎯 **类型安全**: 使用PHP 8.2+强类型和枚举
- 🔧 **灵活配置**: 每种活动类型都有独立的配置和规则
- 📊 **规则引擎**: 动态规则验证和价格计算
- 🎪 **钩子机制**: 提供丰富的扩展点
- 📈 **统计分析**: 内置活动数据统计功能

## 支持的活动类型

### 内置活动类型

- **秒杀活动 (Flash Sale)**: 限时限量抢购
- **拼团活动 (Group Buying)**: 多人成团享优惠
- **折扣活动 (Discount)**: 百分比或固定金额折扣
- **满减活动 (Full Reduction)**: 满额减免优惠
- **砍价活动 (Bargain)**: 邀请好友砍价
- **凑单活动 (Bundle)**: 组合商品优惠

### 扩展活动类型

通过实现 `ActivityTypeHandlerInterface` 接口，您可以轻松添加自定义活动类型，如：
- 抽奖活动
- 积分兑换
- 签到活动
- 邀请有礼
- 等等...

## 快速开始

### 安装

```bash
composer require red-jasmine/promotion
```

### 发布配置和迁移

```bash
php artisan vendor:publish --provider="RedJasmine\Promotion\PromotionPackageServiceProvider"
php artisan migrate
```

### 基本使用

#### 创建活动

```php
use RedJasmine\Promotion\Application\Services\Commands\ActivityCreateCommand;
use RedJasmine\Promotion\Domain\Facades\ActivityManager;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;

// 创建秒杀活动
$command = ActivityCreateCommand::from([
    'title' => '双11手机秒杀',
    'type' => ActivityTypeEnum::FLASH_SALE,
    'start_time' => now()->addHour(),
    'end_time' => now()->addHours(2),
    'rules' => [
        'max_participants' => 1000,
        'limit_per_user' => 1,
    ],
]);

$activity = ActivityManager::create($command);
```

#### 用户参与活动

```php
use RedJasmine\Promotion\Application\Services\Commands\ActivityParticipateCommand;

$participateCommand = ActivityParticipateCommand::from([
    'activity_id' => $activity->id,
    'user' => $user,
    'participation_data' => [
        'product_id' => 123,
        'quantity' => 1,
    ],
]);

$activityOrder = ActivityManager::participate($participateCommand);
```

#### 价格计算

```php
// 单个商品价格
$priceInfo = ActivityManager::calculateActivityPrice($activity, $productId, $quantity);

// 批量商品价格
$batchPriceInfo = ActivityManager::calculateBatchActivityPrice($activity, [
    ['product_id' => 123, 'quantity' => 2],
    ['product_id' => 456, 'quantity' => 1],
]);
```

## 架构设计

### 核心组件

1. **ActivityTypeHandlerInterface** - 活动类型处理器接口
2. **AbstractActivityTypeHandler** - 抽象处理器基类
3. **ActivityTypeHandlerFactory** - 处理器工厂
4. **ActivityApplicationService** - 活动应用服务
5. **ActivityRulesEngine** - 规则引擎

### 设计模式

- **策略模式**: 每种活动类型独立的处理策略
- **工厂模式**: 动态创建活动处理器
- **模板方法模式**: 定义通用流程，子类实现具体逻辑

## 扩展新活动类型

### 1. 创建活动类型处理器

```php
<?php

namespace App\Promotion\Handlers;

use RedJasmine\Promotion\Domain\Services\AbstractActivityTypeHandler;

class LotteryActivityHandler extends AbstractActivityTypeHandler
{
    public function getActivityType(): string
    {
        return 'lottery';
    }
    
    public function getExtensionFields(): array
    {
        return [
            'total_prizes' => 'integer',
            'winning_rate' => 'decimal:2',
            'prize_config' => 'json',
        ];
    }
    
    // 实现其他抽象方法...
}
```

### 2. 注册处理器

```php
// 在服务提供者中注册
ActivityTypeHandlerFactory::register('lottery', LotteryActivityHandler::class);
```

### 3. 添加到枚举

```php
// 在 ActivityTypeEnum 中添加
case LOTTERY = 'lottery';
```

## API文档

### 活动管理

- `ActivityManager::create($command)` - 创建活动
- `ActivityManager::update($command)` - 更新活动
- `ActivityManager::delete($activity)` - 删除活动
- `ActivityManager::find($query)` - 查找活动
- `ActivityManager::paginate($query)` - 分页查询活动

### 活动参与

- `ActivityManager::participate($command)` - 参与活动
- `ActivityManager::canParticipate($activity, $user, $data)` - 检查参与资格
- `ActivityManager::calculateActivityPrice($activity, $productId, $quantity)` - 计算价格

### 活动控制

- `ActivityManager::startActivity($activity)` - 开始活动
- `ActivityManager::endActivity($activity)` - 结束活动

### 配置获取

- `ActivityManager::getActivityTypeExtensionFields($type)` - 获取扩展字段
- `ActivityManager::getActivityTypeDefaultRules($type)` - 获取默认规则
- `ActivityManager::getRegisteredActivityTypes()` - 获取已注册类型

## 钩子和事件

### 活动类型钩子

```php
// 秒杀活动钩子
Hook::register('promotion.flash_sale.starting', function ($activity) {
    // 秒杀开始前的逻辑
});

// 拼团活动钩子
Hook::register('promotion.group_buying.participation', function ($activityOrder) {
    // 拼团参与后的逻辑
});
```

### 应用服务钩子

```php
Hook::register('promotion.activity.application.create', function ($activity) {
    // 活动创建后的逻辑
});
```

## 配置

### 活动配置

```php
// config/promotion.php
return [
    'default_activity_duration' => 24, // 默认活动时长（小时）
    'max_activity_duration' => 720,    // 最大活动时长（小时）
    'enable_cache' => true,             // 启用缓存
    'cache_ttl' => 3600,               // 缓存时间（秒）
];
```

## 测试

### 单元测试示例

```php
public function test_flash_sale_activity_creation()
{
    $command = ActivityCreateCommand::from([
        'title' => '测试秒杀',
        'type' => ActivityTypeEnum::FLASH_SALE,
        'start_time' => now()->addHour(),
        'end_time' => now()->addHours(2),
    ]);
    
    $activity = ActivityManager::create($command);
    
    $this->assertEquals('测试秒杀', $activity->title);
    $this->assertEquals(ActivityTypeEnum::FLASH_SALE, $activity->type);
}
```

## 贡献

欢迎提交 Pull Request 来添加新的活动类型或改进现有功能。

## 许可证

MIT License
