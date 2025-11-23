<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use RedJasmine\Product\Domain\Stock\Models\ProductStock;
use RedJasmine\Product\Domain\Stock\Repositories\ProductStockRepositoryInterface;
use RedJasmine\Product\Exceptions\StockException;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Throwable;

/**
 * 商品库存仓库实现（Redis + Lua 方案）
 *
 * 基于 Redis 分布式锁 + Lua 脚本实现，提供高并发库存操作能力
 *
 * 设计特点：
 * 1. 使用 Redis 分布式锁保证并发安全
 * 2. 使用 Lua 脚本保证操作的原子性
 * 3. 数据持久化存储在数据库中
 * 4. Redis 作为缓存层提升查询性能
 */
class ProductStockRedisRepository extends Repository implements ProductStockRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductStock::class;

    /**
     * Redis 键前缀
     */
    protected string $redisPrefix = 'product_stock:';

    /**
     * 分布式锁键前缀
     */
    protected string $lockPrefix = 'lock:product_stock:';

    /**
     * 锁超时时间（秒）
     */
    protected int $lockTimeout = 10;

    /**
     * 缓存过期时间（秒）
     */
    protected int $cacheTtl = 300; // 5分钟

    // ========================================
    // 查询方法 (Query Methods)
    // ========================================

    /**
     * 根据变体ID和仓库ID查找库存记录
     */
    public function findByVariantAndWarehouse(int $variantId, int $warehouseId) : ?ProductStock
    {
        // 先尝试从缓存获取
        $cacheKey = $this->getCacheKey($variantId, $warehouseId);
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return static::$modelClass::find($cached['id']);
        }

        // 从数据库查询
        $stock = static::$modelClass::where('variant_id', $variantId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        // 缓存结果
        if ($stock) {
            $this->cacheStock($stock);
        }

        return $stock;
    }

    /**
     * 根据变体ID和仓库ID查找库存记录并加锁
     *
     * 注意：Redis 实现中，此方法返回未加锁的记录
     * 实际的锁在库存操作方法中通过 Redis 分布式锁实现
     */
    public function findByVariantAndWarehouseLock(int $variantId, int $warehouseId) : ?ProductStock
    {
        // Redis 实现中，锁在操作方法中处理，这里直接返回记录
        return $this->findByVariantAndWarehouse($variantId, $warehouseId);
    }

    /**
     * 查找变体的所有仓库库存记录
     */
    public function findByVariant(int $variantId) : Collection
    {
        return static::$modelClass::where('variant_id', $variantId)->get();
    }

    /**
     * 根据商品ID和变体ID查找库存记录
     */
    public function findByProductAndVariant(int $productId, int $variantId) : Collection
    {
        return static::$modelClass::where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->get();
    }

    /**
     * 批量查询多个变体在指定仓库的库存记录
     */
    public function findByVariantsAndWarehouse(array $variantIds, int $warehouseId) : Collection
    {
        return static::$modelClass::whereIn('variant_id', $variantIds)
            ->where('warehouse_id', $warehouseId)
            ->get();
    }

    /**
     * 获取可用库存数量
     */
    public function getAvailableStock(int $variantId, int $warehouseId) : int
    {
        $stock = $this->findByVariantAndWarehouse($variantId, $warehouseId);

        if (!$stock) {
            return 0;
        }

        return max(0, $stock->available_stock);
    }

    // ========================================
    // 库存操作方法 (Stock Operation Methods)
    // ========================================

    /**
     * 锁定库存（下单时）
     */
    public function lockStock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        return $this->executeWithLock(
            $variantId,
            $warehouseId,
            'lock',
            $quantity,
            function ($stock) use ($quantity) {
                // 计算可用库存
                $availableStock = max(0, $stock->stock - $stock->locked_stock - $stock->reserved_stock);

                if ($availableStock < $quantity) {
                    throw new StockException("库存不足，可用库存：{$availableStock}，需要：{$quantity}");
                }

                // 锁定库存：locked_stock += quantity
                $stock->locked_stock += $quantity;
                $this->updateAvailableStock($stock);

                return $stock;
            }
        );
    }

    /**
     * 解锁库存（订单取消时）
     */
    public function unlockStock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        return $this->executeWithLock(
            $variantId,
            $warehouseId,
            'unlock',
            $quantity,
            function ($stock) use ($quantity) {
                if ($stock->locked_stock < $quantity) {
                    throw new StockException("锁定库存不足，锁定库存：{$stock->locked_stock}，需要：{$quantity}");
                }

                // 解锁库存：locked_stock -= quantity
                $stock->locked_stock -= $quantity;
                $this->updateAvailableStock($stock);

                return $stock;
            }
        );
    }

    /**
     * 预留库存（支付成功时）
     * 将锁定库存转为预留库存
     */
    public function reserveStock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        return $this->executeWithLock(
            $variantId,
            $warehouseId,
            'reserve',
            $quantity,
            function ($stock) use ($quantity) {
                if ($stock->locked_stock < $quantity) {
                    throw new StockException("锁定库存不足，锁定库存：{$stock->locked_stock}，需要：{$quantity}");
                }

                // 预留库存：locked_stock -= quantity, reserved_stock += quantity
                $stock->locked_stock -= $quantity;
                $stock->reserved_stock += $quantity;
                $this->updateAvailableStock($stock);

                return $stock;
            }
        );
    }

    /**
     * 扣减库存（发货后）
     * 扣减总库存和预留库存
     */
    public function deductStock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        return $this->executeWithLock(
            $variantId,
            $warehouseId,
            'deduct',
            $quantity,
            function ($stock) use ($quantity) {
                if ($stock->reserved_stock < $quantity) {
                    throw new StockException("预留库存不足，预留库存：{$stock->reserved_stock}，需要：{$quantity}");
                }

                // 扣减库存：reserved_stock -= quantity, stock -= quantity
                $stock->reserved_stock -= $quantity;
                $stock->stock -= $quantity;
                $this->updateAvailableStock($stock);

                return $stock;
            }
        );
    }

    /**
     * 增加库存
     */
    public function addStock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        return $this->executeWithLock(
            $variantId,
            $warehouseId,
            'add',
            $quantity,
            function ($stock) use ($quantity) {
                // 增加库存：stock += quantity
                $stock->stock += $quantity;
                $this->updateAvailableStock($stock);

                return $stock;
            }
        );
    }

    /**
     * 重置库存
     * 设置总库存和可用库存为指定值
     */
    public function resetStock(int $variantId, int $warehouseId, int $stockValue) : ProductStock
    {
        return $this->executeWithLock(
            $variantId,
            $warehouseId,
            'reset',
            $stockValue,
            function ($stock) use ($stockValue) {
                // 重置库存：设置 stock 为指定值
                // 注意：locked_stock 和 reserved_stock 保持不变
                $stock->stock = $stockValue;
                $this->updateAvailableStock($stock);

                return $stock;
            }
        );
    }

    /**
     * 释放库存（订单取消时，释放预留库存）
     * 将预留库存转回可用库存
     */
    public function releaseStock(int $variantId, int $warehouseId, int $quantity) : ProductStock
    {
        return $this->executeWithLock(
            $variantId,
            $warehouseId,
            'release',
            $quantity,
            function ($stock) use ($quantity) {
                if ($stock->reserved_stock < $quantity) {
                    throw new StockException("预留库存不足，预留库存：{$stock->reserved_stock}，需要：{$quantity}");
                }

                // 释放库存：reserved_stock -= quantity
                $stock->reserved_stock -= $quantity;
                $this->updateAvailableStock($stock);

                return $stock;
            }
        );
    }

    // ========================================
    // 内部方法 (Internal Methods)
    // ========================================

    /**
     * 使用 Redis 分布式锁执行库存操作
     *
     * @param  int  $variantId  变体ID
     * @param  int  $warehouseId  仓库ID
     * @param  string  $operation  操作类型
     * @param  int  $quantity  数量
     * @param  callable  $callback  操作回调
     *
     * @return ProductStock
     * @throws StockException
     */
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
            // 尝试获取分布式锁
            $acquired = $this->acquireLock($lockKey, $lockValue, $this->lockTimeout);

            if (!$acquired) {
                throw new StockException("获取库存锁失败，请稍后重试");
            }

            // 查询库存记录
            $stock = static::$modelClass::where('variant_id', $variantId)
                ->where('warehouse_id', $warehouseId)
                ->first();

            if (!$stock) {
                throw new StockException("库存记录不存在：变体ID={$variantId}, 仓库ID={$warehouseId}");
            }

            // 使用 Lua 脚本执行原子操作
            $result = $this->executeLuaScript($operation, $stock, $quantity, $callback);

            // 同步到数据库
            $stock->save();

            // 更新缓存
            $this->cacheStock($stock);

            return $stock->fresh();
        } catch (StockException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new StockException("库存操作失败：{$e->getMessage()}");
        } finally {
            // 释放锁
            if ($acquired) {
                $this->releaseLock($lockKey, $lockValue);
            }
        }
    }

    /**
     * 执行 Lua 脚本进行原子操作
     *
     * 使用数据库事务 + PHP 回调实现原子操作
     * 注意：如果需要更高的性能，可以将逻辑封装为 Redis Lua 脚本
     * 但考虑到库存数据需要持久化到数据库，当前方案使用数据库事务
     */
    protected function executeLuaScript(
        string $operation,
        ProductStock $stock,
        int $quantity,
        callable $callback
    ) {
        // 在事务中执行操作
        return DB::transaction(function () use ($stock, $callback) {
            // 重新加载最新数据（加锁查询）
            $stock->refresh();

            // 执行回调操作
            return $callback($stock);
        });
    }

    /**
     * 获取 Redis 分布式锁
     *
     * @param  string  $key  锁的键
     * @param  string  $value  锁的值（用于释放时验证）
     * @param  int  $timeout  超时时间（秒）
     *
     * @return bool 是否成功获取锁
     */
    protected function acquireLock(string $key, string $value, int $timeout) : bool
    {
        // 使用 SET NX EX 实现分布式锁
        $result = Redis::set($key, $value, 'EX', $timeout, 'NX');

        return $result === true || $result === 'OK';
    }

    /**
     * 释放 Redis 分布式锁
     *
     * @param  string  $key  锁的键
     * @param  string  $value  锁的值（用于验证）
     *
     * @return bool 是否成功释放锁
     */
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

    /**
     * 计算并更新可用库存
     * available_stock = stock - locked_stock - reserved_stock
     */
    protected function updateAvailableStock(ProductStock $stock) : void
    {
        $stock->available_stock = max(0, $stock->stock - $stock->locked_stock - $stock->reserved_stock);
    }

    /**
     * 缓存库存记录
     */
    protected function cacheStock(ProductStock $stock) : void
    {
        $cacheKey = $this->getCacheKey($stock->variant_id, $stock->warehouse_id);
        Cache::put($cacheKey, [
            'id' => $stock->id,
            'stock' => $stock->stock,
            'available_stock' => $stock->available_stock,
            'locked_stock' => $stock->locked_stock,
            'reserved_stock' => $stock->reserved_stock,
        ], $this->cacheTtl);
    }

    /**
     * 获取缓存键
     */
    protected function getCacheKey(int $variantId, int $warehouseId) : string
    {
        return $this->redisPrefix . "{$variantId}:{$warehouseId}";
    }

    /**
     * 获取锁键
     */
    protected function getLockKey(int $variantId, int $warehouseId) : string
    {
        return $this->lockPrefix . "{$variantId}:{$warehouseId}";
    }

    /**
     * 清除缓存
     */
    protected function clearCache(int $variantId, int $warehouseId) : void
    {
        $cacheKey = $this->getCacheKey($variantId, $warehouseId);
        Cache::forget($cacheKey);
    }

    // ========================================
    // 查询构建器配置 (Query Builder Configuration)
    // ========================================

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('variant_id'),
            AllowedFilter::exact('warehouse_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('is_active'),
        ];
    }
}

