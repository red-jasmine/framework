# 用户端 API 使用示例

## 概述

本文档提供了优惠券包用户端 API 的使用示例。

## 基础设置

所有用户端 API 都需要用户身份验证，请在请求头中添加：

```
Authorization: Bearer {user_token}
```

## 接口列表

### 优惠券相关接口（CouponController）

#### 1. 获取可领取优惠券列表

**接口地址:** `GET /api/user/coupon/coupons`

**请求参数:**
- `page` (int, optional): 页码，默认为 1
- `per_page` (int, optional): 每页数量，默认为 15
- `sort` (string, optional): 排序字段，如 `-created_at`
- `filter` (array, optional): 过滤条件

**响应示例:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "满100减10",
            "description": "购买满100元减10元",
            "image": "https://example.com/coupon.jpg",
            "discount_amount_type": "fixed_amount",
            "discount_amount_value": "10.00",
            "threshold_type": "amount",
            "threshold_value": "100.00",
            "validity_start_time": "2024-01-01T00:00:00Z",
            "validity_end_time": "2024-12-31T23:59:59Z",
            "total_quantity": 1000,
            "total_issued": 150,
            "remaining_issue_count": 850,
            "can_collect": true,
            "label": "满100减10"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 10
    }
}
```

#### 2. 获取优惠券详情

**接口地址:** `GET /api/user/coupon/coupons/{id}`

**路径参数:**
- `id` (int): 优惠券ID

**响应示例:**
```json
{
    "data": {
        "id": 1,
        "name": "满100减10",
        "description": "购买满100元减10元",
        "image": "https://example.com/coupon.jpg",
        "discount_amount_type": "fixed_amount",
        "discount_amount_value": "10.00",
        "threshold_type": "amount",
        "threshold_value": "100.00",
        "validity_start_time": "2024-01-01T00:00:00Z",
        "validity_end_time": "2024-12-31T23:59:59Z",
        "usage_rules": [],
        "receive_rules": [],
        "can_collect": true,
        "label": "满100减10"
    }
}
```

#### 3. 领取优惠券

**接口地址:** `POST /api/user/coupon/coupons/{id}/receive`

**路径参数:**
- `id` (int): 优惠券ID

**请求参数:**
```json
{
    "channel": "app",
    "invite_code": "ABC123",
    "extra": {
        "source": "活动页面"
    }
}
```

**响应示例:**
```json
{
    "data": {
        "id": 10001,
        "coupon_id": 1,
        "status": "available",
        "start_at": "2024-01-01T00:00:00Z",
        "end_at": "2024-12-31T23:59:59Z",
        "created_at": "2024-01-15T10:30:00Z"
    }
}
```

#### 4. 使用优惠券

**接口地址:** `POST /api/user/coupon/coupons/consume/{userCouponId}`

**路径参数:**
- `userCouponId` (int): 用户优惠券ID

**请求参数:**
```json
{
    "order_id": 123456,
    "order_amount": "150.00",
    "use_amount": "10.00",
    "extra": {
        "order_type": "normal"
    }
}
```

**响应示例:**
```json
{
    "data": true
}
```

### 用户优惠券查询接口（UserCouponController）

#### 5. 获取我的优惠券列表

**接口地址:** `GET /api/user/coupon/user-coupons`

**请求参数:**
- `page` (int, optional): 页码，默认为 1
- `per_page` (int, optional): 每页数量，默认为 15
- `status` (string, optional): 状态过滤，可选值: `available`, `used`, `expired`
- `coupon_id` (int, optional): 优惠券ID过滤
- `is_available` (bool, optional): 是否可用过滤
- `is_used` (bool, optional): 是否已使用过滤
- `is_expired` (bool, optional): 是否已过期过滤

**响应示例:**
```json
{
    "data": [
        {
            "id": 10001,
            "coupon_id": 1,
            "status": "available",
            "start_at": "2024-01-01T00:00:00Z",
            "end_at": "2024-12-31T23:59:59Z",
            "created_at": "2024-01-15T10:30:00Z",
            "coupon": {
                "id": 1,
                "name": "满100减10",
                "discount_amount_type": "fixed_amount",
                "discount_amount_value": "10.00"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 5
    }
}
```

**状态筛选示例:**

获取可用优惠券：
```
GET /api/user/coupon/user-coupons?status=available&is_available=true
```

获取已使用优惠券：
```
GET /api/user/coupon/user-coupons?status=used&is_used=true
```

获取已过期优惠券：
```
GET /api/user/coupon/user-coupons?status=expired&is_expired=true
```

#### 6. 获取用户优惠券详情

**接口地址:** `GET /api/user/coupon/user-coupons/{id}`

**路径参数:**
- `id` (int): 用户优惠券ID

**响应示例:**
```json
{
    "data": {
        "id": 10001,
        "coupon_id": 1,
        "status": "available",
        "start_at": "2024-01-01T00:00:00Z",
        "end_at": "2024-12-31T23:59:59Z",
        "created_at": "2024-01-15T10:30:00Z",
        "coupon": {
            "id": 1,
            "name": "满100减10",
            "description": "购买满100元减10元",
            "discount_amount_type": "fixed_amount",
            "discount_amount_value": "10.00",
            "threshold_value": "100.00"
        }
    }
}
```

## 接口职责划分

### CouponController
- **职责:** 优惠券的查看、领取和使用
- **功能:**
  - 查看可领取的优惠券列表和详情
  - 领取优惠券
  - 使用优惠券

### UserCouponController  
- **职责:** 用户已领取优惠券的查询
- **功能:**
  - 查看用户已领取的优惠券列表（支持状态过滤）
  - 查看用户优惠券详情
  - 只读操作，不包含修改操作

## 错误处理

所有接口都会返回标准的错误响应格式：

```json
{
    "message": "优惠券不存在",
    "errors": {}
}
```

常见的错误码：
- `400` - 请求参数错误
- `401` - 未授权
- `403` - 权限不足
- `404` - 资源不存在
- `422` - 验证失败
- `500` - 服务器内部错误

## 使用示例

### JavaScript 示例

```javascript
// 获取可领取优惠券列表
async function getCoupons() {
    const response = await fetch('/api/user/coupon/coupons', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${userToken}`,
            'Content-Type': 'application/json'
        }
    });
    
    const data = await response.json();
    return data.data;
}

// 领取优惠券
async function receiveCoupon(couponId) {
    const response = await fetch(`/api/user/coupon/coupons/${couponId}/receive`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${userToken}`,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            channel: 'app',
            extra: {
                source: 'coupon_center'
            }
        })
    });
    
    const data = await response.json();
    return data.data;
}

// 使用优惠券
async function useCoupon(userCouponId, orderInfo) {
    const response = await fetch(`/api/user/coupon/coupons/consume/${userCouponId}`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${userToken}`,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            order_id: orderInfo.orderId,
            order_amount: orderInfo.amount,
            use_amount: orderInfo.discountAmount
        })
    });
    
    const data = await response.json();
    return data.data;
}

// 获取我的优惠券列表（可用的）
async function getMyAvailableCoupons() {
    const response = await fetch('/api/user/coupon/user-coupons?status=available&is_available=true', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${userToken}`,
            'Content-Type': 'application/json'
        }
    });
    
    const data = await response.json();
    return data.data;
}

// 获取我的优惠券列表（已使用的）
async function getMyUsedCoupons() {
    const response = await fetch('/api/user/coupon/user-coupons?status=used&is_used=true', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${userToken}`,
            'Content-Type': 'application/json'
        }
    });
    
    const data = await response.json();
    return data.data;
}
```

### PHP 示例

```php
// 使用 Guzzle HTTP 客户端
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'https://api.example.com',
    'headers' => [
        'Authorization' => 'Bearer ' . $userToken,
        'Content-Type' => 'application/json',
    ]
]);

// 获取可领取优惠券列表
$response = $client->get('/api/user/coupon/coupons');
$coupons = json_decode($response->getBody(), true);

// 领取优惠券
$response = $client->post('/api/user/coupon/coupons/1/receive', [
    'json' => [
        'channel' => 'app',
        'extra' => [
            'source' => 'coupon_center'
        ]
    ]
]);
$userCoupon = json_decode($response->getBody(), true);

// 获取我的可用优惠券
$response = $client->get('/api/user/coupon/user-coupons', [
    'query' => [
        'status' => 'available',
        'is_available' => true
    ]
]);
$myAvailableCoupons = json_decode($response->getBody(), true);
```

## 注意事项

1. **职责清晰:** CouponController 负责优惠券操作，UserCouponController 只负责查询
2. **状态过滤:** 通过查询参数进行状态筛选，无需单独的接口
3. **权限控制:** 所有接口都需要用户身份验证
4. **数据隔离:** 用户只能操作自己的数据
5. **防重复领取:** 用户不能重复领取同一张优惠券
6. **使用条件:** 优惠券的使用需要满足相应的使用条件
7. **分页查询:** 默认每页返回 15 条记录，最大不超过 100 条 