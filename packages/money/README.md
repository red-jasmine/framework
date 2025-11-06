# Red Jasmine Money

Red Jasmine Money 是一个基于 `moneyphp/money` 的 Laravel 金额处理扩展包，支持多种货币类型，包括 ISO 标准货币、比特币以及自定义虚拟货币。

## 特性

- ✅ 支持 ISO 标准货币
- ✅ 支持比特币货币
- ✅ 支持自定义虚拟货币（积分、游戏币、代金券等）
- ✅ Eloquent Model 金额转换
- ✅ Spatie Laravel Data 集成
- ✅ 支持 decimal 和 bigint 两种存储类型
- ✅ 共享货币字段支持
- ✅ 完善的异常处理

## 安装

```bash
composer require red-jasmine/money
```

## 配置

发布配置文件：

```bash
php artisan vendor:publish --tag=money-config
```

配置文件 `config/money.php`：

```php
return [
    // 默认货币
    'default_currency' => env('MONEY_DEFAULT_CURRENCY', 'CNY'),

    // 货币配置
    'currencies' => [
        // ISO 标准货币
        'iso' => [
            'CNY', 'USD', 'EUR', 'GBP', 'JPY', 'HKD', 'TWD', 'KRW'
        ],
        // 或使用 'all' 支持所有 ISO 货币
        // 'iso' => 'all',

        // 比特币货币
        'bitcoin' => false,

        // 自定义货币
        'custom' => [
            // 积分
            'POINTS' => [
                'name' => '积分',
                'code' => 'POINTS',
                'subunit' => 0,  // 没有小数
                'numeric_code' => 999,
            ],
            
            // 游戏金币
            'GOLD' => [
                'name' => '金币',
                'code' => 'GOLD',
                'subunit' => 0,
                'numeric_code' => 998,
            ],
            
            // 平台代金券
            'VOUCHER' => [
                'name' => '代金券',
                'code' => 'VOUCHER',
                'subunit' => 2,  // 两位小数
                'numeric_code' => 997,
            ],
        ],
    ],
];
```

## 使用方法

### 1. Eloquent Model 中使用

#### 数据库迁移

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->decimal('price_amount', 10, 2);  // 金额
    $table->string('price_currency', 3);      // 货币代码
    $table->timestamps();
});
```

#### Model 定义

```php
use Illuminate\Database\Eloquent\Model;
use Money\Money;
use RedJasmine\Money\Casts\MoneyCast;

class Product extends Model
{
    protected $casts = [
        'price' => MoneyCast::class,
    ];
}
```

#### 使用示例

```php
// 创建产品
$product = new Product();
$product->price = new Money(10050, new Currency('CNY')); // 100.50 元
$product->save();

// 或使用字符串
$product->price = '100.50';
$product->save();

// 或使用数组
$product->price = [
    'amount' => '100.50',
    'currency' => 'USD'
];
$product->save();

// 读取
$price = $product->price; // Money 对象
echo $price->getAmount(); // "10050" (最小单位：分)
echo $price->getCurrency()->getCode(); // "CNY"
```

### 2. Spatie Laravel Data 中使用

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
```

### 3. 高级配置

#### 自定义字段名

```php
protected $casts = [
    'price' => MoneyCast::class . ':currency_code,money_value',
];
```

这将使用 `price_currency_code` 和 `price_money_value` 作为数据库字段。

#### 使用 bigint 类型存储

```php
protected $casts = [
    'price' => MoneyCast::class . ':null,null,null,bigint',
];
```

数据库迁移：

```php
$table->bigInteger('price_amount');      // 使用 bigint 存储最小单位
$table->string('price_currency', 3);
```

#### 共享货币字段

当多个金额字段使用同一个货币时：

```php
protected $casts = [
    'price' => MoneyCast::class . ':currency,null,true',
    'cost' => MoneyCast::class . ':currency,null,true',
];
```

数据库迁移：

```php
$table->decimal('price_amount', 10, 2);
$table->decimal('cost_amount', 10, 2);
$table->string('currency', 3);  // 共享货币字段
```

### 4. 使用自定义货币

```php
use Money\Currency;
use Money\Money;

// 使用积分
$points = new Money(1000, new Currency('POINTS'));

// 使用游戏金币
$gold = new Money(500, new Currency('GOLD'));

// 使用代金券
$voucher = new Money(5000, new Currency('VOUCHER')); // 50.00
```

### 5. 货币管理器

```php
use RedJasmine\Money\Currencies\AggregateCurrencies;

// 获取货币管理器
$currencies = app(AggregateCurrencies::class);

// 检查货币是否支持
$isSupported = $currencies->contains(new Currency('POINTS'));

// 获取货币小数位数
$subunit = $currencies->subunitFor(new Currency('CNY')); // 2

// 检查货币类型
$isISO = $currencies->isISO(new Currency('USD'));        // true
$isBitcoin = $currencies->isBitcoin(new Currency('XBT')); // true
$isCustom = $currencies->isCustom(new Currency('POINTS')); // true
```

## 参数说明

`MoneyCast` 构造函数参数：

```php
MoneyCast::class . ':currencyKey,valueKey,isShareCurrencyField,valueType'
```

- `currencyKey`: 货币字段名（默认：`{field}_currency`）
- `valueKey`: 金额字段名（默认：`{field}_amount`）
- `isShareCurrencyField`: 是否共享货币字段（默认：`false`）
- `valueType`: 金额存储类型（`decimal` 或 `bigint`，默认：`decimal`）

## 许可证

MIT License

## 贡献

欢迎提交 Issue 和 Pull Request！

