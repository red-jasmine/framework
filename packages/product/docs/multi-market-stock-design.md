# 商品多市场库存体系设计方案

## 文档信息

| 项目 | 内容 |
|------|------|
| **方案名称** | 商品多市场库存体系设计方案 |
| **方案版本** | v1.0 |
| **创建日期** | 2024-12-19 |
| **适用范围** | Red Jasmine Framework - Product Domain |
| **文档状态** | 📝 设计阶段 |

---

## 一、方案概述

### 1.1 设计目标

在现有库存体系基础上，增加**多市场、多门店**的库存管理能力，支持：
- ✅ 共享库存模式：所有市场/门店共享同一份库存
- ✅ 独立库存模式：每个市场/门店独立库存分配
- ✅ 混合模式：部分市场共享，部分市场独立
- ✅ 门店维度：支持默认门店和具体门店的库存分配
- ✅ 向后兼容：保留现有库存字段，作为基准库存

### 1.2 设计原则

```
核心原则：

✅ 基准库存：product_variants 表保留总库存（所有市场共享库存或默认市场库存）
✅ 多市场库存：product_stocks 表管理不同市场、门店的库存分配
✅ 库存分配策略：支持共享库存、独立库存两种模式，并可叠加门店维度
✅ 库存扣减：支持按市场/门店精细扣减库存，支持库存锁定和释放
✅ 库存同步：支持库存自动同步和手动分配
✅ 向后兼容：现有库存操作逻辑保持不变，新增多市场库存作为扩展
```

---

## 二、数据库表结构设计

### 2.1 product_stocks 表（新建）

**表名：** `product_stocks`

**表说明：** 商品多市场库存表，管理不同市场、门店的变体级别库存分配

**重要说明：** 库存管理均为变体级别，不支持商品级别库存。

```sql
CREATE TABLE product_stocks (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    variant_id BIGINT UNSIGNED NOT NULL COMMENT 'SKU ID（变体ID，必填）',
    
    -- ========== 库存维度 ==========
    market VARCHAR(64) NOT NULL COMMENT '市场：cn, us, de, *（*表示共享库存）',
    store VARCHAR(64) NOT NULL DEFAULT '*' COMMENT '门店：default-默认门店，store_xxx-具体门店，* 表示门店共享',
    
    -- ========== 库存分配模式 ==========
    allocation_mode VARCHAR(32) DEFAULT 'shared' COMMENT '分配模式：shared-共享库存, independent-独立库存',
    
    -- ========== 库存数量 ==========
    total_stock BIGINT DEFAULT 0 COMMENT '总库存（独立模式下为该市场分配的总库存）',
    available_stock BIGINT DEFAULT 0 COMMENT '可用库存',
    locked_stock BIGINT DEFAULT 0 COMMENT '锁定库存（已下单未支付）',
    reserved_stock BIGINT DEFAULT 0 COMMENT '预留库存（已支付待发货）',
    sold_stock BIGINT DEFAULT 0 COMMENT '已售库存',
    safety_stock BIGINT DEFAULT 0 COMMENT '安全库存',
    
    -- ========== 库存限制 ==========
    min_stock BIGINT DEFAULT 0 COMMENT '最小库存（低于此值触发补货提醒）',
    max_stock BIGINT DEFAULT 0 COMMENT '最大库存（独立模式下该市场的最大库存）',
    
    -- ========== 库存状态 ==========
    is_active TINYINT(1) DEFAULT 1 COMMENT '是否启用',
    is_tracked TINYINT(1) DEFAULT 1 COMMENT '是否跟踪库存',
    stock_status VARCHAR(32) DEFAULT 'in_stock' COMMENT '库存状态：in_stock-有货, low_stock-低库存, out_of_stock-缺货',
    
    -- ========== 库存同步 ==========
    last_synced_at TIMESTAMP NULL COMMENT '最后同步时间',
    sync_mode VARCHAR(32) DEFAULT 'auto' COMMENT '同步模式：auto-自动同步, manual-手动同步',
    
    -- ========== 操作信息 ==========
    creator_type VARCHAR(64) NULL COMMENT '创建者类型',
    creator_id VARCHAR(64) NULL COMMENT '创建者ID',
    creator_nickname VARCHAR(64) NULL COMMENT '创建者昵称',
    updater_type VARCHAR(64) NULL COMMENT '更新者类型',
    updater_id VARCHAR(64) NULL COMMENT '更新者ID',
    updater_nickname VARCHAR(64) NULL COMMENT '更新者昵称',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- ========== 索引 ==========
    UNIQUE KEY uk_variant_market_store (variant_id, market, store),
    INDEX idx_product_variant (product_id, variant_id),
    INDEX idx_variant_market (variant_id, market),
    INDEX idx_variant_market_store (variant_id, market, store),
    INDEX idx_product_market (product_id, market),
    INDEX idx_stock_status (stock_status),
    INDEX idx_allocation_mode (allocation_mode),
    
    COMMENT='商品-多市场库存表（变体级别）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.2 现有表字段说明（保持不变）

**products 表：**
- `stock` - 商品级别总库存（统计用，所有SKU库存汇总，仅用于统计展示）
- `channel_stock` - 渠道库存（保留，用于渠道活动）
- `lock_stock` - 锁定库存（统计用）
- `safety_stock` - 安全库存

**product_variants 表：**
- `stock` - SKU级别总库存（基准库存，所有市场共享或默认市场库存）
- `channel_stock` - SKU渠道库存（保留）
- `lock_stock` - SKU锁定库存（基准锁定库存）
- `safety_stock` - SKU安全库存

**重要说明：** 
- 库存管理均为**变体级别**，不支持商品级别库存
- `product_stocks` 表的 `variant_id` 字段为必填（NOT NULL）
- 现有 `product_variants.stock` 字段作为基准库存保留
- `product_stocks` 表作为多市场库存的扩展，管理变体在不同市场/门店的库存分配

---

## 三、核心处理逻辑

### 3.1 库存查询逻辑

#### **3.1.1 获取可用库存**

```
查询流程（变体级别）：

1. 查询 product_stocks 表，匹配条件：
   - variant_id = {variantId}（必填，变体ID）
   - market = {market} 或 '*'
   - store = {store} 或 'default' 或 '*'

2. 匹配优先级（从高到低）：
   a) 精确匹配：variant_id + market + store（具体门店）
   b) 门店回退：variant_id + market + 'default'（默认门店）
   c) 门店通配：variant_id + market + '*'（门店共享）
   d) 市场通配：variant_id + '*' + store（市场共享）
   e) 全局共享：variant_id + '*' + '*'（全局共享库存）

3. 根据 allocation_mode 处理：
   - shared（共享库存）：返回共享库存记录的 available_stock
   - independent（独立库存）：返回该市场/门店的 available_stock

4. 回退机制：
   - 如果 product_stocks 表中没有匹配记录，回退到 product_variants.stock
   - 如果 product_variants.stock 也没有，返回 0

注意：所有库存操作都是变体级别，variant_id 为必填参数。
```

#### **3.1.2 库存状态计算**

```
库存状态判断：

1. 获取可用库存：available_stock
2. 获取安全库存：safety_stock
3. 判断逻辑：
   - available_stock <= 0 → out_of_stock（缺货）
   - available_stock <= safety_stock → low_stock（低库存）
   - available_stock > safety_stock → in_stock（有货）

4. 更新 stock_status 字段
```

### 3.2 库存分配逻辑

#### **3.2.1 共享库存模式**

```
共享库存配置：

1. 创建共享库存记录：
   - market = '*'
   - store = '*'
   - allocation_mode = 'shared'
   - total_stock = {总库存}
   - available_stock = {总库存}

2. 各市场/门店记录：
   - market = {具体市场}
   - store = {具体门店} 或 'default' 或 '*'
   - allocation_mode = 'shared'
   - total_stock = 0（不存储实际库存）
   - available_stock = 共享库存的 available_stock（显示值，自动同步）

3. 库存扣减：
   - 扣减 market='*', store='*' 的 available_stock
   - 自动同步所有使用共享库存的市场/门店记录的 available_stock
```

#### **3.2.2 独立库存模式**

```
独立库存配置：

1. 为每个市场/门店创建独立库存记录：
   - market = {具体市场}
   - store = {具体门店} 或 'default'
   - allocation_mode = 'independent'
   - total_stock = {分配的总库存}
   - available_stock = {分配的总库存}

2. 库存扣减：
   - 只扣减对应市场/门店的 available_stock
   - 其他市场/门店不受影响

3. 库存分配：
   - 从 product_variants.stock（基准库存）中分配
   - 确保：sum(各市场 total_stock) <= product_variants.stock
```

#### **3.2.3 混合模式**

```
混合模式配置：

1. 部分市场共享库存：
   - market='*', store='*' → 共享库存记录
   - market='cn', store='*' → 使用共享库存（allocation_mode='shared'）

2. 部分市场独立库存：
   - market='us', store='default' → 独立库存（allocation_mode='independent'）
   - market='us', store='store_la01' → 独立库存（allocation_mode='independent'）

3. 库存扣减：
   - 共享库存：扣减共享记录，同步所有使用共享的市场
   - 独立库存：只扣减对应市场/门店的记录
```

### 3.3 库存扣减流程

#### **3.3.1 锁定库存（下单时）**

```
锁定流程：

1. 查询可用库存（按 3.1.1 逻辑）
2. 检查可用库存是否充足
3. 锁定库存：
   - available_stock = available_stock - quantity
   - locked_stock = locked_stock + quantity
4. 如果是共享库存，同步所有使用共享的市场/门店记录
5. 记录库存日志
```

#### **3.3.2 预留库存（支付成功）**

```
预留流程：

1. 查询锁定库存记录
2. 预留库存：
   - locked_stock = locked_stock - quantity
   - reserved_stock = reserved_stock + quantity
3. 如果是共享库存，同步所有使用共享的市场/门店记录
4. 记录库存日志
```

#### **3.3.3 扣减库存（发货后）**

```
扣减流程：

1. 查询预留库存记录
2. 扣减库存：
   - reserved_stock = reserved_stock - quantity
   - sold_stock = sold_stock + quantity
   - 如果是独立库存：total_stock = total_stock - quantity
   - 如果是共享库存：total_stock = total_stock - quantity（共享记录）
3. 如果是共享库存，同步所有使用共享的市场/门店记录
4. 更新 product_variants.stock（基准库存）
5. 更新 products.stock（商品级别统计）
6. 记录库存日志
```

#### **3.3.4 释放库存（订单取消）**

```
释放流程：

1. 判断订单状态：
   - 未支付：释放 locked_stock
   - 已支付：释放 reserved_stock

2. 释放库存：
   - available_stock = available_stock + quantity
   - locked_stock = locked_stock - quantity（未支付）
   - reserved_stock = reserved_stock - quantity（已支付）

3. 如果是共享库存，同步所有使用共享的市场/门店记录
4. 记录库存日志
```

### 3.4 库存同步逻辑

#### **3.4.1 共享库存自动同步**

```
同步触发时机：

1. 共享库存变更时（锁定、预留、扣减、释放）
2. 定时任务同步（可选）

同步逻辑：

1. 查询共享库存记录（market='*', store='*'）
2. 查询所有使用共享库存的市场/门店记录：
   - allocation_mode = 'shared'
   - (market != '*' OR store != '*')
3. 更新这些记录的 available_stock = 共享库存的 available_stock
4. 更新这些记录的 stock_status（按 3.1.2 逻辑）
```

#### **3.4.2 库存分配（独立库存模式）**

```
分配流程：

1. 检查基准库存：product_variants.stock
2. 计算已分配库存：sum(product_stocks.total_stock WHERE allocation_mode='independent')
3. 计算可分配库存：product_variants.stock - 已分配库存
4. 分配库存到指定市场/门店：
   - 创建或更新 product_stocks 记录
   - allocation_mode = 'independent'
   - total_stock = total_stock + quantity
   - available_stock = available_stock + quantity
5. 验证：确保 sum(各市场 total_stock) <= product_variants.stock
```

### 3.5 库存查询服务接口设计

#### **3.5.1 核心方法**

```
ProductStockService 核心方法（变体级别）：

注意：所有方法都要求 variantId 为必填参数，库存管理均为变体级别。

1. getAvailableStock(variantId, market, store)
   → 获取变体可用库存
   → variantId: 必填，变体ID

2. lockStock(variantId, market, store, quantity)
   → 锁定变体库存
   → variantId: 必填，变体ID

3. unlockStock(variantId, market, store, quantity)
   → 解锁变体库存
   → variantId: 必填，变体ID

4. reserveStock(variantId, market, store, quantity)
   → 预留变体库存
   → variantId: 必填，变体ID

5. deductStock(variantId, market, store, quantity)
   → 扣减变体库存
   → variantId: 必填，变体ID

6. releaseStock(variantId, market, store, quantity, orderStatus)
   → 释放变体库存
   → variantId: 必填，变体ID

7. allocateStockToMarket(variantId, market, store, quantity)
   → 分配变体库存到市场（独立库存模式）
   → variantId: 必填，变体ID

8. syncSharedStock(variantId)
   → 同步变体共享库存
   → variantId: 必填，变体ID

9. getStockStatus(variantId, market, store)
   → 获取变体库存状态
   → variantId: 必填，变体ID
```

### 3.6 与现有代码集成点

#### **3.6.1 现有库存操作改造**

```
改造策略：

1. 保持现有接口不变（向后兼容）
2. 在现有库存操作中增加市场/门店参数（可选）
3. 如果未指定市场/门店，使用默认值（'default'）
4. 优先查询 product_stocks 表，如果没有记录，回退到现有逻辑

改造点：

1. StockApplicationService：
   - lock() → 增加 market, store 参数（可选）
   - unlock() → 增加 market, store 参数（可选）
   - confirm() → 增加 market, store 参数（可选）

2. ProductSkuRepository：
   - lock() → 调用 ProductStockService.lockStock()
   - unlock() → 调用 ProductStockService.unlockStock()
   - confirm() → 调用 ProductStockService.reserveStock()

3. StockCommand：
   - 增加 market, store 字段（可选，默认 'default'）
```

#### **3.6.2 库存查询改造**

```
改造策略：

1. 查询时优先使用 product_stocks 表
2. 如果没有多市场库存记录，回退到 product_variants.stock
3. 保持现有查询接口不变

改造点：

1. FindSkuStockQuery：
   - 增加 market, store 参数（可选）
   - 查询逻辑改为调用 ProductStockService.getAvailableStock()

2. ProductStockPaginateQuery：
   - 支持按 market, store 过滤
   - 支持按 allocation_mode 过滤
```

---

## 四、数据示例

### 4.1 共享库存模式示例

```
商品：iPhone 15 Pro
SKU ID：10001
总库存：1000件（存放在 product_variants.stock）

product_stocks 表记录：

| id | product_id | variant_id | market | store | allocation_mode | total_stock | available_stock | locked_stock |
|----|-----------|-----------|--------|-------|----------------|-------------|----------------|--------------|
| 1  | 1001      | 10001     | *      | *     | shared         | 1000        | 950            | 0            |
| 2  | 1001      | 10001     | cn     | default | shared       | 0           | 950            | 0            |
| 3  | 1001      | 10001     | us     | default | shared       | 0           | 950            | 0            |
| 4  | 1001      | 10001     | cn     | store_sh01 | shared    | 0           | 950            | 0            |

说明：
- 记录1是共享库存主记录，存储实际库存
- 记录2-4是显示记录，available_stock 自动同步自记录1
- 中国市场售出50件 → 记录1的 available_stock 变为 900，记录2-4自动同步为 900
```

### 4.2 独立库存模式示例

```
商品：定制T恤
SKU ID：10002
总库存：1000件（存放在 product_variants.stock）

product_stocks 表记录：

| id | product_id | variant_id | market | store | allocation_mode | total_stock | available_stock | locked_stock |
|----|-----------|-----------|--------|-------|----------------|-------------|----------------|--------------|
| 5  | 1002      | 10002     | cn     | default | independent   | 600         | 550            | 0            |
| 6  | 1002      | 10002     | cn     | store_sz01 | independent | 200         | 180            | 0            |
| 7  | 1002      | 10002     | us     | default | independent   | 300         | 300            | 0            |
| 8  | 1002      | 10002     | us     | store_la01 | independent | 120         | 120            | 0            |

说明：
- 中国市场分配600件，其中默认门店600件，深圳门店200件（从600件中分配）
- 美国市场分配300件，其中默认门店300件，洛杉矶门店120件（从300件中分配）
- 中国市场售出50件 → 只影响记录5，记录7-8不受影响
```

### 4.3 混合模式示例

```
商品：限量版球鞋
SKU ID：10003
总库存：500件（存放在 product_variants.stock）

product_stocks 表记录：

| id | product_id | variant_id | market | store | allocation_mode | total_stock | available_stock | locked_stock |
|----|-----------|-----------|--------|-------|----------------|-------------|----------------|--------------|
| 9  | 1003      | 10003     | *      | *     | shared         | 200         | 200            | 0            |
| 10 | 1003      | 10003     | us     | default | shared       | 0           | 200            | 0            |
| 11 | 1003      | 10003     | de     | default | shared       | 0           | 200            | 0            |
| 12 | 1003      | 10003     | cn     | default | independent   | 300         | 300            | 0            |

说明：
- 中国市场：独立库存300件
- 美国+欧洲市场：共享库存200件
- 中国市场售出50件 → 只影响记录12
- 美国市场售出30件 → 影响记录9、10、11（共享库存同步）
```

---

## 五、实施步骤

### Phase 1: 数据库表创建（Week 1）

**任务：**
1. 创建 `product_stocks` 表迁移文件
2. 创建 `StockStatusEnum` 枚举类
3. 创建 `AllocationModeEnum` 枚举类
4. 数据库迁移测试

### Phase 2: 领域模型创建（Week 1-2）

**任务：**
1. 创建 `ProductStock` 模型
2. 创建 `ProductStockRepositoryInterface` 接口
3. 创建 `ProductStockRepository` 实现
4. 创建 `ProductStockService` 领域服务

### Phase 3: 核心逻辑实现（Week 2-3）

**任务：**
1. 实现库存查询逻辑（getAvailableStock）
2. 实现库存锁定逻辑（lockStock）
3. 实现库存扣减逻辑（deductStock）
4. 实现库存同步逻辑（syncSharedStock）
5. 实现库存分配逻辑（allocateStockToMarket）

### Phase 4: 集成现有代码（Week 3-4）

**任务：**
1. 改造 `StockApplicationService`，支持市场/门店参数
2. 改造 `StockCommand`，增加 market, store 字段
3. 改造 `ProductSkuRepository`，调用新的库存服务
4. 保持向后兼容，未指定市场/门店时使用默认值

### Phase 5: 测试和优化（Week 4-5）

**任务：**
1. 单元测试
2. 集成测试
3. 性能测试
4. 文档编写

---

## 六、注意事项

### 6.1 向后兼容

1. **现有库存字段保留**：`product_variants.stock` 作为基准库存保留（变体级别）
2. **变体级别库存**：所有库存操作都是变体级别，`variant_id` 为必填参数
3. **默认值处理**：未指定 market/store 时，使用 'default'
4. **回退机制**：如果 `product_stocks` 表没有记录，回退到 `product_variants.stock`

### 6.2 性能优化

1. **索引优化**：
   - 唯一索引：`uk_variant_market_store (variant_id, market, store)` - 确保变体在不同市场/门店的唯一性
   - 查询索引：`idx_variant_market_store (variant_id, market, store)` - 优化变体库存查询
   - 辅助索引：`idx_product_variant (product_id, variant_id)` - 支持按商品查询变体库存
2. **查询缓存**：库存查询结果缓存（Redis，TTL=5分钟），缓存 key 格式：`product_stock:{variant_id}:{market}:{store}`
3. **批量操作**：支持批量查询和更新变体库存

### 6.3 数据一致性

1. **事务保证**：所有库存操作在事务中执行
2. **锁机制**：使用数据库行锁（lockForUpdate）防止并发问题
3. **同步机制**：共享库存变更时自动同步所有相关记录

### 6.4 扩展性

1. **门店维度**：支持未来扩展更多门店相关功能
2. **库存策略**：支持未来扩展更多库存分配策略
3. **监控告警**：支持库存预警和补货提醒

---

## 七、总结

本方案在现有库存体系基础上，通过新增 `product_stocks` 表实现多市场、多门店的**变体级别**库存管理，支持共享库存、独立库存、混合模式三种策略，同时保持向后兼容，确保现有功能不受影响。

**核心特点：**
- ✅ **变体级别库存**：所有库存操作都是变体级别，`variant_id` 为必填参数
- ✅ **灵活的多市场库存分配策略**：支持共享、独立、混合三种模式
- ✅ **支持门店维度的精细化库存管理**：可针对不同门店设置独立库存
- ✅ **向后兼容**：保留 `product_variants.stock` 作为基准库存，不影响现有功能
- ✅ **性能优化**：合理索引设计，支持大规模数据查询
- ✅ **易于扩展**：支持未来扩展更多库存分配策略和门店功能

---

**文档状态：** 📝 设计完成，待评审

**© 2024 Red Jasmine Framework. All Rights Reserved.**

