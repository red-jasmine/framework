# 库存仓库 Redis + Lua 实现方案

## 概述

`ProductStockRedisRepository` 是基于 Redis 分布式锁 + Lua 脚本的库存仓库实现，提供高并发库存操作能力。

## 设计特点

### 1. Redis 分布式锁
- 使用 `SET NX EX` 实现分布式锁
- 锁超时时间可配置（默认 10 秒）
- 使用 Lua 脚本保证释放锁的原子性

### 2. 数据持久化
- 库存数据存储在 MySQL 数据库中
- Redis 作为缓存层提升查询性能
- 操作完成后同步更新缓存

### 3. 原子性保证
- 使用数据库事务保证操作的原子性
- Redis 分布式锁保证并发安全
- 操作完成后立即更新缓存

## 架构对比

### 数据库锁方案（ProductStockRepository）
```
请求 → 数据库行锁（lockForUpdate）→ 执行操作 → 提交事务
```
- **优点**：实现简单，数据一致性强
- **缺点**：高并发下数据库压力大，性能瓶颈明显

### Redis + Lua 方案（ProductStockRedisRepository）
```
请求 → Redis 分布式锁 → 数据库事务 → 执行操作 → 更新缓存 → 释放锁
```
- **优点**：高并发性能好，减少数据库压力
- **缺点**：实现复杂度较高，需要维护缓存一致性

## 使用方法

### 1. 服务提供者绑定

在 `ProductPackageServiceProvider` 中绑定接口实现：

```php
// 使用数据库锁方案（默认）
$this->app->bind(
    ProductStockRepositoryInterface::class,
    ProductStockRepository::class
);

// 或使用 Redis + Lua 方案（高并发场景）
$this->app->bind(
    ProductStockRepositoryInterface::class,
    ProductStockRedisRepository::class
);
```

### 2. 配置 Redis 连接

确保 `config/database.php` 中配置了 Redis 连接：

```php
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    'default' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
    ],
],
```

### 3. 环境变量配置

```env
# Redis 配置
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_DB=0
```

## 核心实现

### 1. 分布式锁获取

```php
protected function acquireLock(string $key, string $value, int $timeout) : bool
{
    // 使用 SET NX EX 实现分布式锁
    $result = Redis::set($key, $value, 'EX', $timeout, 'NX');
    
    return $result === true || $result === 'OK';
}
```

### 2. 分布式锁释放

```php
protected function releaseLock(string $key, string $value) : bool
{
    // 使用 Lua 脚本保证释放锁的原子性
    $lua = "
        if redis.call('get', KEYS[1]) == ARGV[1] then
            return redis.call('del', KEYS[1])
        else
            return 0
        end
    ";
    
    $result = Redis::eval($lua, 1, $key, $value);
    
    return $result === 1;
}
```

### 3. 库存操作流程

```php
protected function executeWithLock(
    int $variantId,
    int $warehouseId,
    string $operation,
    int $quantity,
    callable $callback
) : ProductStock {
    $lockKey = $this->getLockKey($variantId, $warehouseId);
    $lockValue = uniqid('', true);
    $acquired = false;

    try {
        // 1. 获取分布式锁
        $acquired = $this->acquireLock($lockKey, $lockValue, $this->lockTimeout);
        
        if (!$acquired) {
            throw new StockException("获取库存锁失败，请稍后重试");
        }

        // 2. 查询库存记录
        $stock = static::$modelClass::where('variant_id', $variantId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if (!$stock) {
            throw new StockException("库存记录不存在");
        }

        // 3. 执行操作（数据库事务保证原子性）
        $result = $this->executeLuaScript($operation, $stock, $quantity, $callback);

        // 4. 同步到数据库
        $stock->save();

        // 5. 更新缓存
        $this->cacheStock($stock);

        return $stock->fresh();
    } finally {
        // 6. 释放锁
        if ($acquired) {
            $this->releaseLock($lockKey, $lockValue);
        }
    }
}
```

## 缓存策略

### 缓存键格式
```
product_stock:{variant_id}:{warehouse_id}
```

### 缓存内容
```php
[
    'id' => $stock->id,
    'stock' => $stock->stock,
    'available_stock' => $stock->available_stock,
    'locked_stock' => $stock->locked_stock,
    'reserved_stock' => $stock->reserved_stock,
]
```

### 缓存过期时间
- 默认 TTL：300 秒（5 分钟）
- 操作后立即更新缓存

## 性能优化建议

### 1. 锁超时时间调整
根据业务场景调整锁超时时间：
```php
protected int $lockTimeout = 10; // 秒
```

### 2. 缓存预热
在系统启动时预热热点商品的库存缓存：
```php
// 预热热门商品库存
$hotProducts = Product::where('is_hot', true)->get();
foreach ($hotProducts as $product) {
    foreach ($product->variants as $variant) {
        $this->findByVariantAndWarehouse($variant->id, 0);
    }
}
```

### 3. 批量操作优化
对于批量操作，可以考虑使用 Redis Pipeline：
```php
$pipeline = Redis::pipeline();
foreach ($operations as $operation) {
    $pipeline->set($key, $value);
}
$pipeline->execute();
```

## 注意事项

### 1. 缓存一致性
- 库存操作后必须更新缓存
- 缓存失效时从数据库重新加载
- 考虑使用缓存版本号或时间戳

### 2. 锁超时处理
- 锁超时后自动释放，避免死锁
- 操作时间应小于锁超时时间
- 考虑实现锁续期机制

### 3. 异常处理
- 操作失败时确保释放锁
- 使用 try-finally 保证锁释放
- 记录操作日志便于排查

### 4. 数据一致性
- 使用数据库事务保证操作原子性
- Redis 锁只保证并发控制，不保证数据一致性
- 最终一致性通过数据库保证

## 未来优化方向

### 1. 纯 Redis 存储方案
如果性能要求极高，可以考虑将库存数据完全存储在 Redis 中：
- 使用 Redis Hash 存储库存数据
- 使用 Lua 脚本实现原子操作
- 定期同步到数据库

### 2. 分布式锁优化
- 使用 Redlock 算法实现更可靠的分布式锁
- 实现锁续期机制
- 监控锁竞争情况

### 3. 缓存策略优化
- 使用多级缓存（本地缓存 + Redis）
- 实现缓存预热和失效策略
- 监控缓存命中率

## 总结

`ProductStockRedisRepository` 提供了高并发的库存操作能力，适合高并发场景。通过 Redis 分布式锁和缓存机制，可以有效减少数据库压力，提升系统性能。

选择建议：
- **低并发场景**：使用 `ProductStockRepository`（数据库锁方案）
- **高并发场景**：使用 `ProductStockRedisRepository`（Redis + Lua 方案）

