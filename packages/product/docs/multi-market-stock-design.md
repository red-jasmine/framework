# 商品多市场库存体系设计方案

## 文档信息

| 项目 | 内容 |
|------|------|
| **方案名称** | 商品多市场库存体系设计方案（含轻量级仓库领域） |
| **方案版本** | v2.0 |
| **创建日期** | 2024-12-19 |
| **更新日期** | 2024-12-19 |
| **适用范围** | Red Jasmine Framework - Product Domain + Warehouse Domain |
| **文档状态** | 📝 设计阶段 |

---

## 一、方案概述

### 1.1 设计目标

在现有库存体系基础上，通过引入**轻量级仓库领域**，增加**多仓库**的库存管理能力，支持：
- ✅ 轻量级仓库领域：独立的 `warehouse` 领域包，管理仓库/位置信息
- ✅ 多仓库库存：每个仓库独立管理库存
- ✅ 仓库类型：支持仓库、门店、配送中心等多种类型
- ✅ 仓库关联：通过 warehouse_id 关联 warehouses 表，统一使用仓库ID管理库存

### 1.2 设计原则

```
核心原则：

✅ 轻量级仓库领域：独立的 warehouse 领域包，只包含与电商销售相关的仓库信息
✅ 基准库存：product_variants 表保留总库存（所有仓库库存汇总或默认仓库库存）
✅ 多仓库库存：product_stocks 表管理不同仓库的库存分配，通过 warehouse_id 关联
✅ 库存扣减：支持按仓库精细扣减库存，支持库存锁定和释放
✅ 库存分配：支持手动分配库存到不同仓库
✅ 仓库关联：product_stocks 表通过 warehouse_id 关联 warehouses 表，统一使用仓库ID
✅ WMS边界：不包含完整WMS功能（入库、出库、货位管理等），预留WMS集成接口
```

---

## 二、数据库表结构设计

### 2.1 warehouses 表（新建 - 轻量级仓库领域）

**表名：** `warehouses`

**表说明：** 仓库/位置表，管理仓库、门店、配送中心等位置信息

**重要说明：** 这是轻量级仓库领域，只包含与电商销售相关的仓库信息，不包含完整的WMS功能（入库、出库、货位管理等）。

```sql
CREATE TABLE warehouses (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(64) NOT NULL UNIQUE COMMENT '仓库编码',
    name VARCHAR(255) NOT NULL COMMENT '仓库名称',
    
    -- ========== 仓库信息 ==========
    warehouse_type VARCHAR(32) DEFAULT 'warehouse' COMMENT '类型：warehouse-仓库, store-门店, distribution_center-配送中心',
    address TEXT COMMENT '地址',
    contact_phone VARCHAR(32) COMMENT '联系电话',
    contact_person VARCHAR(64) COMMENT '联系人',
    
    -- ========== 状态 ==========
    is_active TINYINT(1) DEFAULT 1 COMMENT '是否启用',
    is_default TINYINT(1) DEFAULT 0 COMMENT '是否默认仓库',
    
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
    
    INDEX idx_type (warehouse_type),
    INDEX idx_code (code),
    COMMENT='仓库/位置表（轻量级）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.2 warehouse_markets 表（新建 - 仓库与市场/门店关联表）

**表名：** `warehouse_markets`

**表说明：** 仓库与市场/门店的关联表，支持一个仓库关联多个市场/门店

**重要说明：** 通过中间表实现仓库与市场/门店的多对多关系，一个仓库可以服务多个市场/门店。

```sql
CREATE TABLE warehouse_markets (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    warehouse_id BIGINT UNSIGNED NOT NULL COMMENT '仓库ID',
    
    -- ========== 关联到市场/门店 ==========
    market VARCHAR(64) NOT NULL COMMENT '市场：cn, us, de, *（*表示所有市场）',
    store VARCHAR(64) NOT NULL DEFAULT '*' COMMENT '门店：default-默认门店，store_xxx-具体门店，* 表示所有门店',
    
    -- ========== 状态 ==========
    is_active TINYINT(1) DEFAULT 1 COMMENT '是否启用',
    is_primary TINYINT(1) DEFAULT 0 COMMENT '是否主要市场/门店',
    
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
    UNIQUE KEY uk_warehouse_market_store (warehouse_id, market, store),
    INDEX idx_warehouse (warehouse_id),
    INDEX idx_market_store (market, store),
    
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
    
    COMMENT='仓库-市场/门店关联表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.3 product_stocks 表（新建）

**表名：** `product_stocks`

**表说明：** 商品多市场库存表，管理不同仓库的变体级别库存分配

**重要说明：** 
- 库存管理均为变体级别，不支持商品级别库存
- 通过 `warehouse_id` 关联到 `warehouses` 表

```sql
CREATE TABLE product_stocks (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    variant_id BIGINT UNSIGNED NOT NULL COMMENT 'SKU ID（变体ID，必填）',
    
    -- ========== 库存维度（关联仓库）==========
    warehouse_id BIGINT UNSIGNED NULL COMMENT '仓库ID（关联warehouses表，NULL表示默认仓库/简单模式）',
    
    -- ========== 库存数量 ==========
    total_stock BIGINT DEFAULT 0 COMMENT '总库存（该仓库分配的总库存）',
    available_stock BIGINT DEFAULT 0 COMMENT '可用库存',
    locked_stock BIGINT DEFAULT 0 COMMENT '锁定库存（已下单未支付）',
    reserved_stock BIGINT DEFAULT 0 COMMENT '预留库存（已支付待发货）',
    sold_stock BIGINT DEFAULT 0 COMMENT '已售库存',
    safety_stock BIGINT DEFAULT 0 COMMENT '安全库存',
    
    -- ========== 库存状态 ==========
    is_active TINYINT(1) DEFAULT 1 COMMENT '是否启用',
    
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
    UNIQUE KEY uk_variant_warehouse (variant_id, warehouse_id),
    INDEX idx_warehouse (warehouse_id),
    INDEX idx_product_variant (product_id, variant_id),
    -- 注意：MySQL 中 NULL 值在唯一索引中的处理：
    -- 1. NULL 值可以参与唯一索引，但多个 NULL 值不违反唯一性约束
    -- 2. 为了确保简单模式下每个变体只有一条 warehouse_id=NULL 的记录，需要在应用层保证
    -- 3. 或者使用触发器/应用层逻辑确保唯一性
    
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE RESTRICT,
    -- 注意：warehouse_id 允许为 NULL，NULL 值不受外键约束限制
    
    COMMENT='商品-多市场库存表（变体级别）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.4 product_stock_logs 表（库存操作日志表）

**表名：** `product_stock_logs`

**表说明：** 商品库存操作日志表，记录所有库存变更操作，支持多仓库场景

**重要说明：** 
- 记录所有库存操作（锁定、解锁、预留、扣减、释放、调整、分配等）
- 支持多仓库场景，通过 `warehouse_id` 字段标识操作的仓库
- 记录操作前后的库存状态，便于追溯和审计
- 简单模式下 `warehouse_id` 为 NULL，高级模式下为具体仓库ID

```sql
CREATE TABLE product_stock_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- ========== 所属者信息 ==========
    owner_type VARCHAR(64) NOT NULL COMMENT '所属者类型',
    owner_id VARCHAR(64) NOT NULL COMMENT '所属者ID',
    
    -- ========== 商品信息 ==========
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    sku_id BIGINT UNSIGNED NOT NULL COMMENT 'SKU ID（变体ID）',
    
    -- ========== 仓库信息（多仓库支持）==========
    warehouse_id BIGINT UNSIGNED NULL COMMENT '仓库ID（NULL表示默认仓库/简单模式）',
    
    -- ========== 操作信息 ==========
    action_type VARCHAR(32) NOT NULL COMMENT '操作类型：add-增加, sub-扣减, reset-设置, lock-锁定, unlock-解锁, confirm-确认',
    action_stock BIGINT NOT NULL COMMENT '操作库存数量',
    
    -- ========== 库存状态（操作前后）==========
    before_stock BIGINT NOT NULL COMMENT '操作前库存（可用库存）',
    after_stock BIGINT NOT NULL COMMENT '操作后库存（可用库存）',
    lock_stock BIGINT DEFAULT 0 COMMENT '锁定库存数量',
    before_lock_stock BIGINT NOT NULL COMMENT '操作前锁定库存',
    after_lock_stock BIGINT NOT NULL COMMENT '操作后锁定库存',
    
    -- ========== 变更信息 ==========
    change_type VARCHAR(32) NOT NULL COMMENT '变更类型：seller-卖家编辑, sale-销售',
    change_detail VARCHAR(64) NULL COMMENT '变更明细（如订单号、调拨单号等）',
    
    -- ========== 版本控制 ==========
    version BIGINT UNSIGNED DEFAULT 0 COMMENT '版本号（用于乐观锁）',
    
    -- ========== 操作信息 ==========
    creator_type VARCHAR(64) NULL COMMENT '创建者类型',
    creator_id VARCHAR(64) NULL COMMENT '创建者ID',
    creator_nickname VARCHAR(64) NULL COMMENT '创建者昵称',
    updater_type VARCHAR(64) NULL COMMENT '更新者类型',
    updater_id VARCHAR(64) NULL COMMENT '更新者ID',
    updater_nickname VARCHAR(64) NULL COMMENT '更新者昵称',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- ========== 索引 ==========
    INDEX idx_product (product_id),
    INDEX idx_sku (sku_id),
    INDEX idx_warehouse (warehouse_id),
    INDEX idx_product_sku (product_id, sku_id),
    INDEX idx_warehouse_sku (warehouse_id, sku_id),
    INDEX idx_action_type (action_type),
    INDEX idx_change_type (change_type),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE RESTRICT,
    -- 注意：warehouse_id 允许为 NULL，NULL 值不受外键约束限制
    
    COMMENT='商品-库存操作日志表（支持多仓库）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**字段说明：**

| 字段 | 说明 | 备注 |
|------|------|------|
| `warehouse_id` | 仓库ID | NULL表示默认仓库（简单模式），具体ID表示高级模式下的仓库 |
| `action_type` | 操作类型 | add-增加, sub-扣减, reset-设置, lock-锁定, unlock-解锁, confirm-确认 |
| `action_stock` | 操作库存数量 | 正数表示增加，负数表示减少 |
| `before_stock` | 操作前可用库存 | 从 `product_stocks.available_stock` 记录 |
| `after_stock` | 操作后可用库存 | 从 `product_stocks.available_stock` 记录 |
| `before_lock_stock` | 操作前锁定库存 | 从 `product_stocks.locked_stock` 记录 |
| `after_lock_stock` | 操作后锁定库存 | 从 `product_stocks.locked_stock` 记录 |
| `change_type` | 变更类型 | seller-卖家编辑, sale-销售 |
| `change_detail` | 变更明细 | 可存储订单号、调拨单号等关联信息 |

**日志记录规则：**

1. **所有库存操作都必须记录日志**，包括：
   - 锁定库存（下单时）
   - 解锁库存（订单取消）
   - 预留库存（支付成功）
   - 扣减库存（发货后）
   - 释放库存（订单取消）
   - 手动调整库存
   - 库存分配

2. **多仓库场景下的日志记录**：
   - 简单模式（`warehouse_id=NULL`）：记录一条日志，`warehouse_id` 为 NULL
   - 高级模式（`warehouse_id` 为具体仓库ID）：记录一条日志，`warehouse_id` 为具体仓库ID

3. **日志记录时机**：
   - 在库存操作成功后立即记录
   - 与库存操作在同一事务中执行
   - 如果日志记录失败，不影响库存操作（可异步重试）

### 2.5 现有表字段说明（保持不变）

**products 表：**
- `stock` - 商品级别总库存（统计用，所有SKU库存汇总，仅用于统计展示）
- `channel_stock` - 渠道库存（保留，用于渠道活动）
- `lock_stock` - 锁定库存（统计用）
- `safety_stock` - 安全库存
- `is_advanced_stock` - **是否启用高级库存模式**（新增字段）
  - `true`：启用高级库存模式，使用多个仓库的库存集合（仓库库存List）
  - `false`：使用简单库存模式，统一使用 `warehouse_id=NULL`（默认仓库）的库存

**product_variants 表：**
- `stock` - SKU级别总库存（基准库存，所有市场共享或默认市场库存）
- `channel_stock` - SKU渠道库存（保留）
- `lock_stock` - SKU锁定库存（基准锁定库存）
- `safety_stock` - SKU安全库存
- `is_tracked` - **是否跟踪库存**（新增字段）
  - `true`：跟踪库存，库存扣减和查询正常进行
  - `false`：不跟踪库存，库存始终显示为有货，不进行库存扣减

**重要说明：** 
- 库存管理均为**变体级别**，不支持商品级别库存
- **仓库领域设计**：
  - `warehouses` 表管理仓库/位置信息（轻量级仓库领域）
  - `warehouse_markets` 表管理仓库与市场/门店的多对多关系
  - 一个仓库可以关联多个市场/门店
  - 支持多种仓库类型：仓库、门店、配送中心
- `product_stocks` 表的 `variant_id` 字段为必填（NOT NULL）
- `product_stocks` 表通过 `warehouse_id` 关联到 `warehouses` 表
- **统一使用 `product_stocks` 表管理所有库存**，`product_variants.stock` 和 `products.stock` 仅作为汇总数据用于统计展示
- **库存模式控制**：
  - 当 `products.is_advanced_stock = false` 时（简单库存模式）：
    * 统一使用 `warehouse_id = NULL`（默认仓库）的一条库存记录
    * 商品创建时必须创建库存记录
    * 商品编辑时只显示一个库存输入框
    * 下单时从 `warehouse_id=NULL` 的库存扣减
  - 当 `products.is_advanced_stock = true` 时（高级库存模式）：
    * 使用多个仓库的库存集合（仓库库存List），`warehouse_id` 为具体的仓库ID
    * 商品创建时必须创建至少一个仓库的库存记录
    * 商品编辑时显示仓库库存列表，可以配置多个仓库的库存
    * 下单时根据订单的仓库ID从对应仓库扣减库存
- `product_variants.stock` 字段作为**汇总数据**保留，用于：
  - 从 `product_stocks` 汇总而来（所有仓库库存的总和）
  - 统计展示：商品级别的库存汇总
  - **不作为库存源**：查询和扣减库存时统一使用 `product_stocks` 表
- `products.stock` 字段作为**统计汇总数据**，所有变体库存的汇总，仅用于统计展示
- **汇总逻辑**：编辑商品时，从 `product_stocks` 汇总到 `product_variants.stock`，再从 `product_variants.stock` 汇总到 `products.stock`（用于统计展示）
- **库存记录要求**：商品创建时必须创建库存记录，确保查询时一定有数据，不再使用回退机制

---

## 三、核心处理逻辑

### 3.1 库存查询逻辑

#### **3.1.1 获取可用库存**

```
查询流程（变体级别，统一使用 product_stocks 表）：

1. 确定仓库ID：
   a) 如果提供了 warehouseId：直接使用
   b) 如果提供了 market+store：通过 warehouse_markets 表查找对应的 warehouseId
      - 如果找到多个仓库，使用仓库选择策略（见下方说明）
   c) 如果都未提供：
      - 如果 products.is_advanced_stock = false：使用 warehouse_id = NULL（默认仓库）
      - 如果 products.is_advanced_stock = true：使用默认仓库（is_default=1）

2. 检查是否跟踪库存：
   - 查询 product_variants.is_tracked
   - 如果 is_tracked = false：直接返回一个很大的值（表示有货），不进行库存扣减
   - 如果 is_tracked = true：继续执行库存查询流程

3. 查询 product_stocks 表，匹配条件：
   - variant_id = {variantId}（必填，变体ID）
   - warehouse_id = {warehouseId}（简单模式为NULL，高级模式为具体仓库ID）

4. 返回该仓库的 available_stock
   - 如果 product_stocks 表中没有匹配记录，返回 0（商品创建时必须创建库存记录）

仓库选择策略（当 market+store 匹配到多个仓库时）：
1. 优先级策略：优先选择 is_primary=1 的仓库
2. 库存充足度策略：优先选择 available_stock >= 订单数量的仓库
3. 如果多个仓库都满足条件，选择 available_stock 最大的仓库
4. 如果所有仓库都不满足库存要求，选择 available_stock 最大的仓库（允许超卖，由业务层控制）

注意：
- 所有库存操作都是变体级别，variant_id 为必填参数
- 所有库存都统一使用 product_stocks 表，只是聚合方式不同
- 简单模式：统一使用 warehouse_id=NULL 的库存（NULL 表示默认仓库）
- 高级模式：使用多个仓库的库存集合（warehouse_id 为具体的仓库ID）
- warehouse_id 允许为 NULL，NULL 值不受外键约束限制
- `is_tracked` 字段在 `product_variants` 表中，是变体级别的属性，不因仓库而异
- **重要**：如果订单需要指定具体仓库，建议在订单创建时明确指定 warehouseId，避免自动选择的不确定性
```

#### **3.1.2 库存状态计算**

```
库存状态判断（动态计算，不存储）：

1. 检查是否跟踪库存：
   - 如果 product_variants.is_tracked = false：直接返回 in_stock（有货），不进行库存计算
   - 如果 product_variants.is_tracked = true：继续执行库存状态计算

2. 获取可用库存：available_stock（从 product_stocks 表）
3. 获取安全库存：safety_stock（从 product_stocks 表或 product_variants 表）
4. 判断逻辑：
   - available_stock <= 0 → out_of_stock（缺货）
   - available_stock <= safety_stock → low_stock（低库存）
   - available_stock > safety_stock → in_stock（有货）

注意：
- 库存状态通过 available_stock 和 safety_stock 动态计算，不存储在数据库中
- `is_tracked` 字段在 `product_variants` 表中，是变体级别的属性
- 如果 `is_tracked=false`，库存状态始终为 in_stock（有货）
```

### 3.2 库存分配逻辑

#### **3.2.1 库存分配**

```
库存分配配置：

1. 为每个仓库创建库存记录：
   - warehouse_id = {具体仓库ID}
   - total_stock = {分配的总库存}
   - available_stock = {分配的总库存}

2. 库存扣减：
   - 只扣减对应仓库的 available_stock
   - 其他仓库不受影响

3. 库存分配：
   - 直接分配库存到指定仓库
   - 各仓库库存独立管理，不需要总和限制
```

#### **3.2.2 仓库选择策略（多仓库场景）**

**问题场景：**
当多个仓库都支持同一个市场/门店时（例如：仓库A和仓库B都支持中国市场），用户下单时应该从哪个仓库扣减库存？

**解决方案：**

1. **订单必须指定 warehouseId（推荐）**
   - 在订单创建时，明确指定 warehouseId
   - 避免自动选择的不确定性
   - 适用于需要精确控制发货仓库的场景

2. **自动选择策略（如果未指定 warehouseId）**
   ```
   选择流程：
   
   1. 通过 market+store 查找所有匹配的仓库
   2. 过滤条件：
      a) is_active = 1（仓库必须启用）
      b) 该变体在该仓库有库存记录（product_stocks 表）
   3. 选择策略（按优先级）：
      a) 优先选择 is_primary=1 的仓库
      b) 如果多个仓库 is_primary=1，优先选择 available_stock >= 订单数量的仓库
      c) 如果多个仓库都满足库存要求，选择 available_stock 最大的仓库
      d) 如果所有仓库都不满足库存要求，选择 available_stock 最大的仓库（允许超卖，由业务层控制）
   4. 如果找不到任何仓库，抛出异常
   ```

3. **代码示例：**
   ```php
   public function selectWarehouseForOrder(
       int $variantId, 
       string $market, 
       string $store, 
       int $quantity
   ): int {
       // 1. 查找所有匹配的仓库
       $warehouses = $this->warehouseRepository->findByMarketAndStore($market, $store);
       
       // 2. 过滤有库存的仓库
       $warehousesWithStock = [];
       foreach ($warehouses as $warehouse) {
           $stock = $this->productStockService->getAvailableStock(
               $variantId, 
               $warehouse->id
           );
           if ($stock > 0) {
               $warehousesWithStock[] = [
                   'warehouse' => $warehouse,
                   'stock' => $stock
               ];
           }
       }
       
       if (empty($warehousesWithStock)) {
           throw new NoWarehouseAvailableException('没有可用的仓库');
       }
       
       // 3. 选择策略
       // 优先选择 is_primary=1 的仓库
       $primaryWarehouses = array_filter($warehousesWithStock, function($item) {
           return $item['warehouse']->is_primary == 1;
       });
       
       if (!empty($primaryWarehouses)) {
           $warehousesWithStock = $primaryWarehouses;
       }
       
       // 优先选择库存充足的仓库
       $sufficientWarehouses = array_filter($warehousesWithStock, function($item) use ($quantity) {
           return $item['stock'] >= $quantity;
       });
       
       if (!empty($sufficientWarehouses)) {
           $warehousesWithStock = $sufficientWarehouses;
       }
       
       // 选择库存最大的仓库
       usort($warehousesWithStock, function($a, $b) {
           return $b['stock'] <=> $a['stock'];
       });
       
       return $warehousesWithStock[0]['warehouse']->id;
   }
   ```

**重要说明：**
- **推荐做法**：订单创建时明确指定 warehouseId，避免自动选择的不确定性
- **自动选择**：仅在订单未指定 warehouseId 时使用，适用于简单场景
- **业务控制**：如果所有仓库库存都不足，是否允许超卖由业务层决定

### 3.3 库存扣减流程

#### **3.3.1 锁定库存（下单时）**

```
锁定流程：

1. 检查是否跟踪库存：
   - 如果 product_variants.is_tracked = false：直接返回成功，不进行库存锁定
   - 如果 product_variants.is_tracked = true：继续执行库存锁定流程

2. 查询可用库存（按 3.1.1 逻辑）
3. 检查可用库存是否充足
4. 记录操作前库存状态（用于日志）
5. 锁定库存：
   - available_stock = available_stock - quantity
   - locked_stock = locked_stock + quantity
6. 记录库存日志（product_stock_logs）：
   - warehouse_id: 当前操作的仓库ID（简单模式为NULL，高级模式为具体仓库ID）
   - action_type: 'lock'
   - action_stock: quantity（正数）
   - before_stock: 操作前的 available_stock
   - after_stock: 操作后的 available_stock
   - before_lock_stock: 操作前的 locked_stock
   - after_lock_stock: 操作后的 locked_stock
   - change_type: 'sale'
   - change_detail: 订单号（如果有）

注意：`is_tracked=false` 时，不进行库存锁定和扣减，订单可以正常下单。
```

#### **3.3.2 预留库存（支付成功）**

```
预留流程：

1. 检查是否跟踪库存：
   - 如果 product_variants.is_tracked = false：直接返回成功，不进行库存预留
   - 如果 product_variants.is_tracked = true：继续执行库存预留流程

2. 查询锁定库存记录
3. 记录操作前库存状态（用于日志）
4. 预留库存：
   - locked_stock = locked_stock - quantity
   - reserved_stock = reserved_stock + quantity
5. 记录库存日志（product_stock_logs）：
   - warehouse_id: 当前操作的仓库ID（简单模式为NULL，高级模式为具体仓库ID）
   - action_type: 'confirm'
   - action_stock: quantity（正数）
   - before_stock: 操作前的 available_stock（不变）
   - after_stock: 操作后的 available_stock（不变）
   - before_lock_stock: 操作前的 locked_stock
   - after_lock_stock: 操作后的 locked_stock
   - change_type: 'sale'
   - change_detail: 订单号

注意：`is_tracked=false` 时，不进行库存预留。
```

#### **3.3.3 扣减库存（发货后）**

```
扣减流程：

1. 检查是否跟踪库存：
   - 如果 product_variants.is_tracked = false：直接返回成功，不进行库存扣减
   - 如果 product_variants.is_tracked = true：继续执行库存扣减流程

2. 查询预留库存记录
3. 记录操作前库存状态（用于日志）
4. 扣减库存：
   - reserved_stock = reserved_stock - quantity
   - sold_stock = sold_stock + quantity
   - total_stock = total_stock - quantity
5. 更新 product_variants.stock（汇总数据，用于统计展示）
6. 更新 products.stock（汇总数据，用于统计展示）
7. 记录库存日志（product_stock_logs）：
   - warehouse_id: 当前操作的仓库ID（简单模式为NULL，高级模式为具体仓库ID）
   - action_type: 'sub'
   - action_stock: quantity（负数，表示扣减）
   - before_stock: 操作前的 available_stock（不变，因为已预留）
   - after_stock: 操作后的 available_stock（不变）
   - before_lock_stock: 操作前的 locked_stock（不变）
   - after_lock_stock: 操作后的 locked_stock（不变）
   - change_type: 'sale'
   - change_detail: 订单号

注意：`is_tracked=false` 时，不进行库存扣减，但可以记录销量（sold_stock）。
```

#### **3.3.4 释放库存（订单取消）**

```
释放流程：

1. 检查是否跟踪库存：
   - 如果 product_variants.is_tracked = false：直接返回成功，无需释放库存
   - 如果 product_variants.is_tracked = true：继续执行库存释放流程

2. 判断订单状态：
   - 未支付：释放 locked_stock
   - 已支付：释放 reserved_stock

3. 记录操作前库存状态（用于日志）
4. 释放库存：
   - available_stock = available_stock + quantity
   - locked_stock = locked_stock - quantity（未支付）
   - reserved_stock = reserved_stock - quantity（已支付）

5. 记录库存日志（product_stock_logs）：
   - warehouse_id: 当前操作的仓库ID（简单模式为NULL，高级模式为具体仓库ID）
   - action_type: 'unlock'（未支付）或 'sub'（已支付，释放预留库存）
   - action_stock: quantity（正数，表示释放）
   - before_stock: 操作前的 available_stock
   - after_stock: 操作后的 available_stock
   - before_lock_stock: 操作前的 locked_stock
   - after_lock_stock: 操作后的 locked_stock
   - change_type: 'sale'
   - change_detail: 订单号

注意：`is_tracked=false` 时，无需释放库存。
```

### 3.4 库存分配逻辑

```
分配流程：

1. 分配库存到指定仓库：
   - 创建或更新 product_stocks 记录
   - total_stock = total_stock + quantity
   - available_stock = available_stock + quantity

2. 更新汇总数据（可选，用于统计展示）：
   - 汇总 product_stocks 到 product_variants.stock
   - 汇总所有变体库存到 products.stock

注意：
- 各仓库库存独立管理，不需要总和限制
- product_variants.stock 和 products.stock 仅作为汇总数据，不作为约束条件
```

### 3.5 库存查询服务接口设计

#### **3.5.1 核心方法**

```
ProductStockService 核心方法（变体级别）：

注意：所有方法都要求 variantId 为必填参数，库存管理均为变体级别。

1. getAvailableStock(variantId, warehouseId)
   → 获取变体可用库存
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID

2. lockStock(variantId, warehouseId, quantity)
   → 锁定变体库存
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID

3. unlockStock(variantId, warehouseId, quantity)
   → 解锁变体库存
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID

4. reserveStock(variantId, warehouseId, quantity)
   → 预留变体库存
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID

5. deductStock(variantId, warehouseId, quantity)
   → 扣减变体库存
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID

6. releaseStock(variantId, warehouseId, quantity, orderStatus)
   → 释放变体库存
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID

7. allocateStockToWarehouse(variantId, warehouseId, quantity)
   → 分配变体库存到仓库
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID

8. getStockStatus(variantId, warehouseId)
   → 获取变体库存状态（动态计算）
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID
   → 返回：'in_stock' | 'low_stock' | 'out_of_stock'

10. logStockOperation(variantId, warehouseId, actionType, quantity, beforeStock, afterStock, beforeLockStock, afterLockStock, changeType, changeDetail)
   → 记录库存操作日志
   → variantId: 必填，变体ID
   → warehouseId: 必填，仓库ID（简单模式为NULL）
   → actionType: 操作类型（lock, unlock, confirm, sub, add, reset）
   → quantity: 操作数量
   → beforeStock: 操作前可用库存
   → afterStock: 操作后可用库存
   → beforeLockStock: 操作前锁定库存
   → afterLockStock: 操作后锁定库存
   → changeType: 变更类型（seller, sale）
   → changeDetail: 变更明细（订单号等）
```

### 3.6 与现有代码集成点

#### **3.6.1 现有库存操作改造**

```
改造策略：

1. 在现有库存操作中增加 warehouseId 参数（必填）
2. 如果未指定 warehouseId，使用默认仓库（is_default=1）
3. 统一使用 product_stocks 表，商品创建时必须创建库存记录

改造点：

1. StockApplicationService：
   - lock() → 增加 warehouseId 参数（必填）
   - unlock() → 增加 warehouseId 参数（必填）
   - confirm() → 增加 warehouseId 参数（必填）

2. ProductSkuRepository：
   - lock() → 调用 ProductStockService.lockStock()
   - unlock() → 调用 ProductStockService.unlockStock()
   - confirm() → 调用 ProductStockService.reserveStock()

3. StockCommand：
   - 增加 warehouseId 字段（必填）
```

#### **3.6.2 库存查询改造**

```
改造策略：

1. 统一使用 product_stocks 表查询库存（通过 warehouseId）
2. 商品创建时必须创建库存记录，确保查询时一定有数据
3. 保持现有查询接口不变

改造点：

1. FindSkuStockQuery：
   - 增加 warehouseId 参数（必填）
   - 查询逻辑改为调用 ProductStockService.getAvailableStock()

2. ProductStockPaginateQuery：
   - 支持按 warehouseId 过滤（必填）
```

#### **3.6.3 库存模式控制**

**设计说明：**

在商品表中添加 `is_advanced_stock` 字段，用于控制库存的聚合方式和仓库选择。**所有库存都统一使用 `product_stocks` 表**，只是聚合方式不同：
- 简单模式：统一使用 `warehouse_id=NULL`（NULL 表示默认仓库）的库存
- 高级模式：使用多个仓库的库存集合（`warehouse_id` 为具体的仓库ID）

这样可以：
- 统一使用 `product_stocks` 表，逻辑统一
- 通过 `is_advanced_stock` 控制聚合方式和仓库选择
- 下单时根据模式决定从哪个仓库扣减
- 代码逻辑更统一，只是聚合方式不同
- `warehouse_id=NULL` 语义清晰，符合数据库设计规范

**字段定义：**

```php
// products 表迁移文件
$table->boolean('is_advanced_stock')->default(false)->comment('是否启用高级库存模式');
```

**模式判断逻辑：**

```php
// 库存查询逻辑（统一使用 product_stocks 表）
if ($product->is_advanced_stock) {
    // 高级库存模式：从指定仓库查询
    $warehouseId = $warehouseId ?? $this->getDefaultWarehouse()->id;
    $stock = $this->productStockService->getAvailableStock($variantId, $warehouseId);
} else {
    // 简单库存模式：统一使用 warehouse_id=NULL（默认仓库）
    $stock = $this->productStockService->getAvailableStock($variantId, null);
}
```

**库存操作逻辑：**

```php
// 库存扣减逻辑（统一使用 product_stocks 表）
if ($product->is_advanced_stock) {
    // 高级库存模式：从指定仓库扣减
    $warehouseId = $warehouseId ?? $this->getDefaultWarehouse()->id;
    $this->productStockService->lockStock($variantId, $warehouseId, $quantity);
} else {
    // 简单库存模式：统一从 warehouse_id=NULL 扣减
    $this->productStockService->lockStock($variantId, null, $quantity);
}
```

#### **3.6.4 数据结构优化方案**

**问题分析：**

在多市场库存体系下，每个变体在不同市场/门店可能有不同的库存配置。现有代码中，变体的库存是一个简单的 `int` 值，无法表达多市场库存的复杂场景。

**前提条件：**

只有当 `products.is_advanced_stock = true` 时，才需要处理多市场库存配置。

**优化方案：**

1. **创建库存配置 Data 类**

```php
namespace RedJasmine\Product\Domain\Product\Data;

use RedJasmine\Support\Data\Data;

class VariantStockConfig extends Data
{
    /**
     * 仓库ID（必填）
     */
    public int $warehouseId;
    
    /**
     * 总库存（该仓库分配的总库存）
     */
    public int $totalStock = 0;
    
    /**
     * 安全库存
     */
    public int $safetyStock = 0;
    
    /**
     * 是否启用
     */
    public bool $isActive = true;
    
    // 注意：is_tracked 字段在 product_variants 表中，不在 VariantStockConfig 中
}
```

2. **修改 Variant Data 类**

```php
namespace RedJasmine\Product\Domain\Product\Data;

class Variant extends Data
{
    // ... 现有字段 ...
    
    /**
     * 基准库存（汇总数据，从 product_stocks 汇总而来）
     * 仅用于快速查询和统计展示，不作为库存源
     */
    public int $stock = 0;
    
    /**
     * 基准安全库存
     */
    public int $safetyStock = 0;
    
    /**
     * 是否跟踪库存（变体级别属性）
     */
    public bool $isTracked = true;
    
    /**
     * 仓库库存配置集合（高级库存模式使用）
     * 当 products.is_advanced_stock = true 时，此字段必填
     * 当 products.is_advanced_stock = false 时，此字段为空，使用 warehouse_id=NULL 的库存
     * 
     * @var Collection<VariantStockConfig>|null
     */
    public ?Collection $warehouseStocks = null;
}
```

3. **处理逻辑优化**

```php
// ProductCommandHandler::handleStock() 方法改造

protected function handleStock(Product $product, Product $command): void
{
    // 统一使用 product_stocks 表处理库存
    $skuCommand = $command->variants?->keyBy('properties');
    
    foreach ($product->variants as $sku) {
        if ($sku->deleted_at) {
            // 删除的变体，清空所有仓库库存
            $this->clearAllWarehouseStocks($sku);
            continue;
        }
        
        $variantData = $skuCommand[$sku->properties] ?? null;
        if (!$variantData) {
            continue;
        }
        
        if ($product->is_advanced_stock) {
            // 高级库存模式：处理多个仓库的库存集合
            if ($variantData->warehouseStocks && $variantData->warehouseStocks->isNotEmpty()) {
                $this->handleMultiWarehouseStocks($sku, $variantData->warehouseStocks);
            } else {
                // 如果没有配置仓库库存，使用默认仓库
                $this->handleDefaultWarehouseStock($sku, $variantData->stock ?? $command->stock);
            }
        } else {
            // 简单库存模式：统一使用 warehouse_id=NULL
            $this->handleSimpleStockMode($sku, $variantData->stock ?? $command->stock);
        }
    }
    
    // 汇总库存到 product_variants.stock 和 products.stock（用于统计展示）
    $this->syncSummaryStock($product);
}

/**
 * 处理简单库存模式（统一使用 warehouse_id=NULL）
 */
protected function handleSimpleStockMode(ProductVariant $variant, int $stock): void
{
    // 简单库存模式：统一使用 warehouse_id=NULL（NULL 表示默认仓库）
    // 注意：使用 updateOrCreate 确保每个变体只有一条 warehouse_id=NULL 的记录
        $this->stockService->updateOrCreateStock(
        variantId: $variant->id,
        warehouseId: null, // NULL 表示默认仓库
        totalStock: $stock,
        safetyStock: $variant->safety_stock ?? 0
    );
}

/**
 * 处理多仓库库存配置
 */
protected function handleMultiWarehouseStocks(
    ProductVariant $variant, 
    Collection $stockConfigs
): void {
    foreach ($stockConfigs as $config) {
        $this->stockService->allocateStockToWarehouse(
            variantId: $variant->id,
            warehouseId: $config->warehouseId,
            totalStock: $config->totalStock,
            safetyStock: $config->safetyStock,
            isActive: $config->isActive
        );
    }
}

/**
 * 处理默认仓库库存（高级模式未配置仓库库存时使用）
 */
protected function handleDefaultWarehouseStock(ProductVariant $variant, int $stock): void
{
    // 使用默认仓库（is_default=1）
    $defaultWarehouse = $this->warehouseService->getDefaultWarehouse();
    if (!$defaultWarehouse) {
        // 如果没有默认仓库，创建一个
        $defaultWarehouse = $this->warehouseService->createDefaultWarehouse();
    }
    
    $this->stockService->allocateStockToWarehouse(
        variantId: $variant->id,
        warehouseId: $defaultWarehouse->id,
        totalStock: $stock,
        safetyStock: $variant->safety_stock ?? 0
    );
}

/**
 * 汇总库存到基准表
 */
protected function syncSummaryStock(Product $product): void
{
    foreach ($product->variants as $variant) {
        // 汇总 product_stocks 到 product_variants.stock（用于统计展示）
        $totalStock = $this->stockService->getTotalStock($variant->id);
        $variant->stock = $totalStock;
        $variant->save();
    }
    
    // 汇总所有变体库存到 products.stock
    $productStock = $product->variants()->sum('stock');
    $product->stock = $productStock;
    $product->save();
}
```

4. **数据流转说明**

```
编辑商品时的数据流转：

1. 前端提交：
   - 简单模式（is_advanced_stock=false）：variants[].stock = 100
     * 后端自动创建 warehouse_id=NULL 的 product_stocks 记录
   - 高级模式（is_advanced_stock=true）：variants[].warehouseStocks = [
       {warehouseId: 1, totalStock: 500},
       {warehouseId: 2, totalStock: 300},
       {warehouseId: 3, totalStock: 200}
     ]

2. 后端处理（统一使用 product_stocks 表）：
   - 简单模式：创建/更新 warehouse_id=NULL 的 product_stocks 记录
   - 高级模式：解析 warehouseStocks 集合，创建/更新多个仓库的 product_stocks 记录
   - 汇总 product_stocks 到 product_variants.stock（汇总数据，用于统计展示）
   - 汇总所有变体库存到 products.stock（汇总数据，用于统计展示）

3. 查询时（统一使用 product_stocks 表）：
   - 简单模式：查询 warehouse_id=NULL 的库存
   - 高级模式：查询指定 warehouse_id 的库存
   - 商品创建时必须创建库存记录，确保查询时一定有数据
```

5. **向后兼容策略**

```
统一库存处理逻辑：

1. 所有库存都统一使用 product_stocks 表：
   - 简单模式（is_advanced_stock=false）：统一使用 warehouse_id=NULL 的库存（NULL 表示默认仓库）
   - 高级模式（is_advanced_stock=true）：使用多个仓库的库存集合（warehouse_id 为具体的仓库ID）

2. 商品编辑时：
   - 简单模式：只显示一个库存输入框（对应 warehouse_id=NULL）
   - 高级模式：显示仓库库存列表（warehouseStocks），可以配置多个仓库的库存

3. 下单时：
   - 简单模式：统一从 warehouse_id=NULL 扣减库存
   - 高级模式：
     * **推荐**：订单创建时明确指定 warehouseId，从指定仓库扣减库存
     * **自动选择**：如果订单未指定 warehouseId，使用仓库选择策略自动选择仓库（见 3.2.2）
     * 多个仓库支持同一市场时，需要明确选择策略，避免不确定性

4. 查询时（统一使用 product_stocks 表）：
   - 简单模式：查询 warehouse_id=NULL 的库存
   - 高级模式：查询指定 warehouse_id 的库存
   - 商品创建时必须创建库存记录，确保查询时一定有数据

5. 外键约束：
   - warehouse_id 允许为 NULL，NULL 值不受外键约束限制
   - 当 warehouse_id 不为 NULL 时，必须引用 warehouses 表中存在的记录
```

6. **模式切换说明**

```
模式切换规则：

1. 从简单模式切换到高级模式：
   - 设置 products.is_advanced_stock = true
   - 将现有的 warehouse_id=NULL 的库存记录迁移到指定仓库
   - 或者保留 warehouse_id=NULL 的记录，新增其他仓库的库存记录

2. 从高级模式切换到简单模式：
   - 设置 products.is_advanced_stock = false
   - 汇总所有仓库的库存到 warehouse_id=NULL
   - 保留其他仓库的库存记录（不删除），以便后续切换回来

3. 切换时的数据迁移：
   - 需要确保数据一致性
   - 建议在业务低峰期进行切换
   - 切换后验证库存数据是否正确
   - 注意：所有库存都使用 product_stocks 表，切换只是改变聚合方式
   - warehouse_id=NULL 表示默认仓库，不受外键约束限制
```

---

## 四、数据示例

### 4.1 多仓库库存示例

**业务场景：**
- 商品：定制T恤
- SKU ID：10002
- 需求：中国主仓、深圳门店、美国主仓、洛杉矶门店四个仓库独立管理库存
- 说明：所有库存数据统一存放在 product_stocks 表中，product_variants.stock 仅作为汇总数据用于统计展示

**数据示例：**

**warehouses 表记录：**
| id | code | name | warehouse_type | is_default |
|----|------|------|----------------|------------|
| 5  | WH005 | 中国主仓 | warehouse | 0 |
| 6  | ST002 | 深圳门店 | store | 0 |
| 7  | WH007 | 美国主仓 | warehouse | 0 |
| 8  | ST003 | 洛杉矶门店 | store | 0 |

**warehouse_markets 表记录：**
| id | warehouse_id | market | store | is_primary |
|----|--------------|--------|-------|------------|
| 5  | 5            | cn     | default | 1 |
| 6  | 6            | cn     | store_sz01 | 1 |
| 7  | 7            | us     | default | 1 |
| 8  | 8            | us     | store_la01 | 1 |

**product_stocks 表记录：**
| id | product_id | variant_id | warehouse_id | total_stock | available_stock | locked_stock |
|----|-----------|-----------|--------------|-------------|----------------|--------------|
| 5  | 1002      | 10002     | 5            | 600         | 550            | 0            |
| 6  | 1002      | 10002     | 6            | 200         | 180            | 0            |
| 7  | 1002      | 10002     | 7            | 300         | 300            | 0            |
| 8  | 1002      | 10002     | 8            | 120         | 120            | 0            |

**说明：**
- 每个仓库都有独立的库存记录
- 中国主仓分配600件，深圳门店分配200件
- 美国主仓分配300件，洛杉矶门店分配120件
- 中国市场售出50件 → 只影响记录5（中国主仓），记录7-8（美国仓库）不受影响
- 每个仓库的库存独立管理，互不影响
```

---

## 五、实施步骤

### Phase 1: 数据库表创建（Week 1）

**任务：**
1. 创建 `warehouses` 表迁移文件（轻量级仓库领域）
2. 创建 `warehouse_markets` 表迁移文件（仓库与市场/门店关联表）
3. 创建 `product_stocks` 表迁移文件
4. 修改 `product_stock_logs` 表迁移文件，添加 `warehouse_id` 字段
5. 创建 `WarehouseTypeEnum` 枚举类
6. 创建 `StockStatusEnum` 枚举类（用于动态计算库存状态，不存储在数据库）
7. 数据库迁移测试

### Phase 2: 领域模型创建（Week 1-2）

**任务：**
1. 创建 `warehouse` 领域包（轻量级仓库领域）
   - 创建 `Warehouse` 模型
   - 创建 `WarehouseMarket` 模型（关联模型）
   - 创建 `WarehouseRepositoryInterface` 接口
   - 创建 `WarehouseRepository` 实现
   - 创建 `WarehouseDomainService` 领域服务
2. 创建 `ProductStock` 模型
3. 创建 `ProductStockRepositoryInterface` 接口
4. 创建 `ProductStockRepository` 实现
5. 创建 `ProductStockService` 领域服务
6. 更新 `ProductStockLog` 模型，添加 `warehouse_id` 字段和关联关系
7. 更新 `ProductStockLogRepository`，支持按 `warehouse_id` 查询

### Phase 3: 核心逻辑实现（Week 2-3）

**任务：**
1. 实现仓库查询逻辑（getDefaultWarehouse, findByMarketAndStore）
2. 实现库存查询逻辑（getAvailableStock）
3. 实现库存锁定逻辑（lockStock）
4. 实现库存扣减逻辑（deductStock）
5. 实现库存分配逻辑（allocateStockToWarehouse）
6. 实现库存日志记录逻辑（logStockOperation）
   - 在所有库存操作中集成日志记录
   - 支持多仓库场景的日志记录

### Phase 4: 集成现有代码（Week 3-4）

**任务：**
1. 改造 `StockApplicationService`，支持 warehouseId 参数（简单模式为NULL，高级模式为具体仓库ID）
2. 改造 `StockCommand`，增加 warehouseId 字段（简单模式为NULL，高级模式为具体仓库ID）
3. 改造 `ProductSkuRepository`，调用新的库存服务
4. 简单模式统一使用 warehouse_id=NULL，高级模式根据订单使用具体仓库ID
5. 在所有库存操作中集成日志记录功能
   - 锁定库存时记录日志
   - 解锁库存时记录日志
   - 预留库存时记录日志
   - 扣减库存时记录日志
   - 释放库存时记录日志
   - 手动调整库存时记录日志

### Phase 5: 测试和优化（Week 4-5）

**任务：**
1. 单元测试
2. 集成测试
3. 性能测试
4. 文档编写

---

## 七、注意事项

### 6.1 向后兼容

1. **统一数据源**：所有库存数据统一使用 `product_stocks` 表，`product_variants.stock` 仅作为汇总数据用于统计展示
2. **变体级别库存**：所有库存操作都是变体级别，`variant_id` 为必填参数
3. **默认值处理**：
   - 简单模式（is_advanced_stock=false）：统一使用 warehouse_id=NULL
   - 高级模式（is_advanced_stock=true）：如果未提供 warehouseId，使用默认仓库（is_default=1）
4. **库存记录要求**：商品创建时必须创建库存记录，确保查询时一定有数据，不再使用回退机制
5. **仓库关联**：`product_stocks` 表通过 `warehouse_id` 关联 `warehouses` 表
6. **NULL 值唯一性**：
   - MySQL 中多个 NULL 值在唯一索引中不违反唯一性约束
   - 需要在应用层保证简单模式下每个变体只有一条 `warehouse_id=NULL` 的记录
   - 建议在 `handleSimpleStockMode` 方法中使用 `updateOrCreate` 确保唯一性

### 6.2 性能优化

1. **索引优化**：
   - 唯一索引：`uk_variant_warehouse (variant_id, warehouse_id)` - 确保变体在不同仓库的唯一性
   - 查询索引：`idx_warehouse (warehouse_id)` - 优化按仓库查询
   - 辅助索引：`idx_product_variant (product_id, variant_id)` - 支持按商品查询变体库存
2. **查询缓存**：
   - 库存查询结果缓存（Redis，TTL=5分钟）
   - 缓存 key 格式：`product_stock:{variant_id}:{warehouse_id}`
3. **批量操作**：支持批量查询和更新变体库存
4. **仓库缓存**：仓库信息缓存（Redis，TTL=1小时），减少 warehouses 表查询

### 6.3 数据一致性

1. **事务保证**：所有库存操作在事务中执行
2. **锁机制**：使用数据库行锁（lockForUpdate）防止并发问题

### 6.4 日志记录

1. **日志完整性**：
   - 所有库存操作都必须记录日志，确保可追溯性
   - 日志记录与库存操作在同一事务中执行，保证数据一致性
   - 如果日志记录失败，不影响库存操作（可异步重试）

2. **多仓库日志记录**：
   - 简单模式（`warehouse_id=NULL`）：记录一条日志，`warehouse_id` 为 NULL
   - 高级模式（`warehouse_id` 为具体仓库ID）：记录一条日志，`warehouse_id` 为具体仓库ID

3. **日志查询优化**：
   - 支持按 `warehouse_id` 查询日志，便于多仓库场景下的日志分析
   - 支持按 `action_type`、`change_type` 等字段过滤日志
   - 建议对日志表进行定期归档，避免数据量过大影响查询性能

4. **日志字段说明**：
   - `before_stock` 和 `after_stock`：记录操作前后的可用库存（`available_stock`）
   - `before_lock_stock` 和 `after_lock_stock`：记录操作前后的锁定库存（`locked_stock`）
   - `change_detail`：可存储订单号、调拨单号等关联信息，便于追溯

### 6.5 扩展性

1. **仓库维度**：支持未来扩展更多仓库相关功能（仓库层级、仓库类型等）
2. **库存策略**：支持未来扩展更多库存分配策略
3. **监控告警**：支持库存预警和补货提醒
4. **WMS集成**：预留 WMS 系统集成接口，支持未来对接独立 WMS 系统
5. **日志分析**：支持基于日志的库存分析报表和预警功能

---

## 八、总结

本方案在现有库存体系基础上，通过引入**轻量级仓库领域（Warehouse Domain）**和新增 `product_stocks` 表实现多仓库的**变体级别**库存管理。**所有库存都统一使用 `product_stocks` 表**，通过 `is_advanced_stock` 字段控制聚合方式：
- 简单模式：统一使用 `warehouse_id=NULL` 的库存（NULL 表示默认仓库）
- 高级模式：使用多个仓库的库存集合（`warehouse_id` 为具体的仓库ID）

每个仓库独立管理库存，逻辑统一，代码简洁。`warehouse_id` 允许为 NULL，符合数据库设计规范，语义清晰。

**核心特点：**
- ✅ **统一库存表设计**：所有库存都统一使用 `product_stocks` 表，逻辑统一，代码简洁
- ✅ **轻量级仓库领域**：独立的 `warehouse` 领域包，管理仓库/位置信息，不包含完整WMS功能
- ✅ **变体级别库存**：所有库存操作都是变体级别，`variant_id` 为必填参数
- ✅ **多仓库库存管理**：每个仓库独立管理库存，支持手动分配库存到不同仓库
- ✅ **仓库关联设计**：
  - `warehouse_markets` 表实现仓库与市场/门店的多对多关系
  - `product_stocks` 表通过 `warehouse_id` 关联 `warehouses` 表，统一使用仓库ID管理库存
  - 支持一个仓库服务多个市场/门店
- ✅ **模式控制**：
  - 简单模式（`is_advanced_stock=false`）：统一使用 `warehouse_id=NULL` 的库存（NULL 表示默认仓库）
  - 高级模式（`is_advanced_stock=true`）：使用多个仓库的库存集合（仓库库存List）
  - 商品编辑时根据模式显示不同的UI（简单模式：单个库存输入框；高级模式：仓库库存列表）
  - `warehouse_id` 允许为 NULL，NULL 值不受外键约束限制，语义清晰
- ✅ **完整的库存操作日志**：
  - `product_stock_logs` 表记录所有库存操作，支持多仓库场景
  - 记录操作前后的库存状态，便于追溯和审计
  - 支持按仓库、操作类型等维度查询日志
  - 日志记录与库存操作在同一事务中执行，保证数据一致性
- ✅ **性能优化**：合理索引设计，支持大规模数据查询，仓库信息缓存
- ✅ **易于扩展**：支持未来扩展更多库存分配策略和仓库功能，预留WMS集成接口

---

**文档状态：** 📝 设计完成，待评审

**© 2024 Red Jasmine Framework. All Rights Reserved.**

