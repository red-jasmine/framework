# 商品列表批量价格查询方案

## 需求分析

在商品列表查询时，需要根据市场（market）、门店（store）、用户等级（user_level）等维度，快速批量查询出每个商品的基础价格。

## 方案设计

### 1. 核心思路

- **批量查询**：一次性查询多个商品的价格，避免 N+1 查询问题
- **维度匹配**：根据市场、门店、用户等级匹配最佳价格
- **回退机制**：如果没有匹配的多维度价格，回退到变体的基准价格
- **性能优化**：使用索引和合理的查询策略

### 2. 实现层次

#### 2.1 仓库层（Repository Layer）

在 `ProductPriceRepositoryInterface` 中添加批量查询方法：

```php
/**
 * 批量查询商品价格（根据市场、门店、用户等级）
 * 
 * @param array<int> $productIds 商品ID数组
 * @param array<int> $variantIds 变体ID数组（可选，如果提供则只查询这些变体）
 * @param string $market 市场
 * @param string $store 门店
 * @param string $userLevel 用户等级
 * @return Collection<ProductPrice> 价格集合，key为 "product_id-variant_id"
 */
public function findBatchPrices(
    array $productIds,
    ?array $variantIds = null,
    string $market = '*',
    string $store = '*',
    string $userLevel = '*'
): Collection;
```

**实现要点**：
- 使用 `whereIn` 批量查询
- 使用 `byDimensions` 作用域匹配维度
- 按优先级和匹配度排序
- 返回结果以 `product_id-variant_id` 为 key，便于快速查找

#### 2.2 领域服务层（Domain Service Layer）

在 `ProductPriceDomainService` 中添加批量获取价格方法：

```php
/**
 * 批量获取商品价格
 * 
 * @param Collection<Product> $products 商品集合
 * @param string $market 市场
 * @param string $store 门店
 * @param string $userLevel 用户等级
 * @param bool $useDefaultVariant 是否只查询默认变体（true：只查询默认变体，false：查询所有变体）
 * @return array<string, ProductPrice|null> key为 "product_id-variant_id"，value为价格对象或null
 */
public function getBatchPrices(
    Collection $products,
    string $market = '*',
    string $store = '*',
    string $userLevel = '*',
    bool $useDefaultVariant = true
): array;
```

**实现逻辑**：
1. 收集所有需要查询的商品ID和变体ID
2. 调用仓库批量查询价格
3. 对没有匹配到价格的变体，回退到变体的基准价格
4. 返回价格映射表

#### 2.3 应用服务层（Application Service Layer）

在 `PriceApplicationService` 中添加批量查询方法：

```php
/**
 * 批量获取商品价格（便捷方法）
 * 
 * @param Collection<Product> $products 商品集合
 * @param string $market 市场
 * @param string $store 门店
 * @param string $userLevel 用户等级
 * @param bool $useDefaultVariant 是否只查询默认变体
 * @return array<string, ProductPrice|null>
 */
public function getBatchPrices(
    Collection $products,
    string $market = '*',
    string $store = '*',
    string $userLevel = '*',
    bool $useDefaultVariant = true
): array;
```

### 3. 使用场景

#### 场景1：商品列表查询（只查询默认变体价格）

```php
// 在商品列表查询处理器中
$products = $this->repository->paginate($query);

// 批量查询价格
$priceService = app(PriceApplicationService::class);
$prices = $priceService->getBatchPrices(
    $products->items(),
    market: 'cn',
    store: '*',
    userLevel: 'default',
    useDefaultVariant: true
);

// 将价格附加到商品
foreach ($products->items() as $product) {
    $defaultVariant = $product->getDefaultVariant();
    $priceKey = "{$product->id}-{$defaultVariant->id}";
    $product->setAttribute('current_price', $prices[$priceKey] ?? null);
}
```

#### 场景2：商品列表查询（查询所有变体价格）

```php
$prices = $priceService->getBatchPrices(
    $products->items(),
    market: 'cn',
    store: '*',
    userLevel: 'default',
    useDefaultVariant: false
);

// 将价格附加到变体
foreach ($products->items() as $product) {
    foreach ($product->variants as $variant) {
        $priceKey = "{$product->id}-{$variant->id}";
        $variant->setAttribute('current_price', $prices[$priceKey] ?? null);
    }
}
```

### 4. 性能优化

#### 4.1 查询优化

- **索引优化**：确保 `product_prices` 表有合适的索引
  - `idx_product_dimensions` (product_id, market, store, user_level)
  - `idx_product_variant` (product_id, variant_id)
  
- **批量查询**：使用 `whereIn` 一次性查询，避免循环查询

- **关联预加载**：在查询商品时，预加载变体关系
  ```php
  $products = Product::with('variants')->paginate($query);
  ```

#### 4.2 缓存策略（可选）

对于热门商品，可以考虑缓存价格：
- 缓存 key：`product_price:{product_id}:{variant_id}:{market}:{store}:{user_level}`
- 缓存时间：根据业务需求设置（如5分钟）

### 5. 数据返回格式

#### 5.1 价格映射表

```php
[
    "123-456" => ProductPrice,  // 商品ID-变体ID
    "123-457" => ProductPrice,
    "124-458" => null,           // 没有匹配到价格，回退到变体基准价格
]
```

#### 5.2 商品对象扩展

可以通过以下方式将价格附加到商品：

```php
// 方式1：附加到商品属性
$product->setAttribute('current_price', $price);

// 方式2：附加到变体属性
$variant->setAttribute('current_price', $price);

// 方式3：使用访问器
$product->current_price = $price;
```

### 6. 实现步骤

1. **第一步**：在 `ProductPriceRepositoryInterface` 添加 `findBatchPrices` 方法
2. **第二步**：在 `ProductPriceRepository` 实现批量查询逻辑
3. **第三步**：在 `ProductPriceDomainService` 添加 `getBatchPrices` 方法
4. **第四步**：在 `PriceApplicationService` 添加便捷方法
5. **第五步**：在商品列表查询处理器中使用批量查询

### 7. 注意事项

1. **变体选择**：如果商品有多个变体，需要明确查询哪个变体的价格（默认变体或所有变体）
2. **价格回退**：如果没有匹配到多维度价格，应该回退到变体的基准价格
3. **空值处理**：某些商品可能没有变体或价格，需要妥善处理
4. **性能监控**：监控批量查询的性能，确保不会成为瓶颈

## 总结

该方案通过批量查询的方式，避免了 N+1 查询问题，提高了商品列表查询的性能。同时保持了价格匹配的灵活性，支持多维度价格查询和回退机制。

