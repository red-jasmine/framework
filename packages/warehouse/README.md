# Red Jasmine Warehouse Domain Package

## 概述

轻量级仓库领域包，管理仓库、门店、配送中心等位置信息。这是轻量级仓库领域，只包含与电商销售相关的仓库信息，不包含完整的WMS功能（入库、出库、货位管理等）。

## 主要功能

- **仓库管理**: 仓库的创建、查询、更新、删除等完整生命周期管理
- **仓库类型**: 支持仓库、门店、配送中心等多种类型
- **市场/门店关联**: 通过中间表实现仓库与市场/门店的多对多关系
- **默认仓库**: 支持设置默认仓库，用于简单库存模式

## 技术架构

基于领域驱动设计(DDD)架构，包含以下层次：

- **领域层(Domain)**: 核心业务逻辑和领域模型
- **基础设施层(Infrastructure)**: 数据持久化
- **用户接口层(UI)**: RESTful API和Web界面（待实现）

## 核心实体

### 仓库聚合 (Warehouse Aggregate)
- **仓库实体**: 仓库的核心信息和状态管理
- **仓库市场关联**: 仓库与市场/门店的多对多关系

### 仓库类型枚举 (WarehouseTypeEnum)
- `WAREHOUSE`: 仓库
- `STORE`: 门店
- `DISTRIBUTION_CENTER`: 配送中心

## 数据库表结构

- `warehouses`: 仓库主表
- `warehouse_markets`: 仓库与市场/门店关联表

## 使用示例

### 获取默认仓库

```php
use RedJasmine\Warehouse\Domain\Services\WarehouseDomainService;

$warehouseService = app(WarehouseDomainService::class);
$defaultWarehouse = $warehouseService->getDefaultWarehouse();
```

### 根据市场/门店查找仓库

```php
use RedJasmine\Warehouse\Domain\Repositories\WarehouseRepositoryInterface;

$repository = app(WarehouseRepositoryInterface::class);
$warehouses = $repository->findByMarketAndStore('cn', 'default');
```

### 为仓库添加市场/门店关联

```php
use RedJasmine\Warehouse\Domain\Services\WarehouseDomainService;

$warehouseService = app(WarehouseDomainService::class);
$warehouseMarket = $warehouseService->addMarketToWarehouse(
    $warehouse,
    'cn',
    'default',
    true // 是否主要市场/门店
);
```

## 安装

```bash
composer require red-jasmine/warehouse
```

## 迁移

```bash
php artisan migrate
```

## 许可证

MIT

