# 快速开始指南

5 分钟快速上手 Red Jasmine Money

## 1. 安装

在主项目 `composer.json` 中添加（已添加）：

```json
{
    "require": {
        "red-jasmine/money": "1.0.x-dev"
    }
}
```

运行安装命令：

```bash
composer update red-jasmine/money
```

## 2. 发布配置

```bash
php artisan vendor:publish --tag=money-config
```

这将创建 `config/money.php` 配置文件。

## 3. 配置货币

编辑 `config/money.php`：

```php
return [
    'default_currency' => 'CNY',
    
    'currencies' => [
        'iso' => ['CNY', 'USD', 'EUR'],  // ISO 货币
        'bitcoin' => false,               // 比特币
        'custom' => [
            // 自定义积分货币
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

## 4. 创建数据库迁移

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('price_amount', 10, 2);   // 金额字段
    $table->string('price_currency', 3);       // 货币字段
    $table->timestamps();
});
```

## 5. 定义 Model

```php
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Money\Casts\MoneyCast;

class Product extends Model
{
    protected $fillable = ['name', 'price'];
    
    protected $casts = [
        'price' => MoneyCast::class,
    ];
}
```

## 6. 使用示例

### 创建产品

```php
use Money\Money;
use Money\Currency;

// 方式 1：使用 Money 对象
$product = Product::create([
    'name' => 'iPhone 15',
    'price' => new Money(699900, new Currency('CNY')), // 6999.00 元
]);

// 方式 2：使用字符串（自动解析）
$product = Product::create([
    'name' => 'iPhone 15',
    'price' => '6999.00',
]);

// 方式 3：使用数组
$product = Product::create([
    'name' => 'iPhone 15',
    'price' => [
        'amount' => '6999.00',
        'currency' => 'CNY',
    ],
]);
```

### 读取产品

```php
$product = Product::find(1);

$price = $product->price;  // Money 对象

// 获取金额（最小单位：分）
echo $price->getAmount();  // "699900"

// 获取货币代码
echo $price->getCurrency()->getCode();  // "CNY"

// 格式化显示
echo $price->getAmount() / 100;  // 6999.00
```

### 金额计算

```php
use Money\Money;
use Money\Currency;

$price1 = new Money(100000, new Currency('CNY')); // 1000.00
$price2 = new Money(50000, new Currency('CNY'));  // 500.00

// 加法
$total = $price1->add($price2);  // 1500.00

// 减法
$diff = $price1->subtract($price2);  // 500.00

// 乘法
$doubled = $price1->multiply(2);  // 2000.00

// 除法
$half = $price1->divide(2);  // 500.00

// 比较
$price1->greaterThan($price2);  // true
$price1->equals($price2);       // false
```

## 7. 使用自定义货币（积分）

### 配置

已在步骤 3 中配置 `POINTS` 货币。

### 使用

```php
// 创建积分记录
$points = new Money(1000, new Currency('POINTS'));

// 在 Model 中使用
class UserPoints extends Model
{
    protected $casts = [
        'points' => MoneyCast::class,
    ];
}

$user->points = new Money(5000, new Currency('POINTS'));
$user->save();
```

## 8. Spatie Data 集成

```php
use Money\Money;
use RedJasmine\Money\Casts\MoneyCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public string $name,
        
        #[WithCast(MoneyCast::class)]
        public ?Money $price = null,
    ) {
    }
}

// 使用
$data = ProductData::from([
    'name' => 'Product',
    'price' => '99.99',
]);

echo $data->price->getAmount();  // "9999"
```

## 9. 高级用法

### 共享货币字段

```php
// 数据库迁移
Schema::create('orders', function (Blueprint $table) {
    $table->decimal('subtotal_amount', 10, 2);
    $table->decimal('tax_amount', 10, 2);
    $table->decimal('total_amount', 10, 2);
    $table->string('currency', 3);  // 共享货币字段
});

// Model
class Order extends Model
{
    protected $casts = [
        'subtotal' => MoneyCast::class . ':currency,null,true',
        'tax'      => MoneyCast::class . ':currency,null,true',
        'total'    => MoneyCast::class . ':currency,null,true',
    ];
}
```

### 使用 BigInt 存储

```php
// 数据库迁移
Schema::create('wallets', function (Blueprint $table) {
    $table->bigInteger('balance_amount');  // 最小单位存储
    $table->string('balance_currency', 3);
});

// Model
class Wallet extends Model
{
    protected $casts = [
        'balance' => MoneyCast::class . ':null,null,null,bigint',
    ];
}
```

## 10. 常见问题

### Q: 金额显示为分而不是元？

```php
// 转换为元
$yuan = $money->getAmount() / 100;

// 或使用格式化（需要额外的 formatter）
```

### Q: 如何支持更多 ISO 货币？

在配置文件中添加货币代码：

```php
'iso' => ['CNY', 'USD', 'EUR', 'JPY', 'GBP'],
```

或支持全部：

```php
'iso' => 'all',
```

### Q: 自定义货币小数位数？

在配置的 `subunit` 中设置：

```php
'VOUCHER' => [
    'code' => 'VOUCHER',
    'subunit' => 2,  // 两位小数
],
```

## 下一步

- 阅读完整 [README](README.md)
- 查看 [迁移指南](MIGRATION.md)
- 查看 [配置示例](config/money.example.php)
- 查看 [更新日志](CHANGELOG.md)

## 获取帮助

- 提交 Issue
- 查看文档
- 阅读源代码

祝你使用愉快！🎉

