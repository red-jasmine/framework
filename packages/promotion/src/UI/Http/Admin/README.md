# 活动管理 Admin UI 层

## 概述

本模块为 Red Jasmine Promotion 包提供完整的 Admin 管理员界面，用于管理电商活动。

## 功能特性

### 🎯 活动管理
- ✅ 标准 CRUD 操作（创建、查询、更新、删除）
- ✅ 活动状态管理（发布、审核、启动、暂停、结束、取消）
- ✅ 活动复制功能
- ✅ 活动统计信息查看
- ✅ 高级筛选和搜索

### 🛍️ 商品管理
- ✅ 活动商品添加/删除
- ✅ 商品价格和库存管理
- ✅ SKU 级别的管理
- ✅ 批量操作支持
- ✅ 商品状态管理

### 🔐 权限控制
- ✅ 基于角色的权限验证
- ✅ 操作级别的权限控制
- ✅ 数据范围权限

### 📊 数据验证
- ✅ 完整的表单验证
- ✅ 业务规则验证
- ✅ 运行时状态验证

## 目录结构

```
src/UI/Http/Admin/
├── Api/
│   ├── Controllers/
│   │   ├── Controller.php                    # 基础控制器
│   │   ├── ActivityController.php            # 活动管理控制器
│   │   └── ActivityProductController.php     # 活动商品控制器
│   ├── Requests/
│   │   ├── ActivityCreateRequest.php         # 活动创建验证
│   │   ├── ActivityUpdateRequest.php         # 活动更新验证
│   │   └── ActivityListRequest.php           # 活动查询验证
│   └── Resources/
│       ├── ActivityResource.php              # 活动资源
│       ├── ActivityProductResource.php       # 活动商品资源
│       ├── ActivitySkuResource.php           # 活动SKU资源
│       └── ActivityOrderResource.php         # 活动订单资源
├── Web/                                      # Web页面目录（预留）
├── PromotionAdminRoute.php                   # 路由定义
└── README.md                                 # 本文档
```

## API 接口

### 活动管理接口

#### 基础 CRUD
- `GET /api/promotion/activities` - 获取活动列表
- `POST /api/promotion/activities` - 创建活动
- `GET /api/promotion/activities/{id}` - 获取活动详情
- `PUT /api/promotion/activities/{id}` - 更新活动
- `DELETE /api/promotion/activities/{id}` - 删除活动

#### 状态管理
- `PATCH /api/promotion/activities/{id}/publish` - 发布活动
- `PATCH /api/promotion/activities/{id}/approve` - 审核通过
- `PATCH /api/promotion/activities/{id}/reject` - 审核拒绝
- `PATCH /api/promotion/activities/{id}/start` - 启动活动
- `PATCH /api/promotion/activities/{id}/pause` - 暂停活动
- `PATCH /api/promotion/activities/{id}/resume` - 恢复活动
- `PATCH /api/promotion/activities/{id}/end` - 结束活动
- `PATCH /api/promotion/activities/{id}/cancel` - 取消活动

#### 其他操作
- `POST /api/promotion/activities/{id}/copy` - 复制活动
- `GET /api/promotion/activities/{id}/statistics` - 获取统计信息

### 活动商品管理接口

- `GET /api/promotion/activities/{id}/products` - 获取活动商品列表
- `POST /api/promotion/activities/{id}/products` - 添加商品到活动
- `PATCH /api/promotion/activities/{id}/products/{productId}` - 更新活动商品
- `DELETE /api/promotion/activities/{id}/products/{productId}` - 删除活动商品

#### 批量操作
- `DELETE /api/promotion/activities/{id}/products/batch` - 批量删除
- `PATCH /api/promotion/activities/{id}/products/batch/status` - 批量更新状态
- `PATCH /api/promotion/activities/{id}/products/batch/show` - 批量更新显示状态

### 配置接口

- `GET /api/promotion/activity-types` - 获取活动类型配置
- `GET /api/promotion/activity-statuses` - 获取活动状态配置
- `GET /api/promotion/config` - 获取活动配置信息

## 使用示例

### 创建活动

```http
POST /api/promotion/activities
Content-Type: application/json

{
    "title": "双11秒杀活动",
    "description": "双11期间的限时秒杀活动",
    "type": "flash_sale",
    "start_time": "2024-11-11 00:00:00",
    "end_time": "2024-11-11 23:59:59",
    "rules": {
        "user_participation_limit": 1,
        "product_purchase_limit": 5
    },
    "is_show": true
}
```

### 查询活动列表

```http
GET /api/promotion/activities?page=1&per_page=15&type=flash_sale&status=running&title=双11
```

### 添加商品到活动

```http
POST /api/promotion/activities/123/products
Content-Type: application/json

{
    "products": [
        {
            "product_id": 1001,
            "product_name": "iPhone 15 Pro",
            "original_price": 8999.00,
            "activity_price": 7999.00,
            "activity_stock": 100,
            "limit_quantity": 1,
            "skus": [
                {
                    "sku_id": 10011,
                    "sku_name": "iPhone 15 Pro 256GB 深空黑色",
                    "original_price": 8999.00,
                    "activity_price": 7999.00,
                    "activity_stock": 50
                }
            ]
        }
    ]
}
```

## 数据验证规则

### 活动创建验证

- `title`: 必填，最大255字符
- `type`: 必填，必须是有效的活动类型
- `start_time`: 必填，必须晚于当前时间
- `end_time`: 必填，必须晚于开始时间
- `rules`: 可选，活动规则配置
- `user_requirements`: 可选，用户参与条件
- `product_requirements`: 可选，商品参与条件

### 活动更新验证

- 运行中的活动限制修改某些关键字段
- 不能修改活动类型和开始时间
- 结束时间不能早于当前时间

### 商品添加验证

- `product_id`: 必填，商品ID
- `original_price`: 必填，原价
- `activity_price`: 必填，活动价
- `activity_stock`: 可选，活动库存
- `limit_quantity`: 可选，限购数量

## 权限说明

### 活动管理权限

- `viewAny`: 查看活动列表
- `view`: 查看活动详情
- `create`: 创建活动
- `update`: 更新活动
- `delete`: 删除活动
- `publish`: 发布活动
- `approve`: 审核活动
- `reject`: 拒绝活动
- `start`: 启动活动
- `pause`: 暂停活动
- `resume`: 恢复活动
- `end`: 结束活动
- `cancel`: 取消活动

## 状态流转

活动状态流转遵循以下规则：

```
草稿(draft) → 待审核(pending) → 已发布(published) → 预热中(warming) → 进行中(running) → 已结束(ended)
     ↓              ↓              ↓              ↓              ↓
   已取消         已取消         已取消         已取消         已暂停
(cancelled)    (cancelled)    (cancelled)    (cancelled)     (paused)
                                                                 ↓
                                                            进行中(running)
```

## 扩展指南

### 添加自定义验证规则

在相应的 Request 类中添加验证规则：

```php
public function rules(): array
{
    return array_merge(parent::rules(), [
        'custom_field' => ['required', 'string'],
    ]);
}
```

### 添加自定义权限

在控制器的 `authorize` 方法中实现：

```php
public function authorize($ability, $arguments = []): bool
{
    // 实现自定义权限逻辑
    return $this->user()->can($ability, $arguments);
}
```

### 扩展资源字段

在 Resource 类的 `toArray` 方法中添加字段：

```php
public function toArray($request): array
{
    return array_merge(parent::toArray($request), [
        'custom_field' => $this->custom_field,
    ]);
}
```

## 注意事项

1. **状态验证**: 某些操作只能在特定状态下执行，系统会自动验证
2. **权限控制**: 所有操作都需要相应的权限，建议实现完整的权限体系
3. **数据一致性**: 批量操作使用事务确保数据一致性
4. **性能优化**: 大量数据查询时建议使用分页和索引
5. **日志记录**: 重要操作建议记录操作日志

## 技术规范

- **PHP版本**: 8.4+
- **Laravel版本**: 12.0+
- **编码标准**: PSR-12
- **架构模式**: DDD（领域驱动设计）
- **API规范**: RESTful API
- **数据格式**: JSON
- **验证方式**: Form Request
- **权限控制**: Laravel Policy

