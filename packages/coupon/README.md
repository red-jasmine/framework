# 优惠券领域 (Coupon Domain)

Red Jasmine Framework 优惠券领域实现，基于 DDD 架构设计的电商优惠券系统。

## 功能特性

### 核心能力
- **优惠券管理**：创建、编辑、发布、暂停优惠券
- **用户优惠券管理**：用户优惠券的发放、使用、过期处理
- **优惠计算引擎**：支持多种优惠类型和门槛规则
- **营销策略支持**：支持不同的发放策略和使用规则

### 优惠类型
- **百分比优惠**：按订单金额百分比计算优惠
- **固定金额优惠**：固定金额减免
- **运费优惠**：运费减免或免运费

### 门槛类型
- **订单金额门槛**：订单总金额达到指定金额
- **商品金额门槛**：商品金额达到指定金额
- **运费金额门槛**：运费金额达到指定金额
- **跨店铺金额门槛**：跨店铺订单金额门槛

### 有效期规则
- **绝对有效期**：指定具体的开始和结束时间
- **相对有效期**：相对于领取时间的有效期

### 发放策略
- **手动发放**：管理员手动发放给用户
- **自动发放**：满足条件自动发放
- **兑换码发放**：用户通过兑换码领取

## 架构设计

### 目录结构
```
packages/coupon/
├── src/
│   ├── Domain/                 # 领域层
│   │   ├── Models/             # 领域模型
│   │   ├── Data/               # 数据传输对象
│   │   ├── Repositories/       # 仓库接口
│   │   └── Transformers/       # 转换器
│   ├── Application/            # 应用层
│   │   └── Services/           # 应用服务
│   ├── Infrastructure/         # 基础设施层
│   │   ├── Repositories/       # 仓库实现
│   │   └── ReadRepositories/   # 只读仓库实现
│   └── UI/                     # 用户接口层
│       └── Http/               # HTTP接口
├── database/                   # 数据库
│   └── migrations/             # 迁移文件
├── routes/                     # 路由配置
├── config/                     # 配置文件
└── README.md                   # 文档
```

### 核心模型

#### 优惠券 (Coupon)
- 优惠券基本信息管理
- 优惠规则配置
- 使用规则限制
- 发放策略设置

#### 用户优惠券 (UserCoupon)
- 用户优惠券实例
- 状态管理（可用、已使用、已过期）
- 使用记录关联

#### 优惠券使用记录 (CouponUsage)
- 优惠券使用历史
- 优惠金额记录
- 成本承担方信息

#### 发放统计 (CouponIssueStat)
- 发放数量统计
- 使用数量统计
- 过期数量统计

## 安装使用

### 1. 服务提供者注册
```php
// config/app.php
'providers' => [
    // ...
    RedJasmine\Coupon\CouponServiceProvider::class,
];
```

### 2. 发布资源文件
```bash
# 发布配置文件
php artisan vendor:publish --tag=coupon-config

# 发布迁移文件
php artisan vendor:publish --tag=coupon-migrations
```

### 3. 运行迁移
```bash
php artisan migrate
```

### 4. 配置文件
编辑 `config/coupon.php` 配置文件以适应您的需求。

## API 接口

### 管理员接口
- `GET /admin/coupons` - 获取优惠券列表
- `POST /admin/coupons` - 创建优惠券
- `GET /admin/coupons/{id}` - 获取优惠券详情
- `PUT /admin/coupons/{id}` - 更新优惠券
- `DELETE /admin/coupons/{id}` - 删除优惠券
- `POST /admin/coupons/{id}/publish` - 发布优惠券
- `POST /admin/coupons/{id}/pause` - 暂停优惠券

### 用户接口
- `GET /user/user-coupons` - 获取用户优惠券列表
- `GET /user/user-coupons/{id}` - 获取用户优惠券详情
- `POST /user/user-coupons/{id}/use` - 使用优惠券

## 扩展开发

### 自定义优惠类型
```php
// 创建自定义优惠类型枚举
enum CustomDiscountTypeEnum: string
{
    case CUSTOM = 'custom';
    // ...
}

// 实现自定义优惠计算逻辑
class CustomDiscountCalculator
{
    public function calculate($coupon, $order)
    {
        // 自定义优惠计算逻辑
    }
}
```

### 自定义发放策略
```php
// 创建自定义发放策略
class CustomIssueStrategy
{
    public function issue($coupon, $user)
    {
        // 自定义发放逻辑
    }
}
```

### Hook 扩展点
```php
// 注册Hook扩展
Hook::register('coupon.application.coupon.create.validate', function($context) {
    // 自定义验证逻辑
});

Hook::register('coupon.application.coupon.create.fill', function($context) {
    // 自定义填充逻辑
});
```

## 技术特性

- **DDD 架构**：采用领域驱动设计，清晰的分层架构
- **充血模型**：业务逻辑封装在领域模型中
- **CQRS 模式**：命令查询职责分离
- **事件驱动**：支持领域事件和扩展点
- **类型安全**：使用 PHP 8.3+ 强类型系统
- **可扩展性**：丰富的扩展点和Hook机制

## 许可证

本项目基于 MIT 许可证开源。 