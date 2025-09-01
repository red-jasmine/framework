# 活动类型扩展指南

## 概述

本指南介绍如何为promotion包添加新的活动类型，以及如何使用现有的可扩展架构。

## 架构设计

### 核心组件

1. **ActivityTypeHandlerInterface** - 活动类型处理器接口
2. **AbstractActivityTypeHandler** - 抽象活动类型处理器基类
3. **ActivityTypeHandlerFactory** - 活动处理器工厂
4. **ActivityApplicationService** - 活动应用服务
5. **ActivityRulesEngine** - 活动规则引擎

### 设计模式

- **策略模式**: 每种活动类型都有独立的处理策略
- **工厂模式**: 通过工厂创建对应的活动处理器
- **模板方法模式**: 抽象基类定义通用流程，子类实现具体逻辑

## 如何添加新的活动类型

### 1. 定义活动类型枚举

```php
// 在 ActivityTypeEnum 中添加新类型
enum ActivityTypeEnum: string
{
    // ... 现有类型
    case LOTTERY = 'lottery';        // 抽奖活动
    case POINTS_EXCHANGE = 'points_exchange'; // 积分兑换
    
    public static function labels(): array
    {
        return [
            // ... 现有标签
            self::LOTTERY->value => '抽奖活动',
            self::POINTS_EXCHANGE->value => '积分兑换',
        ];
    }
}
```

### 2. 创建活动类型处理器

```php
<?php

namespace RedJasmine\Promotion\Domain\Services\ActivityTypeHandlers;

use RedJasmine\Promotion\Domain\Services\AbstractActivityTypeHandler;

class LotteryActivityHandler extends AbstractActivityTypeHandler
{
    public static string $hookNamePrefix = 'promotion.lottery';
    
    public function getActivityType(): string
    {
        return ActivityTypeEnum::LOTTERY->value;
    }
    
    public function getExtensionFields(): array
    {
        return [
            'total_prizes' => 'integer',      // 总奖品数
            'winning_rate' => 'decimal:2',    // 中奖率
            'max_attempts' => 'integer',      // 最大抽奖次数
            'prize_config' => 'json',         // 奖品配置
        ];
    }
    
    public function getDefaultRules(): array
    {
        return array_merge(parent::getDefaultRules(), [
            'user_participation_limit' => 3, // 每人最多3次
            'winning_rate' => 10.0,          // 默认10%中奖率
        ]);
    }
    
    // 实现抽象方法
    protected function validateSpecificData(ActivityData $data): void
    {
        // 抽奖活动特定验证逻辑
    }
    
    protected function validateSpecificParticipation(Activity $activity, UserInterface $user, array $participationData): void
    {
        // 抽奖参与条件验证
    }
    
    protected function calculateActivityPrice(Activity $activity, ActivityProduct $activityProduct, int $quantity, array $context): float
    {
        // 抽奖活动价格计算（可能是免费或固定价格）
        return $activityProduct->activity_price ?? 0;
    }
    
    protected function executeParticipationLogic(Activity $activity, ActivityOrder $activityOrder, array $participationData): void
    {
        // 执行抽奖逻辑
        $isWinner = $this->drawLottery($activity);
        $prize = $isWinner ? $this->selectPrize($activity) : null;
        
        $activityOrder->update([
            'is_winner' => $isWinner,
            'prize_info' => $prize,
        ]);
    }
    
    // ... 其他抽象方法实现
    
    private function drawLottery(Activity $activity): bool
    {
        $winningRate = $activity->rules['winning_rate'] ?? 10.0;
        return (rand(1, 10000) / 100) <= $winningRate;
    }
    
    private function selectPrize(Activity $activity): ?array
    {
        $prizeConfig = $activity->rules['prize_config'] ?? [];
        // 根据概率选择奖品
        return $prizeConfig[array_rand($prizeConfig)] ?? null;
    }
}
```

### 3. 注册新的活动类型处理器

```php
// 在 PromotionPackageServiceProvider 中注册
protected function registerActivityTypeHandlers(): void
{
    ActivityTypeHandlerFactory::register('lottery', LotteryActivityHandler::class);
    ActivityTypeHandlerFactory::register('points_exchange', PointsExchangeActivityHandler::class);
}
```

## 使用示例

### 创建秒杀活动

```php
use RedJasmine\Promotion\Application\Services\Commands\ActivityCreateCommand;
use RedJasmine\Promotion\Domain\Facades\ActivityManager;

$command = ActivityCreateCommand::from([
    'title' => '新年秒杀活动',
    'type' => ActivityTypeEnum::FLASH_SALE,
    'start_time' => now()->addHour(),
    'end_time' => now()->addHours(2),
    'rules' => [
        'max_participants' => 100,
        'limit_per_user' => 1,
    ],
]);

$activity = ActivityManager::create($command);
```

### 创建拼团活动

```php
$command = ActivityCreateCommand::from([
    'title' => '年货拼团',
    'type' => ActivityTypeEnum::GROUP_BUYING,
    'start_time' => now(),
    'end_time' => now()->addDays(7),
    'rules' => [
        'min_group_size' => 3,
        'max_group_size' => 10,
        'group_timeout' => 48,
        'leader_discount' => 5.0,
    ],
]);

$activity = ActivityManager::create($command);
```

### 用户参与活动

```php
use RedJasmine\Promotion\Application\Services\Commands\ActivityParticipateCommand;

// 参与秒杀
$participateCommand = ActivityParticipateCommand::from([
    'activity_id' => $activity->id,
    'user' => $user,
    'participation_data' => [
        'product_id' => 123,
        'quantity' => 1,
    ],
]);

$activityOrder = ActivityManager::participate($participateCommand);

// 开团拼团
$participateCommand = ActivityParticipateCommand::from([
    'activity_id' => $groupActivity->id,
    'user' => $user,
    'participation_data' => [
        'product_id' => 456,
        'quantity' => 2,
        'is_leader' => true,
    ],
]);

$activityOrder = ActivityManager::participate($participateCommand);
```

### 计算活动价格

```php
// 单个商品价格计算
$priceInfo = ActivityManager::calculateActivityPrice($activity, $productId, $quantity);
// 返回: ['original_price' => 100, 'activity_price' => 80, 'discount_amount' => 20]

// 批量商品价格计算
$batchPriceInfo = ActivityManager::calculateBatchActivityPrice($activity, [
    ['product_id' => 123, 'quantity' => 2],
    ['product_id' => 456, 'quantity' => 1],
]);
```

### 检查用户参与资格

```php
$canParticipate = ActivityManager::canParticipate($activity, $user, $participationData);

if (!$canParticipate) {
    $reason = ActivityManager::getParticipationFailureReason($activity, $user, $participationData);
    echo "无法参与活动: {$reason}";
}
```

## 扩展点和钩子

### 活动类型处理器钩子

每个活动类型处理器都支持钩子机制，可以在关键节点插入自定义逻辑：

```php
// 注册钩子
Hook::register('promotion.flash_sale.starting', function ($activity) {
    // 秒杀开始前的自定义逻辑
    Log::info("秒杀活动 {$activity->title} 即将开始");
});

Hook::register('promotion.group_buying.participation', function ($activityOrder) {
    // 拼团参与后的自定义逻辑
    event(new GroupParticipationEvent($activityOrder));
});
```

### 应用服务钩子

```php
Hook::register('promotion.activity.application.create', function ($activity) {
    // 活动创建后的自定义逻辑
    Cache::tags(['activities'])->flush();
});
```

## 最佳实践

### 1. 活动类型处理器设计

- 继承 `AbstractActivityTypeHandler` 获得通用功能
- 实现所有抽象方法以提供类型特定逻辑
- 使用钩子机制提供扩展点
- 合理设计扩展字段和默认规则

### 2. 规则配置

- 将活动类型特定的配置放在 `getExtensionFields()` 中
- 提供合理的默认规则
- 支持规则的动态验证和类型检查

### 3. 价格计算

- 考虑不同活动类型的价格计算逻辑
- 支持批量计算和上下文传递
- 处理边界情况（如最大折扣限制）

### 4. 参与逻辑

- 分离验证和执行逻辑
- 使用数据库事务确保数据一致性
- 合理处理并发参与的情况

### 5. 性能优化

- 使用缓存减少数据库查询
- 合理设计数据库索引
- 避免N+1查询问题

## 总结

通过这个可扩展架构，您可以：

1. **轻松添加新活动类型** - 只需实现对应的处理器
2. **灵活配置活动规则** - 每种类型都有自己的扩展字段
3. **统一的接口调用** - 通过应用服务和门面提供一致的API
4. **强大的扩展能力** - 通过钩子机制支持自定义逻辑
5. **类型安全** - 使用强类型和枚举确保代码安全

这个架构遵循了DDD原则，保持了良好的职责分离，同时提供了足够的灵活性来支持各种电商活动场景。

## 基于ServiceManager的增强功能

### 新架构优势

从 v2.0 开始，`ActivityTypeHandlerFactory` 基于 `RedJasmine\Support\Helpers\Services\ServiceManager` 重构，提供了更强大的功能：

1. **配置管理**: 每个处理器都可以有独立的配置参数
2. **懒加载**: 处理器实例按需创建，提高性能
3. **单例模式**: 避免重复创建实例
4. **自定义创建器**: 支持闭包方式注册处理器
5. **容器集成**: 完全集成Laravel容器的依赖注入功能

### 配置文件支持

```php
// config/promotion.php
return [
    // 处理器配置
    'activity_handlers' => [
        'flash_sale' => [
            'max_duration' => 24 * 60, // 最大活动时长（分钟）
            'min_stock' => 1,          // 最小库存要求
            'enable_queue' => true,    // 是否启用队列处理
        ],
        'group_buying' => [
            'max_group_size' => 50,    // 最大成团人数
            'default_timeout' => 24,   // 默认成团超时时间（小时）
            'enable_auto_refund' => true, // 启用自动退款
        ],
    ],
    
    // 自定义处理器
    'custom_activity_handlers' => [
        'lottery' => \App\Promotion\Handlers\LotteryActivityHandler::class,
        'points_exchange' => \App\Promotion\Handlers\PointsExchangeActivityHandler::class,
    ],
];
```

### 高级注册方式

```php
// 1. 基本注册
ActivityTypeHandlerFactory::register('lottery', LotteryActivityHandler::class);

// 2. 闭包注册（支持复杂创建逻辑）
ActivityTypeHandlerFactory::register('custom_type', function ($config) {
    $logger = app('log');
    $cache = app('cache');
    
    return new CustomActivityHandler($config, $logger, $cache);
});

// 3. 批量注册
ActivityTypeHandlerFactory::registerMany([
    'lottery' => LotteryActivityHandler::class,
    'points_exchange' => PointsExchangeActivityHandler::class,
]);

// 4. 配置处理器
ActivityTypeHandlerFactory::setHandlerConfig('flash_sale', [
    'max_duration' => 120,
    'enable_queue' => true,
]);
```

### 配置访问

处理器可以在构造函数中接收配置参数：

```php
class LotteryActivityHandler extends AbstractActivityTypeHandler
{
    protected array $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    protected function getMaxPrizes(): int
    {
        return $this->config['max_prizes'] ?? 100;
    }
    
    protected function isQueueEnabled(): bool
    {
        return $this->config['enable_queue'] ?? false;
    }
}
```

### 管理和监控

```php
// 获取所有已注册的处理器类型
$types = ActivityTypeHandlerFactory::getRegisteredTypes();

// 检查处理器是否已注册
$isRegistered = ActivityTypeHandlerFactory::isRegistered('lottery');

// 获取处理器配置
$config = ActivityTypeHandlerFactory::getHandlerConfig('flash_sale');

// 获取所有扩展字段
$fields = ActivityTypeHandlerFactory::getAllExtensionFields();

// 获取所有默认规则
$rules = ActivityTypeHandlerFactory::getAllDefaultRules();
```

### 测试支持

```php
// 在测试中重置工厂状态
ActivityTypeHandlerFactory::resetInstance();

// 注册测试专用的处理器
ActivityTypeHandlerFactory::register('test_type', function ($config) {
    return new MockActivityHandler($config);
});
```

这些增强功能使得活动类型处理器的管理更加灵活和强大，同时保持了向后兼容性。
