# BaseCommandHandler 深度优化建议

基于 Java DDD 优秀实践（阿里 COLA、Spring、Axon Framework），提供更深层次的架构优化建议。

## 1. 领域事件机制（Domain Events）

### Java DDD 实践

**示例 1：Spring 领域事件**
```java
@Entity
public class Product extends AbstractAggregateRoot<Product> {
    
    public void create(ProductData data) {
        // 业务逻辑
        this.initialize(data);
        
        // 注册领域事件
        registerEvent(new ProductCreatedEvent(this));
    }
    
    public void update(ProductData data) {
        this.updateInfo(data);
        registerEvent(new ProductUpdatedEvent(this));
    }
}

// 事件发布（自动）
@Service
public class ProductApplicationService {
    
    @Transactional
    public Product create(CreateCommand cmd) {
        Product product = factory.create();
        product.create(cmd);
        repository.save(product);
        // 事务提交后自动发布事件
        return product;
    }
}

// 事件监听
@Component
public class ProductEventListener {
    
    @EventListener
    @Async
    public void handleProductCreated(ProductCreatedEvent event) {
        // 发送通知、更新缓存、记录日志等
        notificationService.notifyProductCreated(event.getProduct());
    }
}
```

**示例 2：Axon Framework 事件溯源**
```java
@Aggregate
public class Product {
    
    @CommandHandler
    public Product(CreateProductCommand cmd) {
        // 应用事件（事件溯源）
        apply(new ProductCreatedEvent(cmd.getId(), cmd.getName()));
    }
    
    @EventSourcingHandler
    public void on(ProductCreatedEvent event) {
        this.id = event.getId();
        this.name = event.getName();
    }
}
```

### PHP 实现方案

#### 方案 A：Laravel 原生事件系统

```php
<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Product\Events\ProductCreated;
use RedJasmine\Product\Domain\Product\Events\ProductUpdated;

/**
 * 商品聚合根
 */
class Product extends Model
{
    /**
     * 创建商品（业务逻辑 + 发布事件）
     */
    public function createProduct(ProductData $data): void
    {
        $this->initialize($data);
        
        // 发布领域事件
        event(new ProductCreated($this));
    }
    
    /**
     * 更新商品（业务逻辑 + 发布事件）
     */
    public function updateProduct(ProductData $data): void
    {
        $this->updateInfo($data);
        
        // 发布领域事件
        event(new ProductUpdated($this));
    }
}

// 领域事件定义
namespace RedJasmine\Product\Domain\Product\Events;

class ProductCreated
{
    public function __construct(public Product $product) {}
}

// 事件监听器
namespace RedJasmine\Product\Application\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

class ProductCreatedListener implements ShouldQueue
{
    public function handle(ProductCreated $event): void
    {
        // 异步处理：发送通知、更新缓存、记录日志
        $this->notificationService->notifyProductCreated($event->product);
        $this->cacheService->clearProductCache($event->product->id);
    }
}
```

#### 方案 B：聚合根事件注册模式（推荐）

```php
<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * 聚合根基类（支持领域事件）
 */
abstract class AggregateRoot extends Model
{
    /**
     * 领域事件集合
     */
    protected Collection $domainEvents;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->domainEvents = collect();
    }
    
    /**
     * 注册领域事件
     */
    protected function registerEvent(object $event): void
    {
        $this->domainEvents->push($event);
    }
    
    /**
     * 获取并清空领域事件
     */
    public function pullDomainEvents(): Collection
    {
        $events = $this->domainEvents;
        $this->domainEvents = collect();
        return $events;
    }
}

// 商品聚合根
class Product extends AggregateRoot
{
    public function createProduct(ProductData $data): void
    {
        $this->initialize($data);
        
        // 注册领域事件（不立即发布）
        $this->registerEvent(new ProductCreated($this));
    }
}

// 命令处理器中发布事件
class ProductCreateCommandHandler extends BaseCommandHandler
{
    protected function persist(HandleContext $context): void
    {
        $product = $context->getModel();
        
        // 保存
        $this->service->repository->store($product);
        
        // 发布领域事件
        $this->publishDomainEvents($product);
    }
    
    protected function publishDomainEvents(AggregateRoot $aggregate): void
    {
        foreach ($aggregate->pullDomainEvents() as $event) {
            event($event);
        }
    }
}
```

### 优势

✅ **解耦**：业务逻辑和副作用（通知、缓存）解耦  
✅ **可追溯**：所有业务变更都有事件记录  
✅ **异步处理**：副作用可以异步处理，提高性能  
✅ **扩展性**：新增事件监听器无需修改核心业务代码

---

## 2. 规约模式（Specification Pattern）

### Java DDD 实践

```java
// 规约接口
public interface Specification<T> {
    boolean isSatisfiedBy(T candidate);
    Specification<T> and(Specification<T> other);
    Specification<T> or(Specification<T> other);
    Specification<T> not();
}

// 具体规约
public class ProductCanBeSoldSpecification implements Specification<Product> {
    
    @Override
    public boolean isSatisfiedBy(Product product) {
        return product.getStatus() == ProductStatus.ON_SALE
            && product.getStock() > 0
            && !product.isExpired();
    }
}

// 使用规约
public class ProductDomainService {
    
    private final ProductCanBeSoldSpecification canBeSoldSpec = 
        new ProductCanBeSoldSpecification();
    
    public void validateSale(Product product) {
        if (!canBeSoldSpec.isSatisfiedBy(product)) {
            throw new BusinessException("商品不允许销售");
        }
    }
}
```

### PHP 实现方案

```php
<?php

namespace RedJasmine\Support\Domain\Specifications;

/**
 * 规约接口
 */
interface Specification
{
    public function isSatisfiedBy($candidate): bool;
    
    public function and(Specification $other): Specification;
    
    public function or(Specification $other): Specification;
    
    public function not(): Specification;
}

/**
 * 抽象规约基类
 */
abstract class AbstractSpecification implements Specification
{
    public function and(Specification $other): Specification
    {
        return new AndSpecification($this, $other);
    }
    
    public function or(Specification $other): Specification
    {
        return new OrSpecification($this, $other);
    }
    
    public function not(): Specification
    {
        return new NotSpecification($this);
    }
}

// 组合规约
class AndSpecification extends AbstractSpecification
{
    public function __construct(
        private Specification $left,
        private Specification $right
    ) {}
    
    public function isSatisfiedBy($candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) 
            && $this->right->isSatisfiedBy($candidate);
    }
}

// 商品规约示例
namespace RedJasmine\Product\Domain\Product\Specifications;

/**
 * 商品可售规约
 */
class ProductCanBeSoldSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($product): bool
    {
        if (!$product instanceof Product) {
            return false;
        }
        
        return $product->isOnSale()
            && $product->hasStock()
            && !$product->isExpired()
            && $product->isApproved();
    }
}

/**
 * 商品库存充足规约
 */
class ProductStockSufficientSpecification extends AbstractSpecification
{
    public function __construct(private int $requiredQuantity) {}
    
    public function isSatisfiedBy($product): bool
    {
        return $product->stock >= $this->requiredQuantity;
    }
}

/**
 * 商品价格合理规约
 */
class ProductPriceValidSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($product): bool
    {
        return $product->price > 0 
            && $product->price >= $product->costPrice;
    }
}

// 在领域服务中使用
class ProductDomainService extends DomainService
{
    /**
     * 验证商品是否可以销售
     */
    public function validateCanBeSold(Product $product, int $quantity): void
    {
        // 组合规约
        $spec = (new ProductCanBeSoldSpecification())
            ->and(new ProductStockSufficientSpecification($quantity))
            ->and(new ProductPriceValidSpecification());
        
        if (!$spec->isSatisfiedBy($product)) {
            throw new ProductException('商品不满足销售条件');
        }
    }
}
```

### 优势

✅ **业务规则封装**：复杂业务规则独立封装  
✅ **可组合**：规约可以灵活组合  
✅ **可测试**：规约可以独立测试  
✅ **可复用**：规约可以在多处复用

---

## 3. 工厂模式（Factory Pattern）

### Java DDD 实践

```java
// 工厂接口
public interface ProductFactory {
    Product create(ProductData data);
    Product reconstitute(ProductSnapshot snapshot);
}

// 工厂实现
@Component
public class ProductFactoryImpl implements ProductFactory {
    
    @Override
    public Product create(ProductData data) {
        Product product = new Product();
        product.setId(snowflakeIdGenerator.generate());
        product.initialize(data);
        return product;
    }
    
    @Override
    public Product reconstitute(ProductSnapshot snapshot) {
        // 从快照重建聚合根
        Product product = new Product();
        product.restore(snapshot);
        return product;
    }
}
```

### PHP 实现方案

```php
<?php

namespace RedJasmine\Product\Domain\Product\Factories;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Data\Product as ProductData;
use RedJasmine\Support\Helpers\ID\Snowflake;

/**
 * 商品工厂
 * 
 * 职责：
 * 1. 创建新的商品聚合根
 * 2. 处理复杂的创建逻辑
 * 3. 确保商品的完整性和一致性
 */
class ProductFactory
{
    public function __construct(
        protected Snowflake $snowflake,
    ) {}
    
    /**
     * 创建新商品
     */
    public function create(ProductData $data): Product
    {
        $product = new Product();
        
        // 生成ID
        $product->id = $this->snowflake->id();
        
        // 设置所有者信息
        $product->owner_type = $data->owner->getType();
        $product->owner_id = $data->owner->getID();
        
        // 初始化状态
        $product->status = ProductStatus::DRAFT;
        
        // 设置默认值
        $product->sales = 0;
        $product->views = 0;
        $product->favorites = 0;
        
        return $product;
    }
    
    /**
     * 创建变体商品
     */
    public function createWithVariants(ProductData $data): Product
    {
        $product = $this->create($data);
        
        // 处理变体创建的复杂逻辑
        foreach ($data->variants as $variantData) {
            $variant = $this->createVariant($product, $variantData);
            $product->variants->add($variant);
        }
        
        return $product;
    }
    
    /**
     * 创建变体
     */
    protected function createVariant(Product $product, VariantData $data): ProductVariant
    {
        $variant = new ProductVariant();
        $variant->id = $this->snowflake->id();
        $variant->product_id = $product->id;
        $variant->owner_type = $product->owner_type;
        $variant->owner_id = $product->owner_id;
        
        // 复制主商品信息
        $variant->title = $product->title;
        $variant->price = $data->price ?? $product->price;
        
        return $variant;
    }
}

// 在领域服务中使用工厂
class ProductDomainService extends DomainService
{
    public function __construct(
        protected ProductFactory $factory,
        // ... 其他依赖
    ) {}
    
    public function create(ProductData $data): Product
    {
        // 使用工厂创建商品
        $product = $data->hasVariants 
            ? $this->factory->createWithVariants($data)
            : $this->factory->create($data);
        
        // 执行业务逻辑
        $product->initialize($data);
        $product->calculatePrice();
        
        return $product;
    }
}
```

### 优势

✅ **封装创建逻辑**：复杂的创建逻辑集中管理  
✅ **保证完整性**：确保聚合根创建时的完整性  
✅ **ID 生成**：统一的 ID 生成策略  
✅ **默认值管理**：统一管理默认值

---

## 4. 值对象增强（Value Objects）

### Java DDD 实践

```java
// 值对象（不可变）
@Value
public class Money {
    private BigDecimal amount;
    private Currency currency;
    
    public Money add(Money other) {
        if (!this.currency.equals(other.currency)) {
            throw new IllegalArgumentException("Currency mismatch");
        }
        return new Money(this.amount.add(other.amount), this.currency);
    }
    
    public Money multiply(int quantity) {
        return new Money(this.amount.multiply(BigDecimal.valueOf(quantity)), this.currency);
    }
}

// 使用值对象
public class Product {
    private Money price;
    
    public Money calculateTotalPrice(int quantity) {
        return price.multiply(quantity);
    }
}
```

### PHP 实现方案

```php
<?php

namespace RedJasmine\Product\Domain\Product\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 价格值对象（不可变）
 */
class Price extends ValueObject
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency = 'CNY'
    ) {
        if ($amount < 0) {
            throw new \InvalidArgumentException('价格不能为负数');
        }
    }
    
    /**
     * 加法
     */
    public function add(Price $other): Price
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('货币类型不一致');
        }
        
        return new Price($this->amount + $other->amount, $this->currency);
    }
    
    /**
     * 乘法
     */
    public function multiply(int $quantity): Price
    {
        return new Price($this->amount * $quantity, $this->currency);
    }
    
    /**
     * 是否大于
     */
    public function greaterThan(Price $other): bool
    {
        return $this->amount > $other->amount;
    }
    
    /**
     * 格式化显示
     */
    public function format(): string
    {
        return match($this->currency) {
            'CNY' => '¥' . number_format($this->amount, 2),
            'USD' => '$' . number_format($this->amount, 2),
            default => $this->currency . ' ' . number_format($this->amount, 2),
        };
    }
}

/**
 * 商品属性值对象
 */
class ProductAttribute extends ValueObject
{
    public function __construct(
        public readonly int $attributeId,
        public readonly string $attributeName,
        public readonly int $valueId,
        public readonly string $valueName,
    ) {}
    
    public function equals(ProductAttribute $other): bool
    {
        return $this->attributeId === $other->attributeId
            && $this->valueId === $other->valueId;
    }
}

/**
 * SKU 编码值对象
 */
class SkuCode extends ValueObject
{
    private const PATTERN = '/^[A-Z0-9]{6,20}$/';
    
    public function __construct(public readonly string $value)
    {
        if (!preg_match(self::PATTERN, $value)) {
            throw new \InvalidArgumentException('SKU编码格式不正确');
        }
    }
    
    public function __toString(): string
    {
        return $this->value;
    }
}

// 在领域模型中使用值对象
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'price' => Price::class,  // 自动转换为值对象
            'cost_price' => Price::class,
        ];
    }
    
    /**
     * 计算总价
     */
    public function calculateTotalPrice(int $quantity): Price
    {
        return $this->price->multiply($quantity);
    }
    
    /**
     * 是否有利润
     */
    public function hasProfitMargin(): bool
    {
        return $this->price->greaterThan($this->cost_price);
    }
}
```

### 优势

✅ **业务概念明确**：用对象表达业务概念  
✅ **类型安全**：编译时类型检查  
✅ **不可变性**：避免意外修改  
✅ **业务逻辑封装**：业务规则集中在值对象中

---

## 5. 仓储模式增强

### Java DDD 实践

```java
// 仓储接口
public interface ProductRepository {
    Product findById(Long id);
    List<Product> findByOwner(Owner owner);
    List<Product> findByCriteria(ProductCriteria criteria);
    void save(Product product);
    void delete(Product product);
}

// 规范查询
public interface Specification<T> {
    Predicate toPredicate(Root<T> root, CriteriaQuery<?> query, CriteriaBuilder cb);
}

public interface SpecificationRepository<T> {
    List<T> findAll(Specification<T> spec);
    Page<T> findAll(Specification<T> spec, Pageable pageable);
}
```

### PHP 实现方案

```php
<?php

namespace RedJasmine\Support\Domain\Repositories;

/**
 * 规范查询接口
 */
interface SpecificationRepository
{
    /**
     * 根据规范查询
     */
    public function findBySpecification(Specification $spec): Collection;
    
    /**
     * 根据规范分页查询
     */
    public function paginateBySpecification(Specification $spec, int $perPage = 15): LengthAwarePaginator;
}

// 商品仓储接口
interface ProductRepositoryInterface extends RepositoryInterface, SpecificationRepository
{
    /**
     * 根据所有者查询
     */
    public function findByOwner(OwnerInterface $owner): Collection;
    
    /**
     * 根据状态查询
     */
    public function findByStatus(ProductStatus $status): Collection;
    
    /**
     * 根据分类查询
     */
    public function findByCategory(int $categoryId): Collection;
    
    /**
     * 批量保存
     */
    public function batchStore(Collection $products): void;
}

// 仓储实现
class ProductRepository extends Repository implements ProductRepositoryInterface
{
    protected static string $modelClass = Product::class;
    
    public function findByOwner(OwnerInterface $owner): Collection
    {
        return static::$modelClass::query()
            ->where('owner_type', $owner->getType())
            ->where('owner_id', $owner->getID())
            ->get();
    }
    
    public function findBySpecification(Specification $spec): Collection
    {
        $query = static::$modelClass::query();
        
        // 应用规约到查询
        $spec->applyToQuery($query);
        
        return $query->get();
    }
    
    public function batchStore(Collection $products): void
    {
        foreach ($products as $product) {
            $this->store($product);
        }
    }
}
```

---

## 6. 完整的命令处理器模板

综合以上所有模式，提供一个完整的命令处理器模板：

```php
<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\Commands\BaseCommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;

/**
 * 商品创建命令处理器（最佳实践版）
 */
class ProductCreateCommandHandler extends ProductCommandHandler
{
    protected string $name = 'create';

    /**
     * 步骤1：基础入参验证（事务外，快速失败）
     */
    protected function validate(Data $command): void
    {
        // 轻量级验证
        if (empty($command->title)) {
            throw new ProductException('商品标题不能为空');
        }
    }

    /**
     * 步骤2：解析领域模型（事务内）
     * 
     * 使用工厂模式创建聚合根
     */
    protected function resolve(Data $command): Model
    {
        // ✅ 使用工厂创建聚合根
        return $this->productFactory->create($command);
    }

    /**
     * 步骤3：执行核心业务逻辑（事务内）
     * 
     * 调用领域服务，领域服务内部：
     * - 业务规则验证（使用规约模式）
     * - 业务逻辑处理（调用领域模型方法）
     * - 注册领域事件
     */
    protected function execute(HandleContext $context): void
    {
        $product = $context->getModel();
        $command = $context->getCommand();

        // ✅ 应用层只负责编排，调用领域服务
        $this->productDomainService->create($product, $command);

        $context->setModel($product);
    }

    /**
     * 步骤4：持久化到仓库（事务内）
     * 
     * 保存聚合根，发布领域事件
     */
    protected function persist(HandleContext $context): void
    {
        $product = $context->getModel();
        $command = $context->getCommand();

        // 保存主聚合根
        $this->service->repository->store($product);

        // 处理关联数据
        $this->handleVariants($product, $command);
        $this->handleStock($product, $command);
        
        // ✅ 发布领域事件（事务提交后）
        $this->publishDomainEvents($product);
    }
    
    /**
     * 发布领域事件
     */
    protected function publishDomainEvents(AggregateRoot $aggregate): void
    {
        foreach ($aggregate->pullDomainEvents() as $event) {
            event($event);
        }
    }
}
```

## 架构分层总结

```
┌─────────────────────────────────────────────────────────────┐
│  用户接口层 (UI Layer)                                         │
│  - Controllers (REST API, GraphQL)                           │
│  - Resources (API Response)                                  │
│  - Requests (Validation)                                     │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│  应用层 (Application Layer)                                   │
│  - ApplicationService (门面)                                 │
│  - CommandHandler (流程编排)                                 │
│    * validate()  - 基础验证                                   │
│    * resolve()   - 解析模型（使用工厂）                        │
│    * execute()   - 调用领域服务                               │
│    * persist()   - 持久化 + 发布事件                          │
│  - QueryHandler (查询处理)                                    │
│  - Commands/Queries (CQRS)                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│  领域层 (Domain Layer)                                        │
│  - AggregateRoot (聚合根)                                    │
│    * 业务行为方法                                             │
│    * 领域事件注册                                             │
│  - DomainService (领域服务)                                  │
│    * 业务规则验证（使用规约）                                  │
│    * 业务逻辑编排                                             │
│  - ValueObject (值对象)                                      │
│  - Factory (工厂)                                            │
│  - Specification (规约)                                      │
│  - DomainEvent (领域事件)                                    │
│  - Repository Interface (仓储接口)                           │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│  基础设施层 (Infrastructure Layer)                            │
│  - Repository Implementation (仓储实现)                       │
│  - ORM Mapping (Eloquent)                                   │
│  - External Services (外部服务)                              │
│  - Event Publishing (事件发布)                               │
└─────────────────────────────────────────────────────────────┘
```

## 迁移路线图

### 第一阶段：基础重构（1-2周）
- [ ] 更新 `BaseCommandHandler`，实现 4 步流程
- [ ] 完善 `ProductDomainService`，添加业务逻辑方法
- [ ] 重构现有命令处理器，使用新流程
- [ ] 添加单元测试

### 第二阶段：模式引入（2-3周）
- [ ] 引入工厂模式
- [ ] 实现领域事件机制
- [ ] 添加值对象
- [ ] 引入规约模式

### 第三阶段：完善优化（1-2周）
- [ ] 增强仓储模式
- [ ] 完善聚合根
- [ ] 添加集成测试
- [ ] 性能优化

## 总结

通过对比 Java DDD 优秀实践，提出以下关键改进：

1. **领域服务完善**：从"只验证"到"完整业务逻辑"
2. **领域事件机制**：实现事件驱动架构
3. **规约模式**：封装复杂业务规则
4. **工厂模式**：统一聚合根创建
5. **值对象增强**：用对象表达业务概念
6. **仓储模式增强**：支持规范查询

这些改进将使架构：
- ✅ 更符合 DDD 原则
- ✅ 更易于测试和维护
- ✅ 更高的业务逻辑复用性
- ✅ 更好的扩展性

