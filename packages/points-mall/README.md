# 积分商城模块

基于DDD架构的积分商城系统，提供积分商品管理、积分兑换订单处理和积分余额管理功能。

## 功能特性

- **积分商品管理**：管理积分商品的发布、上下架、库存等
- **积分兑换处理**：处理用户的积分兑换请求，支持多种支付模式
- **订单集成**：与订单领域集成，统一处理订单流程
- **积分管理**：与钱包领域集成，管理用户积分余额
- **支付处理**：支持纯积分、纯现金、积分+现金混合支付模式

## 安装

```bash
composer require red-jasmine/points-mall
```

## 配置

发布配置文件：

```bash
php artisan vendor:publish --tag=points-mall-config
```

## 数据库迁移

运行数据库迁移：

```bash
php artisan migrate
```

## 使用示例

### 积分兑换

```php
use RedJasmine\PointsMall\Application\Commands\PointsExchangeCommand;use RedJasmine\PointsMall\Application\Services\PointsProduct\PointsMallApplicationService;

$service = app(PointsMallApplicationService::class);

$command = new PointsExchangeCommand([
    'product_id' => '123',
    'quantity' => 1,
    'buyer' => $user,
]);

$order = $service->exchange($command);
```

### 查询积分商品

```php
use RedJasmine\PointsMall\Application\Queries\PointsListProductsQuery;

$query = new PointsListProductsQuery([
    'status' => 'on_sale',
    'category_id' => 1,
]);

$products = $service->listProducts($query);
```

## 支付模式

### 纯积分支付
- 用户使用积分直接兑换商品
- 无需现金支付

### 纯现金支付
- 用户使用现金购买积分商品
- 无需积分支付

### 混合支付
- 用户同时使用积分和现金
- 支持灵活的支付比例配置

## 与现有系统集成

### 商品领域集成
- 商品信息复用
- 库存管理
- 分类体系

### 订单领域集成
- 订单创建
- 状态同步
- 流程复用

### 钱包领域集成
- 积分管理
- 交易记录
- 余额管理

### 支付领域集成
- 支付处理
- 支付方式
- 支付状态

## 配置说明

详细配置请参考 `config/points-mall.php` 文件。

## 许可证

MIT License 