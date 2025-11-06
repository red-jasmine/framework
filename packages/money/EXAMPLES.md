# 使用示例

## 场景一：电商产品价格管理

### 数据库迁移

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('price_amount', 10, 2);
    $table->string('price_currency', 3);
    $table->decimal('cost_amount', 10, 2)->nullable();
    $table->string('cost_currency', 3)->nullable();
    $table->timestamps();
});
```

### Model 定义

```php
use Illuminate\Database\Eloquent\Model;
use Money\Money;
use RedJasmine\Money\Casts\MoneyCast;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'cost'];
    
    protected $casts = [
        'price' => MoneyCast::class,
        'cost' => MoneyCast::class,
    ];
    
    // 计算利润
    public function getProfit(): ?Money
    {
        if (!$this->price || !$this->cost) {
            return null;
        }
        
        return $this->price->subtract($this->cost);
    }
    
    // 计算利润率
    public function getProfitRate(): ?float
    {
        $profit = $this->getProfit();
        if (!$profit || !$this->cost) {
            return null;
        }
        
        return (float)$profit->ratioOf($this->cost);
    }
}
```

### 使用

```php
$product = Product::create([
    'name' => 'iPhone 15 Pro',
    'price' => '8999.00',
    'cost' => '6500.00',
]);

echo "售价：" . $product->price->getAmount() / 100 . " 元\n";
echo "成本：" . $product->cost->getAmount() / 100 . " 元\n";
echo "利润：" . $product->getProfit()->getAmount() / 100 . " 元\n";
echo "利润率：" . ($product->getProfitRate() * 100) . "%\n";
```

## 场景二：订单金额计算

### 数据库迁移

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_no')->unique();
    $table->decimal('subtotal_amount', 10, 2);
    $table->decimal('shipping_amount', 10, 2)->default(0);
    $table->decimal('tax_amount', 10, 2)->default(0);
    $table->decimal('discount_amount', 10, 2)->default(0);
    $table->decimal('total_amount', 10, 2);
    $table->string('currency', 3);
    $table->timestamps();
});
```

### Model 定义

```php
class Order extends Model
{
    protected $fillable = [
        'order_no', 'subtotal', 'shipping', 'tax', 'discount', 'total', 'currency'
    ];
    
    protected $casts = [
        'subtotal' => MoneyCast::class . ':currency,null,true',
        'shipping' => MoneyCast::class . ':currency,null,true',
        'tax' => MoneyCast::class . ':currency,null,true',
        'discount' => MoneyCast::class . ':currency,null,true',
        'total' => MoneyCast::class . ':currency,null,true',
    ];
    
    // 计算总金额
    public function calculateTotal(): void
    {
        $this->total = $this->subtotal
            ->add($this->shipping ?? new Money(0, $this->subtotal->getCurrency()))
            ->add($this->tax ?? new Money(0, $this->subtotal->getCurrency()))
            ->subtract($this->discount ?? new Money(0, $this->subtotal->getCurrency()));
    }
}
```

### 使用

```php
use Money\Money;
use Money\Currency;

$order = new Order([
    'order_no' => 'ORD2025001',
    'subtotal' => new Money(500000, new Currency('CNY')), // 5000.00
    'shipping' => new Money(1000, new Currency('CNY')),   // 10.00
    'tax' => new Money(25000, new Currency('CNY')),       // 250.00
    'discount' => new Money(50000, new Currency('CNY')),  // 500.00
]);

$order->calculateTotal();
$order->save();

echo "订单号：{$order->order_no}\n";
echo "小计：" . $order->subtotal->getAmount() / 100 . "\n";
echo "运费：" . $order->shipping->getAmount() / 100 . "\n";
echo "税费：" . $order->tax->getAmount() / 100 . "\n";
echo "优惠：" . $order->discount->getAmount() / 100 . "\n";
echo "总计：" . $order->total->getAmount() / 100 . "\n";
```

## 场景三：积分系统

### 配置

```php
// config/money.php
'custom' => [
    'POINTS' => [
        'name' => '积分',
        'code' => 'POINTS',
        'subunit' => 0,
        'numeric_code' => 999,
    ],
],
```

### 数据库迁移

```php
Schema::create('user_points', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->bigInteger('balance_amount')->default(0);
    $table->string('balance_currency', 10)->default('POINTS');
    $table->timestamps();
});

Schema::create('points_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->bigInteger('amount_amount');
    $table->string('amount_currency', 10)->default('POINTS');
    $table->enum('type', ['earn', 'spend', 'refund']);
    $table->string('description');
    $table->timestamps();
});
```

### Model 定义

```php
class UserPoints extends Model
{
    protected $casts = [
        'balance' => MoneyCast::class . ':null,null,null,bigint',
    ];
    
    public function earn(Money $points, string $description): void
    {
        DB::transaction(function () use ($points, $description) {
            $this->balance = $this->balance->add($points);
            $this->save();
            
            PointsTransaction::create([
                'user_id' => $this->user_id,
                'amount' => $points,
                'type' => 'earn',
                'description' => $description,
            ]);
        });
    }
    
    public function spend(Money $points, string $description): void
    {
        if ($this->balance->lessThan($points)) {
            throw new \Exception('积分不足');
        }
        
        DB::transaction(function () use ($points, $description) {
            $this->balance = $this->balance->subtract($points);
            $this->save();
            
            PointsTransaction::create([
                'user_id' => $this->user_id,
                'amount' => $points,
                'type' => 'spend',
                'description' => $description,
            ]);
        });
    }
}

class PointsTransaction extends Model
{
    protected $fillable = ['user_id', 'amount', 'type', 'description'];
    
    protected $casts = [
        'amount' => MoneyCast::class . ':null,null,null,bigint',
    ];
}
```

### 使用

```php
use Money\Money;
use Money\Currency;

$userPoints = UserPoints::firstOrCreate(['user_id' => 1]);

// 获得积分
$userPoints->earn(
    new Money(100, new Currency('POINTS')),
    '签到奖励'
);

// 消费积分
$userPoints->spend(
    new Money(50, new Currency('POINTS')),
    '兑换商品'
);

echo "当前积分：" . $userPoints->balance->getAmount() . "\n";
```

## 场景四：钱包系统（多币种）

### 数据库迁移

```php
Schema::create('wallets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id');
    $table->bigInteger('balance_amount')->default(0);
    $table->string('balance_currency', 10);
    $table->unique(['user_id', 'balance_currency']);
    $table->timestamps();
});
```

### Model 定义

```php
class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance'];
    
    protected $casts = [
        'balance' => MoneyCast::class . ':null,null,null,bigint',
    ];
    
    public static function getUserWallet(int $userId, string $currency): self
    {
        return static::firstOrCreate(
            ['user_id' => $userId, 'balance_currency' => $currency],
            ['balance' => new Money(0, new Currency($currency))]
        );
    }
    
    public function deposit(Money $amount): void
    {
        $this->balance = $this->balance->add($amount);
        $this->save();
    }
    
    public function withdraw(Money $amount): void
    {
        if ($this->balance->lessThan($amount)) {
            throw new \Exception('余额不足');
        }
        
        $this->balance = $this->balance->subtract($amount);
        $this->save();
    }
}
```

### 使用

```php
use Money\Money;
use Money\Currency;

$userId = 1;

// CNY 钱包
$cnyWallet = Wallet::getUserWallet($userId, 'CNY');
$cnyWallet->deposit(new Money(100000, new Currency('CNY'))); // 充值 1000.00 元

// 积分钱包
$pointsWallet = Wallet::getUserWallet($userId, 'POINTS');
$pointsWallet->deposit(new Money(500, new Currency('POINTS'))); // 获得 500 积分

// 查询余额
echo "人民币余额：" . $cnyWallet->balance->getAmount() / 100 . " 元\n";
echo "积分余额：" . $pointsWallet->balance->getAmount() . " 分\n";
```

## 场景五：优惠券系统

### 配置

```php
// config/money.php
'custom' => [
    'VOUCHER' => [
        'name' => '代金券',
        'code' => 'VOUCHER',
        'subunit' => 2,
        'numeric_code' => 994,
    ],
],
```

### Model 定义

```php
class Coupon extends Model
{
    protected $casts = [
        'discount_amount' => MoneyCast::class,
        'min_purchase_amount' => MoneyCast::class,
        'max_discount_amount' => MoneyCast::class,
    ];
    
    public function calculateDiscount(Money $orderAmount): Money
    {
        // 检查最低消费
        if ($this->min_purchase_amount && $orderAmount->lessThan($this->min_purchase_amount)) {
            return new Money(0, $orderAmount->getCurrency());
        }
        
        $discount = $this->discount_amount;
        
        // 检查最大优惠
        if ($this->max_discount_amount && $discount->greaterThan($this->max_discount_amount)) {
            $discount = $this->max_discount_amount;
        }
        
        // 确保不超过订单金额
        if ($discount->greaterThan($orderAmount)) {
            $discount = $orderAmount;
        }
        
        return $discount;
    }
}
```

### 使用

```php
$coupon = Coupon::create([
    'code' => 'SAVE50',
    'discount_amount' => '50.00',
    'min_purchase_amount' => '200.00',
    'max_discount_amount' => '100.00',
]);

$orderAmount = new Money(30000, new Currency('CNY')); // 300.00 元
$discount = $coupon->calculateDiscount($orderAmount);

echo "订单金额：" . $orderAmount->getAmount() / 100 . " 元\n";
echo "优惠金额：" . $discount->getAmount() / 100 . " 元\n";
echo "实付金额：" . $orderAmount->subtract($discount)->getAmount() / 100 . " 元\n";
```

## 场景六：佣金计算

```php
class Commission
{
    public static function calculate(Money $sales, float $rate): Money
    {
        // 计算佣金（使用乘法和四舍五入）
        return $sales->multiply($rate);
    }
}

// 使用
$sales = new Money(1000000, new Currency('CNY')); // 销售额 10000.00
$commission = Commission::calculate($sales, 0.05);   // 5% 佣金

echo "销售额：" . $sales->getAmount() / 100 . " 元\n";
echo "佣金（5%）：" . $commission->getAmount() / 100 . " 元\n";
```

## 更多示例

查看测试文件获取更多使用示例。

