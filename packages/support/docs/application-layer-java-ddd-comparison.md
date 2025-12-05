# 应用层设计：Java DDD 模式对比与深度优化方案

## 📚 文档说明

本文档基于 [应用层设计分析与优化方案](./application-layer-design-analysis.md)，对比 Java DDD 模式的优秀实践，提供更深层次的优化建议。

---

## 🔍 Java DDD 优秀实践对比

### 1. Spring Boot DDD 典型架构

```java
// Java 典型的应用服务实现
@Service
@Transactional
public class OrderApplicationService {
    
    private final OrderRepository orderRepository;
    private final DomainEventPublisher eventPublisher;
    private final ApplicationEventPublisher applicationEventPublisher;
    
    @Autowired
    public OrderApplicationService(
        OrderRepository orderRepository,
        DomainEventPublisher eventPublisher,
        ApplicationEventPublisher applicationEventPublisher
    ) {
        this.orderRepository = orderRepository;
        this.eventPublisher = eventPublisher;
        this.applicationEventPublisher = applicationEventPublisher;
    }
    
    public OrderDTO createOrder(CreateOrderCommand command) {
        // 1. 验证命令
        validateCommand(command);
        
        // 2. 创建聚合根
        Order order = Order.create(command.getCustomerId(), command.getItems());
        
        // 3. 执行业务逻辑（在聚合根内部）
        order.calculateTotal();
        
        // 4. 持久化
        orderRepository.save(order);
        
        // 5. 发布领域事件
        order.getDomainEvents().forEach(eventPublisher::publish);
        
        // 6. 返回 DTO
        return OrderDTO.from(order);
    }
}
```

### 2. CQRS 模式实现

```java
// 命令端
@Service
public class OrderCommandService {
    public void handle(CreateOrderCommand command) {
        // 处理写操作
    }
}

// 查询端
@Service
public class OrderQueryService {
    public OrderDTO findById(Long id) {
        // 处理读操作，可能从读模型查询
    }
}
```

### 3. 事件溯源模式

```java
// 事件存储
@Service
public class EventSourcingService {
    private final EventStore eventStore;
    
    public void save(AggregateRoot aggregate) {
        List<DomainEvent> events = aggregate.getUncommittedEvents();
        eventStore.saveEvents(aggregate.getId(), events);
        aggregate.markEventsAsCommitted();
    }
    
    public <T extends AggregateRoot> T load(String aggregateId, Class<T> type) {
        List<DomainEvent> events = eventStore.getEvents(aggregateId);
        T aggregate = type.newInstance();
        aggregate.loadFromHistory(events);
        return aggregate;
    }
}
```

---

## 🎯 深度优化方案

### 优化 1：引入 UnitOfWork（工作单元）模式

#### Java 实现参考
```java
public interface UnitOfWork {
    void registerNew(Entity entity);
    void registerDirty(Entity entity);
    void registerClean(Entity entity);
    void registerDeleted(Entity entity);
    void commit();
    void rollback();
}

@Service
public class UnitOfWorkImpl implements UnitOfWork {
    private Set<Entity> newEntities = new HashSet<>();
    private Set<Entity> dirtyEntities = new HashSet<>();
    private Set<Entity> deletedEntities = new HashSet<>();
    
    @Transactional
    public void commit() {
        // 批量处理所有变更
        insertNew();
        updateDirty();
        deleteRemoved();
        clear();
    }
}
```

#### PHP 优化实现
```php
<?php

namespace RedJasmine\Support\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * 工作单元模式
 * 用于跟踪和管理实体的状态变化
 */
class UnitOfWork
{
    /**
     * 新增的实体
     */
    protected Collection $newEntities;
    
    /**
     * 修改的实体
     */
    protected Collection $dirtyEntities;
    
    /**
     * 删除的实体
     */
    protected Collection $deletedEntities;
    
    /**
     * 干净的实体（已持久化且未修改）
     */
    protected Collection $cleanEntities;
    
    public function __construct()
    {
        $this->newEntities = collect();
        $this->dirtyEntities = collect();
        $this->deletedEntities = collect();
        $this->cleanEntities = collect();
    }
    
    /**
     * 注册新实体
     */
    public function registerNew(Model $entity): void
    {
        if ($this->isRegistered($entity)) {
            throw new \LogicException('Entity already registered');
        }
        
        $this->newEntities->push($entity);
    }
    
    /**
     * 注册修改的实体
     */
    public function registerDirty(Model $entity): void
    {
        if ($this->deletedEntities->contains($entity)) {
            throw new \LogicException('Cannot register dirty a deleted entity');
        }
        
        if (!$this->dirtyEntities->contains($entity) && !$this->newEntities->contains($entity)) {
            $this->dirtyEntities->push($entity);
        }
    }
    
    /**
     * 注册删除的实体
     */
    public function registerDeleted(Model $entity): void
    {
        if ($this->newEntities->contains($entity)) {
            $this->newEntities = $this->newEntities->reject(fn($e) => $e === $entity);
            return;
        }
        
        $this->dirtyEntities = $this->dirtyEntities->reject(fn($e) => $e === $entity);
        
        if (!$this->deletedEntities->contains($entity)) {
            $this->deletedEntities->push($entity);
        }
    }
    
    /**
     * 注册干净的实体
     */
    public function registerClean(Model $entity): void
    {
        if (!$this->cleanEntities->contains($entity)) {
            $this->cleanEntities->push($entity);
        }
    }
    
    /**
     * 提交所有变更
     */
    public function commit(): void
    {
        DB::transaction(function () {
            // 1. 插入新实体
            $this->insertNew();
            
            // 2. 更新修改的实体
            $this->updateDirty();
            
            // 3. 删除标记删除的实体
            $this->deleteRemoved();
            
            // 4. 清空跟踪列表
            $this->clear();
        });
    }
    
    /**
     * 回滚所有变更
     */
    public function rollback(): void
    {
        $this->clear();
    }
    
    /**
     * 检查实体是否已注册
     */
    protected function isRegistered(Model $entity): bool
    {
        return $this->newEntities->contains($entity)
            || $this->dirtyEntities->contains($entity)
            || $this->deletedEntities->contains($entity)
            || $this->cleanEntities->contains($entity);
    }
    
    /**
     * 插入新实体
     */
    protected function insertNew(): void
    {
        foreach ($this->newEntities as $entity) {
            $entity->save();
        }
    }
    
    /**
     * 更新修改的实体
     */
    protected function updateDirty(): void
    {
        foreach ($this->dirtyEntities as $entity) {
            $entity->save();
        }
    }
    
    /**
     * 删除标记删除的实体
     */
    protected function deleteRemoved(): void
    {
        foreach ($this->deletedEntities as $entity) {
            $entity->delete();
        }
    }
    
    /**
     * 清空所有跟踪列表
     */
    protected function clear(): void
    {
        $this->newEntities = collect();
        $this->dirtyEntities = collect();
        $this->deletedEntities = collect();
        $this->cleanEntities = collect();
    }
    
    /**
     * 获取所有变更的实体数量
     */
    public function getChangeCount(): int
    {
        return $this->newEntities->count() 
             + $this->dirtyEntities->count() 
             + $this->deletedEntities->count();
    }
}
```

#### 集成到 ApplicationService
```php
abstract class ApplicationService extends Service
{
    /**
     * 工作单元
     */
    protected ?UnitOfWork $unitOfWork = null;
    
    /**
     * 获取工作单元
     */
    public function getUnitOfWork(): UnitOfWork
    {
        if (!$this->unitOfWork) {
            $this->unitOfWork = new UnitOfWork();
        }
        return $this->unitOfWork;
    }
    
    /**
     * 提交工作单元
     */
    public function commitUnitOfWork(): void
    {
        if ($this->unitOfWork) {
            $this->unitOfWork->commit();
        }
    }
    
    /**
     * 回滚工作单元
     */
    public function rollbackUnitOfWork(): void
    {
        if ($this->unitOfWork) {
            $this->unitOfWork->rollback();
        }
    }
}
```

---

### 优化 2：引入 Specification（规约）模式

#### Java 实现参考
```java
public interface Specification<T> {
    boolean isSatisfiedBy(T candidate);
    Specification<T> and(Specification<T> other);
    Specification<T> or(Specification<T> other);
    Specification<T> not();
}

public class CustomerAgeSpecification implements Specification<Customer> {
    private final int minAge;
    
    public boolean isSatisfiedBy(Customer customer) {
        return customer.getAge() >= minAge;
    }
}
```

#### PHP 优化实现
```php
<?php

namespace RedJasmine\Support\Domain\Specification;

/**
 * 规约接口
 * 用于封装业务规则
 */
interface SpecificationInterface
{
    /**
     * 检查候选对象是否满足规约
     */
    public function isSatisfiedBy($candidate): bool;
    
    /**
     * 与操作
     */
    public function and(SpecificationInterface $other): SpecificationInterface;
    
    /**
     * 或操作
     */
    public function or(SpecificationInterface $other): SpecificationInterface;
    
    /**
     * 非操作
     */
    public function not(): SpecificationInterface;
}

/**
 * 抽象规约基类
 */
abstract class AbstractSpecification implements SpecificationInterface
{
    public function and(SpecificationInterface $other): SpecificationInterface
    {
        return new AndSpecification($this, $other);
    }
    
    public function or(SpecificationInterface $other): SpecificationInterface
    {
        return new OrSpecification($this, $other);
    }
    
    public function not(): SpecificationInterface
    {
        return new NotSpecification($this);
    }
}

/**
 * 与规约
 */
class AndSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationInterface $left,
        private SpecificationInterface $right
    ) {}
    
    public function isSatisfiedBy($candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) 
            && $this->right->isSatisfiedBy($candidate);
    }
}

/**
 * 或规约
 */
class OrSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationInterface $left,
        private SpecificationInterface $right
    ) {}
    
    public function isSatisfiedBy($candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) 
            || $this->right->isSatisfiedBy($candidate);
    }
}

/**
 * 非规约
 */
class NotSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationInterface $specification
    ) {}
    
    public function isSatisfiedBy($candidate): bool
    {
        return !$this->specification->isSatisfiedBy($candidate);
    }
}
```

#### 使用示例
```php
// 定义具体规约
class ArticlePublishedSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($candidate): bool
    {
        return $candidate->status === ArticleStatus::PUBLISHED;
    }
}

class ArticleVisibleSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($candidate): bool
    {
        return $candidate->is_show === true;
    }
}

class ArticleAvailableSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($candidate): bool
    {
        return $candidate->available_at <= now() 
            && (!$candidate->paused_at || $candidate->paused_at > now());
    }
}

// 组合使用
$publishedSpec = new ArticlePublishedSpecification();
$visibleSpec = new ArticleVisibleSpecification();
$availableSpec = new ArticleAvailableSpecification();

// 可展示的文章 = 已发布 AND 可见 AND 可用
$displayableSpec = $publishedSpec->and($visibleSpec)->and($availableSpec);

if ($displayableSpec->isSatisfiedBy($article)) {
    // 文章可以展示
}
```

---

### 优化 3：引入 DomainService（领域服务）层次化

#### Java 实现参考
```java
// 领域服务接口
public interface OrderPricingService {
    Money calculatePrice(Order order);
}

// 领域服务实现
@Service
public class OrderPricingServiceImpl implements OrderPricingService {
    
    private final PromotionService promotionService;
    private final DiscountService discountService;
    
    public Money calculatePrice(Order order) {
        Money basePrice = order.calculateBasePrice();
        Money discount = discountService.calculateDiscount(order);
        Money promotion = promotionService.calculatePromotion(order);
        
        return basePrice.subtract(discount).subtract(promotion);
    }
}
```

#### PHP 优化实现
```php
<?php

namespace RedJasmine\Support\Domain\Services;

/**
 * 领域服务基类
 */
abstract class DomainService
{
    /**
     * 领域服务名称
     */
    protected string $name;
    
    /**
     * 依赖的其他领域服务
     */
    protected array $dependencies = [];
    
    /**
     * 获取服务名称
     */
    public function getName(): string
    {
        return $this->name ?? static::class;
    }
}

/**
 * 领域服务容器
 * 管理领域服务的依赖关系
 */
class DomainServiceContainer
{
    protected array $services = [];
    
    /**
     * 注册领域服务
     */
    public function register(string $name, DomainService $service): void
    {
        $this->services[$name] = $service;
    }
    
    /**
     * 获取领域服务
     */
    public function get(string $name): DomainService
    {
        if (!isset($this->services[$name])) {
            throw new \RuntimeException("Domain service {$name} not found");
        }
        
        return $this->services[$name];
    }
    
    /**
     * 检查服务是否存在
     */
    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }
}
```

#### 使用示例
```php
// 定义领域服务
class OrderPricingService extends DomainService
{
    protected string $name = 'order.pricing';
    
    public function __construct(
        private PromotionService $promotionService,
        private DiscountService $discountService
    ) {}
    
    public function calculatePrice(Order $order): Money
    {
        $basePrice = $order->calculateBasePrice();
        $discount = $this->discountService->calculateDiscount($order);
        $promotion = $this->promotionService->calculatePromotion($order);
        
        return $basePrice->subtract($discount)->subtract($promotion);
    }
}

// 在应用服务中使用
class OrderApplicationService extends ApplicationService
{
    public function __construct(
        public OrderRepositoryInterface $repository,
        private OrderPricingService $pricingService
    ) {}
    
    protected static $macros = [
        'create' => OrderCreateCommandHandler::class,
    ];
}
```

---

### 优化 4：引入 AggregateRoot（聚合根）增强

#### Java 实现参考
```java
public abstract class AggregateRoot<ID> extends Entity<ID> {
    
    private final List<DomainEvent> domainEvents = new ArrayList<>();
    private int version = 0;
    
    protected void registerEvent(DomainEvent event) {
        domainEvents.add(event);
    }
    
    public List<DomainEvent> getDomainEvents() {
        return Collections.unmodifiableList(domainEvents);
    }
    
    public void clearDomainEvents() {
        domainEvents.clear();
    }
    
    public int getVersion() {
        return version;
    }
    
    public void incrementVersion() {
        this.version++;
    }
}
```

#### PHP 优化实现
```php
<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * 聚合根 Trait
 * 提供领域事件管理和版本控制
 */
trait AggregateRoot
{
    /**
     * 未提交的领域事件
     */
    protected Collection $uncommittedEvents;
    
    /**
     * 初始化聚合根
     */
    protected function initializeAggregateRoot(): void
    {
        if (!isset($this->uncommittedEvents)) {
            $this->uncommittedEvents = collect();
        }
    }
    
    /**
     * 注册领域事件
     */
    protected function registerDomainEvent(object $event): void
    {
        $this->initializeAggregateRoot();
        $this->uncommittedEvents->push($event);
    }
    
    /**
     * 获取未提交的领域事件
     */
    public function getUncommittedEvents(): Collection
    {
        $this->initializeAggregateRoot();
        return $this->uncommittedEvents;
    }
    
    /**
     * 清空未提交的事件
     */
    public function clearUncommittedEvents(): void
    {
        $this->initializeAggregateRoot();
        $this->uncommittedEvents = collect();
    }
    
    /**
     * 标记事件已提交
     */
    public function markEventsAsCommitted(): void
    {
        $this->clearUncommittedEvents();
    }
    
    /**
     * 从历史事件重建聚合根
     */
    public function loadFromHistory(array $events): void
    {
        foreach ($events as $event) {
            $this->applyEvent($event, false);
        }
    }
    
    /**
     * 应用事件
     */
    protected function applyEvent(object $event, bool $isNew = true): void
    {
        $method = 'apply' . class_basename($event);
        
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
        
        if ($isNew) {
            $this->registerDomainEvent($event);
        }
    }
    
    /**
     * 增加版本号
     */
    public function incrementVersion(): void
    {
        if (property_exists($this, 'version')) {
            $this->version++;
        }
    }
    
    /**
     * 获取聚合根版本
     */
    public function getAggregateVersion(): int
    {
        return $this->version ?? 0;
    }
}
```

#### 使用示例
```php
class Order extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use AggregateRoot;  // 使用聚合根 Trait
    
    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->initializeAggregateRoot();
        });
        
        static::created(function ($model) {
            // 创建后发布事件
            $model->registerDomainEvent(new OrderCreated($model));
        });
    }
    
    /**
     * 确认订单
     */
    public function confirm(): void
    {
        if ($this->status !== OrderStatus::PENDING) {
            throw new OrderException('只有待确认订单可以确认');
        }
        
        $this->status = OrderStatus::CONFIRMED;
        $this->confirmed_at = now();
        
        // 注册领域事件
        $this->registerDomainEvent(new OrderConfirmed($this));
        
        // 增加版本号
        $this->incrementVersion();
    }
    
    /**
     * 应用订单确认事件
     */
    protected function applyOrderConfirmed(OrderConfirmed $event): void
    {
        $this->status = OrderStatus::CONFIRMED;
        $this->confirmed_at = $event->confirmedAt;
    }
}
```

---

### 优化 5：引入 CommandBus（命令总线）

#### Java 实现参考
```java
public interface CommandBus {
    <R> R execute(Command<R> command);
}

@Service
public class SimpleCommandBus implements CommandBus {
    
    private final ApplicationContext context;
    
    @Override
    public <R> R execute(Command<R> command) {
        CommandHandler<Command<R>, R> handler = findHandler(command);
        return handler.handle(command);
    }
    
    private <R> CommandHandler<Command<R>, R> findHandler(Command<R> command) {
        String handlerName = command.getClass().getSimpleName() + "Handler";
        return context.getBean(handlerName, CommandHandler.class);
    }
}
```

#### PHP 优化实现

```php
<?php

namespace RedJasmine\Support\Application\Bus;

use RedJasmine\Support\Foundation\Data\Data;

/**
 * 命令总线接口
 */
interface CommandBusInterface
{
    /**
     * 执行命令
     */
    public function execute(Data $command): mixed;
    
    /**
     * 注册命令处理器
     */
    public function register(string $commandClass, string $handlerClass): void;
}

/**
 * 简单命令总线实现
 */
class SimpleCommandBus implements CommandBusInterface
{
    /**
     * 命令处理器映射
     */
    protected array $handlers = [];
    
    /**
     * 中间件管道
     */
    protected array $middlewares = [];
    
    /**
     * 执行命令
     */
    public function execute(Data $command): mixed
    {
        $handler = $this->resolveHandler($command);
        
        // 通过中间件管道执行
        return $this->executeThrough($command, $handler);
    }
    
    /**
     * 注册命令处理器
     */
    public function register(string $commandClass, string $handlerClass): void
    {
        $this->handlers[$commandClass] = $handlerClass;
    }
    
    /**
     * 批量注册
     */
    public function registerHandlers(array $handlers): void
    {
        foreach ($handlers as $command => $handler) {
            $this->register($command, $handler);
        }
    }
    
    /**
     * 添加中间件
     */
    public function addMiddleware(callable $middleware): void
    {
        $this->middlewares[] = $middleware;
    }
    
    /**
     * 解析处理器
     */
    protected function resolveHandler(Data $command): object
    {
        $commandClass = get_class($command);
        
        if (!isset($this->handlers[$commandClass])) {
            throw new \RuntimeException("No handler registered for command: {$commandClass}");
        }
        
        return app($this->handlers[$commandClass]);
    }
    
    /**
     * 通过中间件执行
     */
    protected function executeThrough(Data $command, object $handler): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            fn($next, $middleware) => fn($cmd) => $middleware($cmd, $next),
            fn($cmd) => $handler->handle($cmd)
        );
        
        return $pipeline($command);
    }
}

/**
 * 命令总线中间件示例
 */
class LoggingMiddleware
{
    public function __invoke(Data $command, callable $next): mixed
    {
        logger()->info('Executing command', [
            'command' => get_class($command),
            'data' => $command->toArray(),
        ]);
        
        $result = $next($command);
        
        logger()->info('Command executed', [
            'command' => get_class($command),
        ]);
        
        return $result;
    }
}

class ValidationMiddleware
{
    public function __invoke(Data $command, callable $next): mixed
    {
        // 验证命令
        if (method_exists($command, 'validate')) {
            $command->validate();
        }
        
        return $next($command);
    }
}

class TransactionMiddleware
{
    public function __invoke(Data $command, callable $next): mixed
    {
        return DB::transaction(function () use ($command, $next) {
            return $next($command);
        });
    }
}
```

#### 使用示例
```php
// 注册命令总线
$commandBus = app(CommandBusInterface::class);

// 注册处理器
$commandBus->registerHandlers([
    ArticleCreateCommand::class => ArticleCreateCommandHandler::class,
    ArticleUpdateCommand::class => ArticleUpdateCommandHandler::class,
    ArticleDeleteCommand::class => ArticleDeleteCommandHandler::class,
]);

// 添加中间件
$commandBus->addMiddleware(new LoggingMiddleware());
$commandBus->addMiddleware(new ValidationMiddleware());
$commandBus->addMiddleware(new TransactionMiddleware());

// 执行命令
$command = new ArticleCreateCommand([
    'title' => '测试文章',
    'content' => '内容',
]);

$result = $commandBus->execute($command);
```

---

### 优化 6：引入 QueryBus（查询总线）

#### PHP 实现

```php
<?php

namespace RedJasmine\Support\Application\Bus;

use RedJasmine\Support\Foundation\Data\Data;

/**
 * 查询总线接口
 */
interface QueryBusInterface
{
    /**
     * 执行查询
     */
    public function execute(Data $query): mixed;
    
    /**
     * 注册查询处理器
     */
    public function register(string $queryClass, string $handlerClass): void;
}

/**
 * 简单查询总线实现
 */
class SimpleQueryBus implements QueryBusInterface
{
    protected array $handlers = [];
    protected array $middlewares = [];
    
    public function execute(Data $query): mixed
    {
        $handler = $this->resolveHandler($query);
        return $this->executeThrough($query, $handler);
    }
    
    public function register(string $queryClass, string $handlerClass): void
    {
        $this->handlers[$queryClass] = $handlerClass;
    }
    
    public function addMiddleware(callable $middleware): void
    {
        $this->middlewares[] = $middleware;
    }
    
    protected function resolveHandler(Data $query): object
    {
        $queryClass = get_class($query);
        
        if (!isset($this->handlers[$queryClass])) {
            throw new \RuntimeException("No handler registered for query: {$queryClass}");
        }
        
        return app($this->handlers[$queryClass]);
    }
    
    protected function executeThrough(Data $query, object $handler): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            fn($next, $middleware) => fn($q) => $middleware($q, $next),
            fn($q) => $handler->handle($q)
        );
        
        return $pipeline($query);
    }
}

/**
 * 查询缓存中间件
 */
class QueryCacheMiddleware
{
    public function __invoke(Data $query, callable $next): mixed
    {
        $cacheKey = $this->getCacheKey($query);
        
        return cache()->remember($cacheKey, 3600, function () use ($query, $next) {
            return $next($query);
        });
    }
    
    protected function getCacheKey(Data $query): string
    {
        return 'query:' . md5(serialize($query));
    }
}
```

---

### 优化 7：引入 EventBus（事件总线）

#### PHP 实现
```php
<?php

namespace RedJasmine\Support\Domain\Events;

/**
 * 事件总线接口
 */
interface EventBusInterface
{
    /**
     * 发布事件
     */
    public function publish(object $event): void;
    
    /**
     * 订阅事件
     */
    public function subscribe(string $eventClass, callable $handler): void;
}

/**
 * 简单事件总线实现
 */
class SimpleEventBus implements EventBusInterface
{
    protected array $subscribers = [];
    
    public function publish(object $event): void
    {
        $eventClass = get_class($event);
        
        if (isset($this->subscribers[$eventClass])) {
            foreach ($this->subscribers[$eventClass] as $handler) {
                $handler($event);
            }
        }
        
        // 同时发布到 Laravel 事件系统
        event($event);
    }
    
    public function subscribe(string $eventClass, callable $handler): void
    {
        if (!isset($this->subscribers[$eventClass])) {
            $this->subscribers[$eventClass] = [];
        }
        
        $this->subscribers[$eventClass][] = $handler;
    }
    
    /**
     * 批量订阅
     */
    public function subscribeMultiple(array $subscriptions): void
    {
        foreach ($subscriptions as $event => $handlers) {
            foreach ((array)$handlers as $handler) {
                $this->subscribe($event, $handler);
            }
        }
    }
}

/**
 * 领域事件发布器
 */
class DomainEventPublisher
{
    protected static ?self $instance = null;
    protected EventBusInterface $eventBus;
    
    private function __construct(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }
    
    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self(app(EventBusInterface::class));
        }
        return self::$instance;
    }
    
    public function publish(object $event): void
    {
        $this->eventBus->publish($event);
    }
    
    public function subscribe(string $eventClass, callable $handler): void
    {
        $this->eventBus->subscribe($eventClass, $handler);
    }
}
```

---

### 优化 8：引入 DTO Assembler（DTO 组装器）

#### Java 实现参考
```java
public interface DTOAssembler<Entity, DTO> {
    DTO toDTO(Entity entity);
    Entity toEntity(DTO dto);
    List<DTO> toDTOList(List<Entity> entities);
}

@Component
public class OrderDTOAssembler implements DTOAssembler<Order, OrderDTO> {
    
    @Override
    public OrderDTO toDTO(Order order) {
        return OrderDTO.builder()
            .id(order.getId())
            .customerId(order.getCustomerId())
            .totalAmount(order.getTotalAmount())
            .status(order.getStatus())
            .build();
    }
    
    @Override
    public Order toEntity(OrderDTO dto) {
        // 从 DTO 创建实体
    }
}
```

#### PHP 优化实现

```php
<?php

namespace RedJasmine\Support\Application\Assemblers;

use Illuminate\Database\Eloquent\Model;use Illuminate\Support\Collection;use RedJasmine\Support\Foundation\Data\Data;

/**
 * DTO 组装器接口
 */
interface AssemblerInterface
{
    /**
     * 将实体转换为 DTO
     */
    public function toDTO(Model $entity): Data;
    
    /**
     * 将 DTO 转换为实体
     */
    public function toEntity(Data $dto): Model;
    
    /**
     * 批量转换为 DTO
     */
    public function toDTOList($entities): Collection;
}

/**
 * 抽象组装器基类
 */
abstract class AbstractAssembler implements AssemblerInterface
{
    /**
     * DTO 类
     */
    protected string $dtoClass;
    
    /**
     * 实体类
     */
    protected string $entityClass;
    
    /**
     * 批量转换为 DTO
     */
    public function toDTOList($entities): Collection
    {
        return collect($entities)->map(fn($entity) => $this->toDTO($entity));
    }
    
    /**
     * 获取 DTO 类
     */
    public function getDTOClass(): string
    {
        return $this->dtoClass;
    }
    
    /**
     * 获取实体类
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }
}
```

#### 使用示例
```php
class ArticleAssembler extends AbstractAssembler
{
    protected string $dtoClass = ArticleDTO::class;
    protected string $entityClass = Article::class;
    
    public function toDTO(Model $entity): Data
    {
        return ArticleDTO::from([
            'id' => $entity->id,
            'title' => $entity->title,
            'content' => $entity->content,
            'status' => $entity->status,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at,
        ]);
    }
    
    public function toEntity(Data $dto): Model
    {
        $article = new Article();
        $article->title = $dto->title;
        $article->content = $dto->content;
        $article->status = $dto->status;
        
        return $article;
    }
}

// 在应用服务中使用
class ArticleApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleRepositoryInterface $repository,
        private ArticleAssembler $assembler
    ) {}
    
    public function findById(int $id): ArticleDTO
    {
        $article = $this->repository->find($id);
        return $this->assembler->toDTO($article);
    }
}
```

---

## 📊 完整优化后的应用层架构

### 更新后的 ApplicationService

```php
<?php

namespace RedJasmine\Support\Application;

use Closure;use Illuminate\Contracts\Pagination\Paginator;use Illuminate\Database\Eloquent\Model;use Illuminate\Pagination\LengthAwarePaginator;use RedJasmine\Support\Application\Assemblers\AssemblerInterface;use RedJasmine\Support\Application\Bus\CommandBusInterface;use RedJasmine\Support\Application\Bus\QueryBusInterface;use RedJasmine\Support\Application\Commands\CreateCommandHandler;use RedJasmine\Support\Application\Commands\DeleteCommandHandler;use RedJasmine\Support\Application\Commands\UpdateCommandHandler;use RedJasmine\Support\Application\Queries\FindQueryHandler;use RedJasmine\Support\Application\Queries\PaginateQueryHandler;use RedJasmine\Support\Domain\Data\Queries\FindQuery;use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;use RedJasmine\Support\Domain\Events\EventBusInterface;use RedJasmine\Support\Domain\Repositories\RepositoryInterface;use RedJasmine\Support\Domain\Services\DomainServiceContainer;use RedJasmine\Support\Domain\Specification\SpecificationInterface;use RedJasmine\Support\Domain\Transformer\TransformerInterface;use RedJasmine\Support\Foundation\Data\Data;use RedJasmine\Support\Foundation\Service\Service;use Throwable;

/**
 * 应用服务基类（完整优化版）
 * 
 * 集成了以下模式：
 * - 工作单元（UnitOfWork）
 * - 命令总线（CommandBus）
 * - 查询总线（QueryBus）
 * - 事件总线（EventBus）
 * - 规约模式（Specification）
 * - DTO 组装器（Assembler）
 * - 领域服务容器（DomainServiceContainer）
 * 
 * @method Model create(Data $command)
 * @method Model update(Data $command)
 * @method bool delete(Data $command)
 * @method Model find(FindQuery $query)
 * @method LengthAwarePaginator|Paginator paginate(PaginateQuery $query)
 */
abstract class ApplicationService extends Service
{
    // ==================== 核心依赖 ====================
    
    /**
     * 仓库接口
     */
    public RepositoryInterface $repository;
    
    /**
     * 转换器接口（可选）
     */
    public ?TransformerInterface $transformer = null;
    
    /**
     * DTO 组装器（可选）
     */
    public ?AssemblerInterface $assembler = null;
    
    /**
     * 验证器接口（可选）
     */
    protected ?ValidatorInterface $validator = null;
    
    // ==================== 总线系统 ====================
    
    /**
     * 命令总线
     */
    protected ?CommandBusInterface $commandBus = null;
    
    /**
     * 查询总线
     */
    protected ?QueryBusInterface $queryBus = null;
    
    /**
     * 事件总线
     */
    protected ?EventBusInterface $eventBus = null;
    
    // ==================== 工作单元 ====================
    
    /**
     * 工作单元
     */
    protected ?UnitOfWork $unitOfWork = null;
    
    // ==================== 领域服务 ====================
    
    /**
     * 领域服务容器
     */
    protected ?DomainServiceContainer $domainServices = null;
    
    // ==================== 静态配置 ====================
    
    /**
     * 模型类
     */
    protected static string $modelClass = Model::class;
    
    /**
     * Hook 名称前缀
     */
    protected static string $hookNamePrefix = '';
    
    /**
     * 预定义处理器
     */
    protected static array $handlers = [
        'create'   => CreateCommandHandler::class,
        'update'   => UpdateCommandHandler::class,
        'delete'   => DeleteCommandHandler::class,
        'find'     => FindQueryHandler::class,
        'paginate' => PaginateQueryHandler::class
    ];
    
    // ==================== 运行时状态 ====================
    
    /**
     * 查询作用域集合
     */
    protected array $queryScopes = [];
    
    /**
     * 已发布的领域事件列表
     */
    protected array $events = [];
    
    /**
     * 业务规约集合
     */
    protected array $specifications = [];
    
    // ==================== 静态方法 ====================
    
    public static function getModelClass(): string
    {
        return static::$modelClass;
    }
    
    public static function getHookNamePrefix(): string
    {
        return static::$hookNamePrefix;
    }
    
    public static function getMacros(): array
    {
        return array_merge(static::$handlers, static::$macros);
    }
    
    // ==================== 模型管理 ====================
    
    public function newModel(?Data $data = null): Model
    {
        return static::$modelClass::make();
    }
    
    // ==================== 依赖获取 ====================
    
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }
    
    public function getTransformer(): ?TransformerInterface
    {
        return $this->transformer;
    }
    
    public function getAssembler(): ?AssemblerInterface
    {
        return $this->assembler;
    }
    
    public function setValidator(ValidatorInterface $validator): self
    {
        $this->validator = $validator;
        return $this;
    }
    
    public function getValidator(): ?ValidatorInterface
    {
        return $this->validator;
    }
    
    // ==================== 工作单元 ====================
    
    public function getUnitOfWork(): UnitOfWork
    {
        if (!$this->unitOfWork) {
            $this->unitOfWork = new UnitOfWork();
        }
        return $this->unitOfWork;
    }
    
    public function commitUnitOfWork(): void
    {
        if ($this->unitOfWork) {
            $this->unitOfWork->commit();
        }
    }
    
    public function rollbackUnitOfWork(): void
    {
        if ($this->unitOfWork) {
            $this->unitOfWork->rollback();
        }
    }
    
    // ==================== 总线系统 ====================
    
    public function getCommandBus(): CommandBusInterface
    {
        if (!$this->commandBus) {
            $this->commandBus = app(CommandBusInterface::class);
        }
        return $this->commandBus;
    }
    
    public function getQueryBus(): QueryBusInterface
    {
        if (!$this->queryBus) {
            $this->queryBus = app(QueryBusInterface::class);
        }
        return $this->queryBus;
    }
    
    public function getEventBus(): EventBusInterface
    {
        if (!$this->eventBus) {
            $this->eventBus = app(EventBusInterface::class);
        }
        return $this->eventBus;
    }
    
    // ==================== 领域服务 ====================
    
    public function getDomainServices(): DomainServiceContainer
    {
        if (!$this->domainServices) {
            $this->domainServices = new DomainServiceContainer();
        }
        return $this->domainServices;
    }
    
    public function registerDomainService(string $name, DomainService $service): self
    {
        $this->getDomainServices()->register($name, $service);
        return $this;
    }
    
    // ==================== 规约模式 ====================
    
    public function addSpecification(string $name, SpecificationInterface $specification): self
    {
        $this->specifications[$name] = $specification;
        return $this;
    }
    
    public function getSpecification(string $name): ?SpecificationInterface
    {
        return $this->specifications[$name] ?? null;
    }
    
    public function checkSpecification(string $name, $candidate): bool
    {
        $spec = $this->getSpecification($name);
        return $spec ? $spec->isSatisfiedBy($candidate) : true;
    }
    
    // ==================== 验证 ====================
    
    public function validate(Data $data, array $rules = []): void
    {
        if ($this->validator) {
            $this->validator->validate($data, $rules);
        }
    }
    
    // ==================== 查询作用域 ====================
    
    public function addQueryScope(Closure $scope): self
    {
        $this->queryScopes[] = $scope;
        return $this;
    }
    
    public function addQueryScopes(array $scopes): self
    {
        foreach ($scopes as $scope) {
            $this->addQueryScope($scope);
        }
        return $this;
    }
    
    public function applyQueryScopes($query)
    {
        foreach ($this->queryScopes as $scope) {
            $scope($query);
        }
        return $query;
    }
    
    public function resetQueryScopes(): self
    {
        $this->queryScopes = [];
        return $this;
    }
    
    public function getQueryScopes(): array
    {
        return $this->queryScopes;
    }
    
    // ==================== 事件发布 ====================
    
    public function publishEvent(object $event): void
    {
        $this->events[] = $event;
        $this->getEventBus()->publish($event);
    }
    
    public function publishEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->publishEvent($event);
        }
    }
    
    public function getEvents(): array
    {
        return $this->events;
    }
    
    public function getEventsByType(string $eventClass): array
    {
        return array_filter($this->events, fn($event) => $event instanceof $eventClass);
    }
    
    public function clearEvents(): void
    {
        $this->events = [];
    }
    
    // ==================== 异常处理 ====================
    
    protected function handleException(Throwable $e): void
    {
        logger()->error('Application service error', [
            'service' => static::class,
            'model' => static::$modelClass,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        throw $e;
    }
    
    protected function handleBusinessException(Throwable $e): void
    {
        logger()->warning('Business exception', [
            'service' => static::class,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]);
        
        throw $e;
    }
    
    // ==================== 宏处理 ====================
    
    protected function makeMacro($macro, $method, $parameters)
    {
        if (is_string($macro) && class_exists($macro)) {
            return app($macro, ['service' => $this]);
        }
        return $macro;
    }
    
    /**
     * @deprecated 使用 getModelClass() 代替
     */
    public function model(): string
    {
        return static::$modelClass;
    }
}
```

---

## 📝 实施优先级

### 第一阶段（立即实施）- 基础优化
1. ✅ 明确依赖注入（repository、transformer）
2. ✅ 统一 Hook 命名规范
3. ✅ 增强 HandleContext

### 第二阶段（近期实施）- 模式引入
1. 🔄 引入 Specification 规约模式
2. 🔄 引入 AggregateRoot 增强
3. 🔄 引入 DomainService 层次化

### 第三阶段（中期实施）- 总线系统
1. 🔄 引入 CommandBus 命令总线
2. 🔄 引入 QueryBus 查询总线
3. 🔄 引入 EventBus 事件总线

### 第四阶段（长期优化）- 高级模式
1. 🔄 引入 UnitOfWork 工作单元
2. 🔄 引入 DTO Assembler 组装器
3. 🔄 考虑事件溯源（Event Sourcing）

---

## 🎯 优化效果对比

### 与 Java DDD 对比

| 特性 | Java Spring | 当前 PHP | 优化后 PHP | 优势 |
|------|------------|----------|-----------|------|
| 依赖注入 | ✅ 完善 | ⚠️ 部分 | ✅ 完善 | 类型安全 |
| 工作单元 | ✅ 有 | ❌ 无 | ✅ 有 | 批量优化 |
| 命令总线 | ✅ 有 | ❌ 无 | ✅ 有 | 解耦灵活 |
| 查询总线 | ✅ 有 | ❌ 无 | ✅ 有 | CQRS 支持 |
| 事件总线 | ✅ 有 | ⚠️ 简单 | ✅ 完善 | 事件驱动 |
| 规约模式 | ✅ 有 | ❌ 无 | ✅ 有 | 业务规则 |
| 聚合根 | ✅ 完善 | ⚠️ 基础 | ✅ 完善 | 事件管理 |
| DTO 组装 | ✅ 有 | ⚠️ 简单 | ✅ 完善 | 转换规范 |

---

## 📚 相关文档

- [应用层设计分析与优化方案](./application-layer-design-analysis.md)
- [应用层代码规范](../../../.cursor/rules/application-layer.md)
- [领域层代码规范](../../../.cursor/rules/domain-layer.md)

---

## 📅 更新日志

- **2024-12-04**：初始版本，完成与 Java DDD 模式的对比分析和深度优化方案

