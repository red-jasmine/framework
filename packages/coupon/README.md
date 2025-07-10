# Red Jasmine Coupon Package

## 概述

Red Jasmine Coupon Package 是一个基于 Laravel 的优惠券管理包，提供完整的优惠券创建、发放、使用和统计功能。

## 功能特性

- 优惠券创建和管理
- 用户优惠券发放
- 优惠券使用记录
- 发放统计
- 多种优惠类型支持（固定金额、百分比）
- 灵活的使用规则配置
- 有效期管理

## 数据库结构

### 主要表结构

1. **coupons** - 优惠券主表
   - 基础信息：名称、描述、图片
   - 优惠规则：目标类型、金额类型、门槛值、优惠金额
   - 有效期配置：绝对时间、相对时间
   - 使用规则：JSON格式存储
   - 发放控制：总数量、已发放、已使用

2. **user_coupons** - 用户优惠券表
   - 关联优惠券和用户
   - 状态管理：可用、已使用、已过期
   - 时间记录：发放时间、过期时间、使用时间

3. **coupon_usages** - 使用记录表
   - 详细的使用记录
   - 金额信息：门槛金额、优惠金额、最终优惠金额
   - 成本承担方信息

4. **coupon_issue_statistics** - 发放统计表
   - 按日期统计发放情况
   - 统计指标：发放数量、使用数量、过期数量、总成本

## Domain层架构

### 领域模型

#### Coupon（优惠券）
- 继承：`HasSnowflakeId`、`HasOwner`、`HasOperator`、`SoftDeletes`
- 主要功能：
  - 优惠券状态管理（草稿、发布、暂停、过期）
  - 发放控制（检查可发放性、更新发放数量）
  - 使用验证（检查可用性、有效期验证）
  - 优惠金额计算
  - 关联管理（用户优惠券、使用记录、统计）

#### UserCoupon（用户优惠券）
- 继承：`HasSnowflakeId`、`HasOwner`、`HasOperator`
- 主要功能：
  - 状态管理（可用、已使用、已过期）
  - 时间管理（发放时间、过期时间、使用时间）
  - 使用操作（标记为已使用）
  - 查询作用域（按用户、按状态、按所有者）

#### CouponUsage（使用记录）
- 继承：`HasSnowflakeId`、`HasOwner`、`HasOperator`
- 主要功能：
  - 使用记录存储
  - 金额计算（优惠比例、节省金额）
  - 成本承担方信息
  - 查询作用域（按优惠券、按用户、按订单、按所有者）

#### CouponIssueStatistic（发放统计）
- 继承：`HasOwner`、`HasOperator`
- 主要功能：
  - 统计数据管理（发放、使用、过期、成本）
  - 比率计算（使用率、过期率、平均成本）
  - 统计重置
  - 查询作用域（按日期、按所有者）

### 枚举类型

#### CouponStatusEnum（优惠券状态）
- `DRAFT` - 草稿
- `PUBLISHED` - 已发布
- `PAUSED` - 已暂停
- `EXPIRED` - 已过期

#### UserCouponStatusEnum（用户优惠券状态）
- `AVAILABLE` - 可用
- `USED` - 已使用
- `EXPIRED` - 已过期

#### DiscountAmountTypeEnum（优惠金额类型）
- `FIXED_AMOUNT` - 固定金额
- `PERCENTAGE` - 百分比

#### DiscountTargetEnum（优惠目标类型）
- `ORDER_AMOUNT` - 订单金额
- `PRODUCT_AMOUNT` - 商品金额
- `SHIPPING_AMOUNT` - 运费金额
- `CROSS_STORE_AMOUNT` - 跨店金额

#### ThresholdTypeEnum（门槛类型）
- `AMOUNT` - 金额门槛
- `QUANTITY` - 数量门槛

#### ValidityTypeEnum（有效期类型）
- `ABSOLUTE` - 绝对时间
- `RELATIVE` - 相对时间

### 数据传输对象

#### CouponData
- 包含优惠券的所有属性
- 支持枚举类型转换
- 包含所有者、成本承担方信息

#### UserCouponData
- 包含用户优惠券的所有属性
- 支持用户接口类型

### 转换器

#### CouponTransformer
- 将CouponData转换为Coupon模型
- 处理所有者、成本承担方信息映射

#### UserCouponTransformer
- 将UserCouponData转换为UserCoupon模型
- 处理用户信息映射

### 值对象

#### RuleItem
- 规则项值对象
- 包含规则对象类型、规则类型、对象值
- 提供匹配和显示功能

## 使用示例

### 创建优惠券

```php
use RedJasmine\Coupon\Domain\Data\CouponData;
use RedJasmine\Coupon\Domain\Models\Coupon;

$couponData = new CouponData([
    'name' => '满100减10',
    'discountTarget' => DiscountTargetEnum::ORDER_AMOUNT,
    'discountAmountType' => DiscountAmountTypeEnum::FIXED_AMOUNT,
    'discountAmountValue' => 10,
    'thresholdType' => ThresholdTypeEnum::AMOUNT,
    'thresholdValue' => 100,
    'validityType' => ValidityTypeEnum::ABSOLUTE,
    'validityStartTime' => '2024-01-01 00:00:00',
    'validityEndTime' => '2024-12-31 23:59:59',
    'totalQuantity' => 1000,
]);

$coupon = new Coupon();
$transformer = new CouponTransformer();
$coupon = $transformer->transform($couponData, $coupon);
$coupon->save();
```

### 发放优惠券

```php
$userCoupon = $coupon->issueToUser($userId);
```

### 使用优惠券

```php
$userCoupon->use($orderId);
```

## 安装

```bash
composer require red-jasmine/coupon
```

## 配置

发布配置文件：

```bash
php artisan vendor:publish --tag=coupon-config
```

## 许可证

MIT License 