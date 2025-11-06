# 迁移指南

从 `RedJasmine\Support\Domain\Casts\NewMoneyCast` 迁移到 `RedJasmine\Money\Casts\MoneyCast`

## 安装

1. 确保主项目的 `composer.json` 已包含依赖：

```json
{
    "require": {
        "red-jasmine/money": "1.0.x-dev"
    }
}
```

2. 运行 composer 更新：

```bash
composer update red-jasmine/money --with-all-dependencies
```

3. 发布配置文件：

```bash
php artisan vendor:publish --tag=money-config
```

## 配置货币

编辑 `config/money.php` 文件：

```php
return [
    'default_currency' => 'CNY',
    
    'currencies' => [
        // ISO 货币
        'iso' => ['CNY', 'USD', 'EUR'],
        
        // 比特币（可选）
        'bitcoin' => false,
        
        // 自定义货币
        'custom' => [
            'POINTS' => [
                'name' => '积分',
                'code' => 'POINTS',
                'subunit' => 0,
                'numeric_code' => 999,
            ],
        ],
    ],
];
```

## 代码迁移

### 方式一：直接替换（推荐）

```php
// 旧代码
use RedJasmine\Support\Domain\Casts\NewMoneyCast;

class Product extends Model
{
    protected $casts = [
        'price' => NewMoneyCast::class,
    ];
}
```

```php
// 新代码
use RedJasmine\Money\Casts\MoneyCast;

class Product extends Model
{
    protected $casts = [
        'price' => MoneyCast::class,
    ];
}
```

### 方式二：使用别名（临时兼容）

如果暂时不想修改代码，可以使用别名：

```php
use RedJasmine\Support\Domain\Casts\MoneyCast; // 别名，指向新的 MoneyCast

class Product extends Model
{
    protected $casts = [
        'price' => MoneyCast::class,
    ];
}
```

**注意**：别名方式仅用于过渡期，建议尽快迁移到新的命名空间。

## 新特性

### 1. 支持自定义货币

```php
// 配置自定义货币
'custom' => [
    'POINTS' => [
        'name' => '积分',
        'code' => 'POINTS',
        'subunit' => 0,
    ],
],

// 使用
$points = new Money(1000, new Currency('POINTS'));
```

### 2. 货币管理器

```php
use RedJasmine\Money\Currencies\AggregateCurrencies;

$currencies = app(AggregateCurrencies::class);

// 检查货币
$currencies->contains(new Currency('POINTS')); // true

// 获取小数位
$currencies->subunitFor(new Currency('CNY')); // 2

// 检查类型
$currencies->isCustom(new Currency('POINTS')); // true
```

### 3. 从配置读取默认货币

旧代码中硬编码的 'CNY' 现在会从配置文件读取：

```php
// 旧代码：硬编码
$currency = new Currency('CNY');

// 新代码：从配置读取
$currency = new Currency(config('money.default_currency'));
```

## 兼容性说明

- ✅ API 完全兼容
- ✅ 数据库结构无需改动
- ✅ 支持所有原有功能
- ✅ 新增自定义货币支持

## 常见问题

### Q1: 升级后出现 "Unknown currency" 错误？

**A**: 检查配置文件中是否包含你使用的货币代码。

### Q2: 如何支持所有 ISO 货币？

**A**: 在配置文件中设置：

```php
'currencies' => [
    'iso' => 'all',
],
```

### Q3: 自定义货币的小数位数如何确定？

**A**: 在配置中设置 `subunit`：
- `0` = 无小数（如积分）
- `2` = 两位小数（如人民币）
- `8` = 八位小数（如比特币）

## 测试建议

迁移后建议进行以下测试：

1. **读取测试**：确保能正确读取数据库中的金额
2. **写入测试**：确保能正确保存金额到数据库
3. **货币测试**：测试自定义货币是否正常工作
4. **边界测试**：测试 null 值、0 值等边界情况

## 回退方案

如果遇到问题需要回退：

1. 在 `composer.json` 中移除 `red-jasmine/money` 依赖
2. 运行 `composer update`
3. 恢复使用 `RedJasmine\Support\Domain\Casts\NewMoneyCast`

## 支持

如有问题，请提交 Issue：
https://github.com/red-jasmine/money/issues

