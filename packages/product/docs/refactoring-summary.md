# 价格表重构总结

## 重构概述

将原来的 `product_prices` 表拆分为两个表：
1. **`product_variant_prices`** - 变体价格表（原 `product_prices`）
2. **`product_prices`** - 商品级价格汇总表（新增）

## 已完成的工作

### 1. 数据库迁移
- ✅ 创建迁移：重命名 `product_prices` 为 `product_variant_prices`
- ✅ 创建迁移：创建新的 `product_prices` 汇总表

### 2. 模型层
- ✅ 创建 `ProductVariantPrice` 模型（变体价格）
- ✅ 重构 `ProductPrice` 模型（商品级价格汇总）
- ✅ 更新 `Product` 模型关联：`prices()` 和 `variantPrices()`
- ✅ 更新 `ProductVariant` 模型关联：`prices()`

### 3. 仓库层
- ✅ 创建 `ProductVariantPriceRepositoryInterface` 和实现
- ✅ 创建 `ProductPriceRepositoryInterface`（商品级价格汇总）和实现
- ✅ 更新 `PriceMatcher` 使用 `ProductVariantPrice`

### 4. 领域服务层
- ✅ 更新 `ProductPriceDomainService`：
  - 使用 `ProductVariantPriceRepository` 查询变体价格
  - 使用 `ProductPriceRepository` 查询商品级价格汇总
  - 添加 `getBatchProductPrices()` 方法（商品列表用）
  - 保留 `getBatchPrices()` 方法（商品详情用）

### 5. 应用服务层
- ✅ 更新 `PriceApplicationService`：
  - 添加 `getBatchProductPrices()` 方法
  - 更新 `getPrice()` 返回类型为 `ProductVariantPrice`

### 6. 查询处理器
- ✅ 更新 `UserProductListQueryHandler`：
  - 使用 `getBatchProductPrices()` 查询商品级价格汇总
  - 不再预加载变体
  - 附加价格汇总信息到商品

### 7. 命令处理器
- ✅ 更新 `ProductPriceBulkCreateCommandHandler` 使用 `ProductVariantPrice`

### 8. 服务提供者
- ✅ 更新 `ProductApplicationServiceProvider` 注册新仓库
- ✅ 更新 `ProductPackageServiceProvider` 迁移文件列表

## 还需要更新的文件

### 1. 命令处理器（需要更新使用 ProductVariantPrice）
- [ ] `ProductPriceCreateCommandHandler.php`
- [ ] `ProductPriceUpdateCommandHandler.php`
- [ ] `ProductPriceDeleteCommandHandler.php`

### 2. 查询处理器（需要更新返回类型）
- [ ] `ProductPriceListQueryHandler.php` - 应该查询 `ProductVariantPrice`
- [ ] `GetProductPriceQueryHandler.php` - 返回类型应为 `ProductVariantPrice`

### 3. 数据对象（可能需要更新）
- [ ] `ProductPriceCommandData.php` - 检查是否需要更新
- [ ] `ProductPriceData.php` - 检查是否需要更新

### 4. Filament 管理界面（需要更新）
- [ ] `ProductPriceResource.php` - 应该管理 `ProductVariantPrice`
- [ ] `ProductPriceForm.php` - 更新模型引用
- [ ] `ProductPriceTable.php` - 更新模型引用
- [ ] 相关页面类

### 5. 商品级价格汇总服务（需要创建）
- [ ] 创建 `ProductLevelPriceService`：
  - 计算商品级价格汇总
  - 监听变体价格变化事件
  - 自动更新汇总表

## 使用说明

### 商品列表查询（使用汇总表）
```php
$query = UserProductListQuery::from([
    'market' => 'cn',
    'store' => '*',
    'userLevel' => 'vip',
]);

$service = app(ProductApplicationService::class);
$products = $service->userProductList($query);

// 每个商品都有价格汇总信息
foreach ($products->items() as $product) {
    $price = $product->current_price; // ProductPrice 对象
    $avgPrice = $product->avg_price; // 均价
    $minPrice = $product->min_price; // 最低价
    $maxPrice = $product->max_price; // 最高价
}
```

### 商品详情查询（使用变体价格表）
```php
$priceData = ProductPriceData::from([
    'productId' => 1,
    'skuId' => 123,
    'market' => 'cn',
    'store' => '*',
    'userLevel' => 'vip',
]);

$service = app(PriceApplicationService::class);
$price = $service->getPrice($priceData); // ProductVariantPrice 对象
```

## 下一步工作

1. **创建商品级价格汇总服务**：实现自动计算和更新汇总表
2. **更新剩余的命令和查询处理器**
3. **更新 Filament 管理界面**
4. **添加数据迁移脚本**：为现有数据计算初始汇总价格
5. **添加单元测试**

## 注意事项

1. **数据一致性**：变体价格变化时需要同步更新汇总表
2. **实时性**：汇总表是预计算的，可能存在延迟
3. **回退机制**：如果汇总表没有数据，可以回退到变体价格计算

