# 应用层设计分析与优化方案

## 📊 当前应用层设计概述

### 核心组件

#### 1. ApplicationService（应用服务基类）
- **位置**：`packages/support/src/Application/ApplicationService.php`
- **职责**：作为应用层入口，提供统一的服务接口
- **核心功能**：
  - 宏扩展机制：通过 `$handlers` 和 `$macros` 注册命令和查询处理器
  - 钩子机制：继承 `Service` 基类，提供 Hook 扩展点
  - 依赖注入：支持仓库和转换器注入
  - 命令查询分离：区分 CommandHandler 和 QueryHandler

#### 2. Handler（处理器基类）
- **位置**：`packages/support/src/Application/Handler.php`
- **功能**：
  - 提供钩子能力（HasHooks）
  - 提供数据库事务管理（CanUseDatabaseTransactions）
  - 管理处理上下文（HandleContext）

#### 3. HandleContext（处理上下文）
- **位置**：`packages/support/src/Application/HandleContext.php`
- **功能**：存储命令和模型对象，在处理流程中传递数据

#### 4. BaseCommandHandler（命令处理器基类）
- **位置**：`packages/support/src/Application/Commands/BaseCommandHandler.php`
- **功能**：
  - 定义标准的命令处理流程
  - 提供 validate、fill、save 等扩展点
  - 集成钩子机制和事务管理

#### 5. 预定义处理器
- `CreateCommandHandler`：创建实体
- `UpdateCommandHandler`：更新实体
- `DeleteCommandHandler`：删除实体
- `FindQueryHandler`：查询单个实体
- `PaginateQueryHandler`：分页查询

---

## ✅ 当前设计的优点

### 1. 扩展性强
- 通过宏机制可以动态注册处理器
- 支持在子类中覆盖和扩展功能
- 钩子机制提供多个扩展点

### 2. 统一规范
- 提供标准的 CRUD 处理器
- 统一的处理流程和接口
- 清晰的命令查询分离

### 3. 灵活的钩子系统
- 在处理流程的关键节点插入自定义逻辑
- 支持多级钩子嵌套
- 不侵入核心代码

### 4. 完善的事务管理
- 自动事务开启、提交、回滚
- 异常时自动回滚
- 支持嵌套事务

### 5. 依赖注入友好
- 通过构造函数注入依赖
- 支持 Laravel 容器自动解析
- 便于测试和替换实现

---

## ⚠️ 存在的问题和优化空间

### 问题 1：依赖注入不够明确

#### 当前问题
```php
// ApplicationService.php - 没有定义属性
class ApplicationService extends Service
{
    // 只在 PHPDoc 中声明
    // @property RepositoryInterface $repository
    protected static string $modelClass = Model::class;
}

// 子类需要自己定义
class ArticleApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public ArticleTransformer $transformer
    ) {}
}
```

#### 问题分析
- `ApplicationService` 没有定义 `repository` 和 `transformer` 属性
- 只在 PHPDoc 中声明，IDE 支持不完善
- 子类需要重复定义这些属性
- 缺少统一的获取器方法

#### 优化方案
```php
abstract class ApplicationService extends Service
{
    /**
     * 仓库接口
     */
    public RepositoryInterface $repository;
    
    /**
     * 转换器接口（可选）
     */
    public ?TransformerInterface $transformer = null;
    
    /**
     * 模型类
     */
    protected static string $modelClass = Model::class;
    
    /**
     * Hook 名称前缀
     */
    protected static string $hookNamePrefix = '';
    
    /**
     * 获取仓库
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }
    
    /**
     * 获取转换器
     */
    public function getTransformer(): ?TransformerInterface
    {
        return $this->transformer;
    }
    
    /**
     * 获取 Hook 名称前缀
     */
    public static function getHookNamePrefix(): string
    {
        return static::$hookNamePrefix;
    }
}
```

#### 优化效果
- ✅ 明确定义依赖属性
- ✅ 提供统一的获取器方法
- ✅ 更好的 IDE 支持
- ✅ 子类只需注入，不需要重复定义

---

### 问题 2：Hook 名称前缀管理混乱

#### 当前问题
```php
// BaseCommandHandler.php
protected function callHook(string $hook, mixed $passable, Closure $destination): mixed
{
    // 只使用 name，没有使用 service 的 hookNamePrefix
    $hook = $this->name.'.'.$hook;
    return $this->service->hook($hook, $passable, $destination);
}
```

#### 问题分析
- `ApplicationService` 没有定义 `$hookNamePrefix` 属性
- 处理器中的钩子名称只包含操作名，不包含服务前缀
- 不同服务的相同操作钩子名称会冲突
- 例如：`create.validate` 在所有服务中都一样

#### 优化方案
```php
// ApplicationService.php
abstract class ApplicationService extends Service
{
    /**
     * Hook 名称前缀
     * 建议格式：{domain}.{entity}
     * 例如：article.article, product.product
     */
    protected static string $hookNamePrefix = '';
    
    public static function getHookNamePrefix(): string
    {
        return static::$hookNamePrefix;
    }
}

// BaseCommandHandler.php
protected function callHook(string $hook, mixed $passable, Closure $destination): mixed
{
    $prefix = $this->service::getHookNamePrefix();
    
    // 构建完整的钩子名称：{prefix}.{operation}.{step}
    // 例如：article.article.create.validate
    $fullHook = $prefix 
        ? "{$prefix}.{$this->name}.{$hook}" 
        : "{$this->name}.{$hook}";
        
    return $this->service->hook($fullHook, $passable, $destination);
}

// 使用示例
class ArticleApplicationService extends ApplicationService
{
    protected static string $hookNamePrefix = 'article.article';
    
    // 钩子名称将是：
    // - article.article.create.validate
    // - article.article.create.fill
    // - article.article.create.save
}
```

#### 优化效果
- ✅ 避免钩子名称冲突
- ✅ 更清晰的钩子命名空间
- ✅ 便于调试和追踪
- ✅ 支持按服务过滤钩子

---

### 问题 3：HandleContext 设计不够灵活

#### 当前问题
```php
class HandleContext
{
    protected Data $command;
    public Model $model;
    
    // 只支持这两个属性，无法扩展
}
```

#### 问题分析
- 只支持 `command` 和 `model` 两个固定属性
- 无法存储额外的上下文信息
- 不支持查询上下文（Query）
- 在复杂场景下需要传递更多数据时受限

#### 优化方案
```php
class HandleContext
{
    /**
     * 命令对象
     */
    protected ?Data $command = null;
    
    /**
     * 查询对象
     */
    protected ?Data $query = null;
    
    /**
     * 领域模型
     */
    protected ?Model $model = null;
    
    /**
     * 额外的上下文数据
     */
    protected array $extra = [];
    
    // Command 相关
    public function getCommand(): ?Data
    {
        return $this->command;
    }
    
    public function setCommand(Data $command): self
    {
        $this->command = $command;
        return $this;
    }
    
    // Query 相关
    public function getQuery(): ?Data
    {
        return $this->query;
    }
    
    public function setQuery(Data $query): self
    {
        $this->query = $query;
        return $this;
    }
    
    // Model 相关
    public function getModel(): ?Model
    {
        return $this->model;
    }
    
    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }
    
    // 额外数据管理
    public function set(string $key, mixed $value): self
    {
        $this->extra[$key] = $value;
        return $this;
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->extra[$key] ?? $default;
    }
    
    public function has(string $key): bool
    {
        return isset($this->extra[$key]);
    }
    
    public function all(): array
    {
        return $this->extra;
    }
    
    public function forget(string $key): self
    {
        unset($this->extra[$key]);
        return $this;
    }
}
```

#### 使用示例
```php
// 在处理器中使用
protected function validate(HandleContext $context): void
{
    // 存储验证相关的额外信息
    $context->set('validation_rules', $this->getRules());
    $context->set('validator', $this->makeValidator());
}

protected function fill(HandleContext $context): void
{
    // 获取之前存储的信息
    $rules = $context->get('validation_rules');
    
    // 存储填充前的原始数据
    $context->set('original_data', $context->getModel()->toArray());
}
```

#### 优化效果
- ✅ 支持存储任意上下文数据
- ✅ 支持查询上下文
- ✅ 更灵活的数据传递
- ✅ 便于在钩子中共享数据

---

### 问题 4：缺少统一的查询作用域管理

#### 当前问题
```php
// 在控制器中设置查询作用域
class ArticleController extends Controller
{
    public function __construct(protected ArticleApplicationService $service)
    {
        // 每次都要手动设置
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }
}
```

#### 问题分析
- 查询作用域在控制器中设置，分散且不统一
- 没有提供统一的作用域管理机制
- 难以复用和组合作用域
- 无法在应用服务层统一管理

#### 优化方案
```php
// ApplicationService.php
abstract class ApplicationService extends Service
{
    /**
     * 查询作用域集合
     */
    protected array $queryScopes = [];
    
    /**
     * 添加查询作用域
     */
    public function addQueryScope(Closure $scope): self
    {
        $this->queryScopes[] = $scope;
        return $this;
    }
    
    /**
     * 批量添加查询作用域
     */
    public function addQueryScopes(array $scopes): self
    {
        foreach ($scopes as $scope) {
            $this->addQueryScope($scope);
        }
        return $this;
    }
    
    /**
     * 应用所有查询作用域
     */
    public function applyQueryScopes($query)
    {
        foreach ($this->queryScopes as $scope) {
            $scope($query);
        }
        return $query;
    }
    
    /**
     * 重置查询作用域
     */
    public function resetQueryScopes(): self
    {
        $this->queryScopes = [];
        return $this;
    }
    
    /**
     * 获取所有查询作用域
     */
    public function getQueryScopes(): array
    {
        return $this->queryScopes;
    }
}
```

#### 使用示例
```php
// 在控制器中
class ArticleController extends Controller
{
    public function __construct(protected ArticleApplicationService $service)
    {
        // 添加所有者作用域
        $this->service->addQueryScope(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
        
        // 添加状态作用域
        $this->service->addQueryScope(function ($query) {
            $query->where('status', 'published');
        });
    }
}

// 在查询处理器中自动应用
class PaginateQueryHandler extends QueryHandler
{
    public function handle(PaginateQuery $query): LengthAwarePaginator
    {
        $builder = $this->service->repository->query();
        
        // 自动应用所有作用域
        $builder = $this->service->applyQueryScopes($builder);
        
        return $this->service->repository->paginate($query, $builder);
    }
}
```

#### 优化效果
- ✅ 统一的作用域管理
- ✅ 支持作用域组合
- ✅ 便于复用和测试
- ✅ 更清晰的职责划分

---

### 问题 5：缺少统一的异常处理

#### 当前问题
```php
// BaseCommandHandler.php
public function handle(Data $command): ?Model
{
    $this->beginDatabaseTransaction();
    try {
        // ... 处理逻辑
        $this->commitDatabaseTransaction();
    } catch (Throwable $throwable) {
        $this->rollBackDatabaseTransaction();
        throw $throwable; // 直接抛出，没有统一处理
    }
    return $this->context->getModel();
}
```

#### 问题分析
- 没有统一的异常处理机制
- 缺少异常日志记录
- 业务异常和系统异常混在一起
- 难以进行异常监控和分析

#### 优化方案
```php
// ApplicationService.php
abstract class ApplicationService extends Service
{
    /**
     * 统一异常处理
     */
    protected function handleException(Throwable $e): void
    {
        // 记录详细日志
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
        
        // 可以在这里做异常转换
        // 例如：将数据库异常转换为业务异常
        
        throw $e;
    }
    
    /**
     * 处理业务异常
     */
    protected function handleBusinessException(Throwable $e): void
    {
        logger()->warning('Business exception', [
            'service' => static::class,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]);
        
        throw $e;
    }
}

// BaseCommandHandler.php
public function handle(Data $command): ?Model
{
    $this->beginDatabaseTransaction();
    try {
        // ... 处理逻辑
        $this->commitDatabaseTransaction();
    } catch (Throwable $throwable) {
        $this->rollBackDatabaseTransaction();
        
        // 使用统一的异常处理
        $this->service->handleException($throwable);
    }
    return $this->context->getModel();
}
```

#### 优化效果
- ✅ 统一的异常处理和日志记录
- ✅ 便于异常监控和分析
- ✅ 支持异常转换和包装
- ✅ 更好的错误追踪

---

### 问题 6：缺少事件发布机制

#### 当前问题
- 没有统一的领域事件发布机制
- 事件发布逻辑分散在各个处理器中
- 难以追踪和管理事件

#### 优化方案
```php
// ApplicationService.php
abstract class ApplicationService extends Service
{
    /**
     * 已发布的事件列表
     */
    protected array $events = [];
    
    /**
     * 发布领域事件
     */
    public function publishEvent(object $event): void
    {
        $this->events[] = $event;
        event($event);
    }
    
    /**
     * 批量发布事件
     */
    public function publishEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->publishEvent($event);
        }
    }
    
    /**
     * 获取已发布的事件
     */
    public function getEvents(): array
    {
        return $this->events;
    }
    
    /**
     * 清空事件列表
     */
    public function clearEvents(): void
    {
        $this->events = [];
    }
    
    /**
     * 获取特定类型的事件
     */
    public function getEventsByType(string $eventClass): array
    {
        return array_filter($this->events, fn($event) => $event instanceof $eventClass);
    }
}
```

#### 使用示例
```php
// 在命令处理器中
protected function save(HandleContext $context): void
{
    $model = $context->getModel();
    $isNew = !$model->exists;
    
    $this->service->repository->store($model);
    
    // 发布领域事件
    if ($isNew) {
        $this->service->publishEvent(new ArticleCreated($model));
    } else {
        $this->service->publishEvent(new ArticleUpdated($model));
    }
}

// 在测试中验证事件
public function test_create_article_publishes_event()
{
    $service = app(ArticleApplicationService::class);
    $service->create($command);
    
    $events = $service->getEventsByType(ArticleCreated::class);
    $this->assertCount(1, $events);
}
```

#### 优化效果
- ✅ 统一的事件发布机制
- ✅ 便于事件追踪和调试
- ✅ 支持事件测试
- ✅ 更好的事件管理

---

### 问题 7：缺少验证器支持

#### 当前问题
```php
// CreateCommandHandler.php
protected function validate(HandleContext $context): void
{
    $command = $context->getCommand();
    if (method_exists($command, 'validateBusinessRules')) {
        $command->validateBusinessRules();
    }
    // 验证逻辑分散，不统一
}
```

#### 问题分析
- 验证逻辑分散在各个处理器中
- 没有统一的验证器机制
- 难以复用验证规则
- 缺少验证器扩展点

#### 优化方案
```php
// 定义验证器接口
interface ValidatorInterface
{
    public function validate(Data $data, array $rules = []): void;
}

// ApplicationService.php
abstract class ApplicationService extends Service
{
    /**
     * 验证器
     */
    protected ?ValidatorInterface $validator = null;
    
    /**
     * 设置验证器
     */
    public function setValidator(ValidatorInterface $validator): self
    {
        $this->validator = $validator;
        return $this;
    }
    
    /**
     * 获取验证器
     */
    public function getValidator(): ?ValidatorInterface
    {
        return $this->validator;
    }
    
    /**
     * 执行验证
     */
    public function validate(Data $data, array $rules = []): void
    {
        if ($this->validator) {
            $this->validator->validate($data, $rules);
        }
    }
}

// 使用示例
class ArticleApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public ArticleTransformer $transformer,
        ArticleValidator $validator
    ) {
        $this->setValidator($validator);
    }
}

// 在处理器中使用
protected function validate(HandleContext $context): void
{
    $this->service->validate($context->getCommand());
}
```

#### 优化效果
- ✅ 统一的验证机制
- ✅ 便于复用验证规则
- ✅ 支持自定义验证器
- ✅ 更好的验证扩展性

---

### 问题 8：CommonCommandHandler 和 BaseCommandHandler 重复

#### 当前问题
- `CommonCommandHandler.php` 和 `BaseCommandHandler.php` 内容完全相同
- 造成代码冗余和维护困难
- 可能导致使用混淆

#### 优化方案
- 删除 `CommonCommandHandler.php`
- 统一使用 `BaseCommandHandler.php`
- 更新所有引用

#### 优化效果
- ✅ 消除代码重复
- ✅ 统一命名规范
- ✅ 减少维护成本

---

## 🎯 完整优化后的 ApplicationService

```php
<?php

namespace RedJasmine\Support\Application;

use Closure;use Illuminate\Contracts\Pagination\Paginator;use Illuminate\Database\Eloquent\Model;use Illuminate\Pagination\LengthAwarePaginator;use RedJasmine\Support\Application\Commands\CreateCommandHandler;use RedJasmine\Support\Application\Commands\DeleteCommandHandler;use RedJasmine\Support\Application\Commands\UpdateCommandHandler;use RedJasmine\Support\Application\Queries\FindQueryHandler;use RedJasmine\Support\Application\Queries\PaginateQueryHandler;use RedJasmine\Support\Domain\Data\Queries\FindQuery;use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;use RedJasmine\Support\Domain\Repositories\RepositoryInterface;use RedJasmine\Support\Domain\Transformer\TransformerInterface;use RedJasmine\Support\Foundation\Data\Data;use RedJasmine\Support\Foundation\Service\Service;use Throwable;

/**
 * 应用服务基类
 * 
 * 提供统一的应用服务接口，支持：
 * - 命令和查询处理器注册
 * - 依赖注入（仓库、转换器）
 * - 钩子机制
 * - 查询作用域管理
 * - 事件发布
 * - 异常处理
 * 
 * @method Model create(Data $command)
 * @method Model update(Data $command)
 * @method bool delete(Data $command)
 * @method Model find(FindQuery $query)
 * @method LengthAwarePaginator|Paginator paginate(PaginateQuery $query)
 */
abstract class ApplicationService extends Service
{
    /**
     * 仓库接口
     * 子类通过构造函数注入具体实现
     */
    public RepositoryInterface $repository;
    
    /**
     * 转换器接口（可选）
     * 用于将 DTO 转换为领域模型
     */
    public ?TransformerInterface $transformer = null;
    
    /**
     * 验证器接口（可选）
     * 用于业务规则验证
     */
    protected ?ValidatorInterface $validator = null;
    
    /**
     * 模型类
     * 子类必须指定具体的模型类
     */
    protected static string $modelClass = Model::class;
    
    /**
     * Hook 名称前缀
     * 建议格式：{domain}.{entity}
     * 例如：article.article, product.product
     */
    protected static string $hookNamePrefix = '';
    
    /**
     * 预定义处理器
     * 提供标准的 CRUD 操作
     */
    protected static array $handlers = [
        'create'   => CreateCommandHandler::class,
        'update'   => UpdateCommandHandler::class,
        'delete'   => DeleteCommandHandler::class,
        'find'     => FindQueryHandler::class,
        'paginate' => PaginateQueryHandler::class
    ];
    
    /**
     * 查询作用域集合
     * 用于在查询时自动应用过滤条件
     */
    protected array $queryScopes = [];
    
    /**
     * 已发布的领域事件列表
     * 用于追踪和测试
     */
    protected array $events = [];
    
    /**
     * 获取模型类
     */
    public static function getModelClass(): string
    {
        return static::$modelClass;
    }
    
    /**
     * 获取 Hook 名称前缀
     */
    public static function getHookNamePrefix(): string
    {
        return static::$hookNamePrefix;
    }
    
    /**
     * 获取所有宏定义（处理器）
     * 合并预定义处理器和自定义宏
     */
    public static function getMacros(): array
    {
        return array_merge(static::$handlers, static::$macros);
    }
    
    /**
     * 创建新模型实例
     */
    public function newModel(?Data $data = null): Model
    {
        return static::$modelClass::make();
    }
    
    /**
     * 获取仓库
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }
    
    /**
     * 获取转换器
     */
    public function getTransformer(): ?TransformerInterface
    {
        return $this->transformer;
    }
    
    /**
     * 设置验证器
     */
    public function setValidator(ValidatorInterface $validator): self
    {
        $this->validator = $validator;
        return $this;
    }
    
    /**
     * 获取验证器
     */
    public function getValidator(): ?ValidatorInterface
    {
        return $this->validator;
    }
    
    /**
     * 执行验证
     */
    public function validate(Data $data, array $rules = []): void
    {
        if ($this->validator) {
            $this->validator->validate($data, $rules);
        }
    }
    
    /**
     * 添加查询作用域
     */
    public function addQueryScope(Closure $scope): self
    {
        $this->queryScopes[] = $scope;
        return $this;
    }
    
    /**
     * 批量添加查询作用域
     */
    public function addQueryScopes(array $scopes): self
    {
        foreach ($scopes as $scope) {
            $this->addQueryScope($scope);
        }
        return $this;
    }
    
    /**
     * 应用所有查询作用域
     */
    public function applyQueryScopes($query)
    {
        foreach ($this->queryScopes as $scope) {
            $scope($query);
        }
        return $query;
    }
    
    /**
     * 重置查询作用域
     */
    public function resetQueryScopes(): self
    {
        $this->queryScopes = [];
        return $this;
    }
    
    /**
     * 获取所有查询作用域
     */
    public function getQueryScopes(): array
    {
        return $this->queryScopes;
    }
    
    /**
     * 发布领域事件
     */
    public function publishEvent(object $event): void
    {
        $this->events[] = $event;
        event($event);
    }
    
    /**
     * 批量发布事件
     */
    public function publishEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->publishEvent($event);
        }
    }
    
    /**
     * 获取已发布的事件
     */
    public function getEvents(): array
    {
        return $this->events;
    }
    
    /**
     * 获取特定类型的事件
     */
    public function getEventsByType(string $eventClass): array
    {
        return array_filter($this->events, fn($event) => $event instanceof $eventClass);
    }
    
    /**
     * 清空事件列表
     */
    public function clearEvents(): void
    {
        $this->events = [];
    }
    
    /**
     * 统一异常处理
     */
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
    
    /**
     * 处理业务异常
     */
    protected function handleBusinessException(Throwable $e): void
    {
        logger()->warning('Business exception', [
            'service' => static::class,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]);
        
        throw $e;
    }
    
    /**
     * 创建宏实例
     * 自动注入当前服务实例
     */
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

## 📝 实施建议

### 立即执行的修改（高优先级）

#### 1. 删除重复文件
```bash
# 删除 CommonCommandHandler.php
rm packages/support/src/Application/Commands/CommonCommandHandler.php
```

#### 2. 更新 ApplicationService
- 添加 `$repository` 和 `$transformer` 属性定义
- 添加 `$hookNamePrefix` 静态属性
- 添加获取器方法

#### 3. 更新 BaseCommandHandler
- 修改 `callHook` 方法，使用完整的钩子名称
- 集成异常处理

### 渐进式优化（中优先级）

#### 1. 增强 HandleContext
- 添加 `query` 属性支持
- 添加 `extra` 数组支持任意数据
- 添加相关的 getter/setter 方法

#### 2. 添加查询作用域管理
- 在 ApplicationService 中添加作用域相关方法
- 在查询处理器中自动应用作用域

#### 3. 添加事件发布机制
- 在 ApplicationService 中添加事件相关方法
- 在命令处理器中发布领域事件

### 可选优化（低优先级）

#### 1. 验证器支持
- 定义验证器接口
- 集成到应用服务中

#### 2. 异常处理增强
- 添加异常分类
- 添加异常监控

---

## 🔄 迁移指南

### 现有代码兼容性

优化后的设计保持向后兼容，现有代码无需修改即可运行。但建议逐步迁移到新的实践：

#### 1. 更新应用服务定义

**旧代码**：
```php
class ArticleApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public ArticleTransformer $transformer
    ) {}
}
```

**新代码**：
```php
class ArticleApplicationService extends ApplicationService
{
    protected static string $hookNamePrefix = 'article.article';
    
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public ArticleTransformer $transformer
    ) {
        // 属性会自动设置到父类
    }
}
```

#### 2. 使用查询作用域

**旧代码**：
```php
public function __construct(protected ArticleApplicationService $service)
{
    $this->service->repository->withQuery(function ($query) {
        $query->onlyOwner($this->getOwner());
    });
}
```

**新代码**：
```php
public function __construct(protected ArticleApplicationService $service)
{
    $this->service->addQueryScope(function ($query) {
        $query->onlyOwner($this->getOwner());
    });
}
```

#### 3. 发布领域事件

**新增功能**：
```php
// 在命令处理器中
protected function save(HandleContext $context): void
{
    $model = $context->getModel();
    $this->service->repository->store($model);
    
    // 发布事件
    $this->service->publishEvent(new ArticleCreated($model));
}
```

---

## 📊 优化效果总结

### 代码质量提升
- ✅ 更清晰的依赖关系
- ✅ 更好的类型安全
- ✅ 更完善的 IDE 支持
- ✅ 消除代码重复

### 功能增强
- ✅ 统一的查询作用域管理
- ✅ 完善的事件发布机制
- ✅ 灵活的上下文管理
- ✅ 统一的异常处理

### 可维护性提升
- ✅ 更清晰的钩子命名
- ✅ 更好的扩展性
- ✅ 更容易测试
- ✅ 更好的调试体验

### 性能影响
- ✅ 无明显性能损失
- ✅ 查询作用域可能略微增加开销（可忽略）
- ✅ 事件追踪内存开销很小

---

## 🎓 最佳实践建议

### 1. Hook 命名规范
```
格式：{domain}.{entity}.{operation}.{step}
示例：
- article.article.create.validate
- article.article.create.fill
- article.article.create.save
- product.product.update.validate
```

### 2. 查询作用域使用
```php
// 在控制器构造函数中添加
$this->service->addQueryScope(fn($q) => $q->onlyOwner($this->getOwner()));

// 支持链式调用
$this->service
    ->addQueryScope(fn($q) => $q->where('status', 'active'))
    ->addQueryScope(fn($q) => $q->where('is_show', true));
```

### 3. 事件发布时机
```php
// 在数据持久化后发布
protected function save(HandleContext $context): void
{
    $model = $context->getModel();
    $isNew = !$model->exists;
    
    $this->service->repository->store($model);
    
    // 根据操作类型发布不同事件
    $event = $isNew ? new ModelCreated($model) : new ModelUpdated($model);
    $this->service->publishEvent($event);
}
```

### 4. 上下文数据使用
```php
// 在处理流程中传递额外数据
protected function validate(HandleContext $context): void
{
    $context->set('validated_at', now());
    $context->set('validator_version', '1.0');
}

protected function fill(HandleContext $context): void
{
    $validatedAt = $context->get('validated_at');
    // 使用之前存储的数据
}
```

---

## 📚 相关文档

- [应用层代码规范](../../../.cursor/rules/application-layer.md)
- [领域层代码规范](../../../.cursor/rules/domain-layer.md)
- [基础设施层代码规范](../../../.cursor/rules/infrastructure-layer.md)
- [用户接口层代码规范](../../../.cursor/rules/ui-layer.md)

---

## 📅 更新日志

- **2024-12-04**：初始版本，完成应用层设计分析和优化方案

