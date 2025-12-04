# makeMacro 依赖注入优化方案

## 📋 当前问题分析

### 当前实现
```php
protected function makeMacro($macro, $method, $parameters)
{
    if (is_string($macro) && class_exists($macro)) {
        return app($macro, ['service' => $this]);
    }
    return $macro;
}
```

### 存在的问题

1. **硬编码参数名**
   - 固定使用 `'service'` 作为参数名
   - 如果处理器构造函数参数名不是 `service`，会注入失败

2. **缺少类型检查**
   - 没有验证 `$macro` 是否是有效的类
   - 没有检查类是否可实例化

3. **依赖注入不完整**
   - 只注入了 `service`，其他依赖需要容器自动解析
   - 如果处理器有其他必需依赖，可能会失败

4. **缺少缓存机制**
   - 每次调用都创建新实例
   - 对于无状态的处理器，可以复用实例

5. **错误处理不足**
   - 没有捕获实例化异常
   - 错误信息不够详细

---

## 🎯 优化方案

### 方案 1：使用反射自动解析构造函数（推荐）

#### 实现代码
```php
<?php

namespace RedJasmine\Support\Application;

use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

abstract class ApplicationService extends Service
{
    /**
     * 处理器实例缓存
     */
    protected array $handlerInstances = [];
    
    /**
     * 创建宏实例（优化版）
     * 
     * @param mixed $macro 宏定义（类名或闭包）
     * @param string $method 方法名
     * @param array $parameters 调用参数
     * @return mixed
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    protected function makeMacro($macro, string $method, array $parameters)
    {
        // 如果是闭包，直接返回
        if ($macro instanceof \Closure) {
            return $macro;
        }
        
        // 如果不是字符串或类不存在，返回原值
        if (!is_string($macro) || !class_exists($macro)) {
            return $macro;
        }
        
        // 检查缓存
        if (isset($this->handlerInstances[$macro])) {
            return $this->handlerInstances[$macro];
        }
        
        try {
            // 使用反射分析构造函数
            $instance = $this->resolveHandlerWithReflection($macro);
            
            // 缓存实例（如果是无状态的）
            if ($this->shouldCacheHandler($macro)) {
                $this->handlerInstances[$macro] = $instance;
            }
            
            return $instance;
            
        } catch (\Throwable $e) {
            throw new BindingResolutionException(
                "Failed to instantiate handler [{$macro}] for method [{$method}]: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    /**
     * 使用反射解析处理器
     */
    protected function resolveHandlerWithReflection(string $handlerClass): object
    {
        $reflection = new ReflectionClass($handlerClass);
        
        // 检查是否可实例化
        if (!$reflection->isInstantiable()) {
            throw new \LogicException("Handler [{$handlerClass}] is not instantiable");
        }
        
        $constructor = $reflection->getConstructor();
        
        // 如果没有构造函数，直接实例化
        if (!$constructor) {
            return new $handlerClass();
        }
        
        // 解析构造函数参数
        $dependencies = $this->resolveConstructorDependencies($constructor->getParameters());
        
        // 实例化
        return $reflection->newInstanceArgs($dependencies);
    }
    
    /**
     * 解析构造函数依赖
     */
    protected function resolveConstructorDependencies(array $parameters): array
    {
        $dependencies = [];
        
        foreach ($parameters as $parameter) {
            $dependencies[] = $this->resolveDependency($parameter);
        }
        
        return $dependencies;
    }
    
    /**
     * 解析单个依赖
     */
    protected function resolveDependency(ReflectionParameter $parameter)
    {
        // 1. 检查是否是当前服务实例
        if ($this->isServiceParameter($parameter)) {
            return $this;
        }
        
        // 2. 检查是否有类型提示
        $type = $parameter->getType();
        if ($type && !$type->isBuiltin()) {
            $className = $type->getName();
            
            // 尝试从容器解析
            if (app()->bound($className)) {
                return app($className);
            }
            
            // 如果是接口，检查是否有绑定
            if (interface_exists($className)) {
                return app($className);
            }
            
            // 尝试直接实例化
            return app($className);
        }
        
        // 3. 检查是否有默认值
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        
        // 4. 如果允许 null
        if ($parameter->allowsNull()) {
            return null;
        }
        
        // 5. 无法解析
        throw new BindingResolutionException(
            "Unable to resolve parameter [{$parameter->getName()}] in handler constructor"
        );
    }
    
    /**
     * 检查参数是否是服务实例
     */
    protected function isServiceParameter(ReflectionParameter $parameter): bool
    {
        // 检查参数名
        $name = $parameter->getName();
        if (in_array($name, ['service', 'applicationService', 'app', 'appService'])) {
            return true;
        }
        
        // 检查类型提示
        $type = $parameter->getType();
        if ($type && !$type->isBuiltin()) {
            $className = $type->getName();
            
            // 检查是否是当前类或父类
            if (is_a($this, $className)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 判断是否应该缓存处理器实例
     */
    protected function shouldCacheHandler(string $handlerClass): bool
    {
        // 默认缓存所有处理器
        // 子类可以覆盖此方法来自定义缓存策略
        return true;
    }
    
    /**
     * 清空处理器缓存
     */
    public function clearHandlerCache(): void
    {
        $this->handlerInstances = [];
    }
}
```

#### 使用示例
```php
// 处理器可以使用任意参数名和类型提示
class ArticleCreateCommandHandler extends BaseCommandHandler
{
    // 方式 1：使用参数名 'service'
    public function __construct($service)
    {
        $this->service = $service;
    }
    
    // 方式 2：使用类型提示
    public function __construct(ArticleApplicationService $applicationService)
    {
        $this->service = $applicationService;
    }
    
    // 方式 3：注入多个依赖
    public function __construct(
        ArticleApplicationService $service,
        ArticleValidator $validator,
        EventDispatcher $eventDispatcher
    ) {
        $this->service = $service;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
    }
}
```

#### 优点
- ✅ 自动识别服务参数
- ✅ 支持类型提示
- ✅ 支持多个依赖注入
- ✅ 自动从容器解析依赖
- ✅ 支持默认值
- ✅ 详细的错误信息

---

### 方案 2：使用上下文绑定（Context Binding）

#### 实现代码
```php
<?php

namespace RedJasmine\Support\Application;

abstract class ApplicationService extends Service
{
    /**
     * 创建宏实例（使用上下文绑定）
     */
    protected function makeMacro($macro, string $method, array $parameters)
    {
        if ($macro instanceof \Closure) {
            return $macro;
        }
        
        if (!is_string($macro) || !class_exists($macro)) {
            return $macro;
        }
        
        // 使用上下文绑定
        return $this->makeWithContextBinding($macro);
    }
    
    /**
     * 使用上下文绑定创建实例
     */
    protected function makeWithContextBinding(string $handlerClass): object
    {
        // 为这个处理器设置上下文绑定
        app()->when($handlerClass)
            ->needs('$service')
            ->give(fn() => $this);
        
        // 也支持类型绑定
        app()->when($handlerClass)
            ->needs(static::class)
            ->give(fn() => $this);
        
        // 从容器解析
        return app($handlerClass);
    }
}
```

#### 优点
- ✅ 利用 Laravel 容器的上下文绑定功能
- ✅ 代码简洁
- ✅ 支持复杂的依赖关系

#### 缺点
- ⚠️ 每次都需要设置绑定
- ⚠️ 可能影响全局容器状态

---

### 方案 3：使用工厂模式

#### 实现代码
```php
<?php

namespace RedJasmine\Support\Application;

/**
 * 处理器工厂
 */
class HandlerFactory
{
    protected array $instances = [];
    
    /**
     * 创建处理器实例
     */
    public function make(string $handlerClass, ApplicationService $service): object
    {
        // 生成缓存键
        $cacheKey = $this->getCacheKey($handlerClass, $service);
        
        // 检查缓存
        if (isset($this->instances[$cacheKey])) {
            return $this->instances[$cacheKey];
        }
        
        // 创建实例
        $instance = $this->createInstance($handlerClass, $service);
        
        // 缓存
        $this->instances[$cacheKey] = $instance;
        
        return $instance;
    }
    
    /**
     * 创建实例
     */
    protected function createInstance(string $handlerClass, ApplicationService $service): object
    {
        try {
            // 尝试使用构造函数注入
            return new $handlerClass($service);
        } catch (\ArgumentCountError $e) {
            // 如果构造函数参数不匹配，使用容器解析
            return app()->make($handlerClass, [
                'service' => $service,
                'applicationService' => $service,
            ]);
        }
    }
    
    /**
     * 生成缓存键
     */
    protected function getCacheKey(string $handlerClass, ApplicationService $service): string
    {
        return $handlerClass . '@' . spl_object_id($service);
    }
    
    /**
     * 清空缓存
     */
    public function flush(): void
    {
        $this->instances = [];
    }
}

// 在 ApplicationService 中使用
abstract class ApplicationService extends Service
{
    protected static ?HandlerFactory $handlerFactory = null;
    
    protected function makeMacro($macro, string $method, array $parameters)
    {
        if ($macro instanceof \Closure) {
            return $macro;
        }
        
        if (!is_string($macro) || !class_exists($macro)) {
            return $macro;
        }
        
        return $this->getHandlerFactory()->make($macro, $this);
    }
    
    protected function getHandlerFactory(): HandlerFactory
    {
        if (!static::$handlerFactory) {
            static::$handlerFactory = new HandlerFactory();
        }
        return static::$handlerFactory;
    }
}
```

#### 优点
- ✅ 集中管理处理器创建逻辑
- ✅ 支持缓存
- ✅ 易于扩展和测试
- ✅ 可以添加更多创建策略

---

### 方案 4：使用 Laravel Make 方法（最简洁）

#### 实现代码
```php
<?php

namespace RedJasmine\Support\Application;

abstract class ApplicationService extends Service
{
    /**
     * 创建宏实例（使用 Laravel make）
     */
    protected function makeMacro($macro, string $method, array $parameters)
    {
        if ($macro instanceof \Closure) {
            return $macro;
        }
        
        if (!is_string($macro) || !class_exists($macro)) {
            return $macro;
        }
        
        // 使用 Laravel 容器的 make 方法
        // 支持多种参数名称
        return app()->makeWith($macro, [
            'service' => $this,
            'applicationService' => $this,
            static::class => $this,
        ]);
    }
}
```

#### 优点
- ✅ 代码最简洁
- ✅ 利用 Laravel 容器的强大功能
- ✅ 支持多种参数名称
- ✅ 自动解析其他依赖

#### 缺点
- ⚠️ 每次都创建新实例（无缓存）

---

## 🎯 推荐方案：组合使用

### 最佳实践实现

```php
<?php

namespace RedJasmine\Support\Application;

use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\SimpleCache\CacheInterface;

abstract class ApplicationService extends Service
{
    /**
     * 处理器实例缓存
     */
    protected array $handlerInstances = [];
    
    /**
     * 是否启用处理器缓存
     */
    protected bool $cacheHandlers = true;
    
    /**
     * 创建宏实例（最佳实践版本）
     * 
     * @param mixed $macro 宏定义
     * @param string $method 方法名
     * @param array $parameters 调用参数
     * @return mixed
     */
    protected function makeMacro($macro, string $method, array $parameters)
    {
        // 1. 如果是闭包，直接返回
        if ($macro instanceof \Closure) {
            return $macro;
        }
        
        // 2. 如果不是有效的类名，返回原值
        if (!is_string($macro) || !class_exists($macro)) {
            return $macro;
        }
        
        // 3. 检查缓存
        if ($this->cacheHandlers && isset($this->handlerInstances[$macro])) {
            return $this->handlerInstances[$macro];
        }
        
        try {
            // 4. 创建实例
            $instance = $this->createHandlerInstance($macro);
            
            // 5. 缓存实例
            if ($this->cacheHandlers) {
                $this->handlerInstances[$macro] = $instance;
            }
            
            return $instance;
            
        } catch (\Throwable $e) {
            // 6. 错误处理
            $this->handleHandlerCreationError($macro, $method, $e);
            throw $e;
        }
    }
    
    /**
     * 创建处理器实例
     */
    protected function createHandlerInstance(string $handlerClass): object
    {
        // 尝试多种方式创建实例
        
        // 方式 1：使用 makeWith，支持多种参数名
        try {
            return app()->makeWith($handlerClass, [
                'service' => $this,
                'applicationService' => $this,
                'app' => $this,
                static::class => $this,
            ]);
        } catch (BindingResolutionException $e) {
            // 如果失败，尝试其他方式
        }
        
        // 方式 2：直接从容器解析
        try {
            return app($handlerClass);
        } catch (BindingResolutionException $e) {
            // 如果失败，尝试直接实例化
        }
        
        // 方式 3：直接实例化（最后的尝试）
        return new $handlerClass($this);
    }
    
    /**
     * 处理处理器创建错误
     */
    protected function handleHandlerCreationError(
        string $handlerClass,
        string $method,
        \Throwable $error
    ): void {
        logger()->error('Failed to create handler', [
            'handler' => $handlerClass,
            'method' => $method,
            'service' => static::class,
            'error' => $error->getMessage(),
            'trace' => $error->getTraceAsString(),
        ]);
    }
    
    /**
     * 清空处理器缓存
     */
    public function clearHandlerCache(): void
    {
        $this->handlerInstances = [];
    }
    
    /**
     * 禁用处理器缓存
     */
    public function disableHandlerCache(): self
    {
        $this->cacheHandlers = false;
        return $this;
    }
    
    /**
     * 启用处理器缓存
     */
    public function enableHandlerCache(): self
    {
        $this->cacheHandlers = true;
        return $this;
    }
}
```

### 使用示例

```php
// 处理器定义 - 支持多种方式

// 方式 1：简单的参数名
class Handler1 extends CommandHandler
{
    public function __construct($service)
    {
        $this->service = $service;
    }
}

// 方式 2：类型提示
class Handler2 extends CommandHandler
{
    public function __construct(ArticleApplicationService $service)
    {
        $this->service = $service;
    }
}

// 方式 3：多个依赖
class Handler3 extends CommandHandler
{
    public function __construct(
        ArticleApplicationService $service,
        ArticleValidator $validator
    ) {
        $this->service = $service;
        $this->validator = $validator;
    }
}

// 方式 4：从容器解析
class Handler4 extends CommandHandler
{
    public function __construct(
        ApplicationService $service,
        LoggerInterface $logger
    ) {
        $this->service = $service;
        $this->logger = $logger;
    }
}

// 应用服务使用
class ArticleApplicationService extends ApplicationService
{
    protected static array $macros = [
        'create' => Handler1::class,
        'update' => Handler2::class,
        'delete' => Handler3::class,
        'publish' => Handler4::class,
    ];
}

// 调用
$service = app(ArticleApplicationService::class);
$result = $service->create($command); // 自动创建和缓存 Handler1
```

---

## 📊 方案对比

| 方案 | 灵活性 | 性能 | 复杂度 | 推荐度 |
|------|--------|------|--------|--------|
| 方案 1：反射解析 | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| 方案 2：上下文绑定 | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐ |
| 方案 3：工厂模式 | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| 方案 4：Laravel Make | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐ | ⭐⭐⭐⭐⭐ |
| 组合方案 | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |

---

## 🎓 最佳实践建议

### 1. 处理器构造函数设计

```php
// ✅ 推荐：使用类型提示
class ArticleCreateCommandHandler extends BaseCommandHandler
{
    public function __construct(
        ArticleApplicationService $service,
        ?ArticleValidator $validator = null
    ) {
        $this->service = $service;
        $this->validator = $validator;
    }
}

// ❌ 不推荐：使用混合类型
class ArticleCreateCommandHandler extends BaseCommandHandler
{
    public function __construct($service, $validator = null)
    {
        $this->service = $service;
        $this->validator = $validator;
    }
}
```

### 2. 依赖注入优先级

```php
// 优先级顺序：
// 1. 应用服务实例（必需）
// 2. 领域服务（按需）
// 3. 基础设施服务（按需）
// 4. 工具类（按需）

class ArticleCreateCommandHandler extends BaseCommandHandler
{
    public function __construct(
        ArticleApplicationService $service,        // 1. 应用服务
        ArticleDomainService $domainService,       // 2. 领域服务
        EventDispatcher $eventDispatcher,          // 3. 基础设施
        LoggerInterface $logger                    // 4. 工具类
    ) {
        $this->service = $service;
        $this->domainService = $domainService;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }
}
```

### 3. 缓存策略

```php
// 在应用服务中配置
class ArticleApplicationService extends ApplicationService
{
    // 启用缓存（默认）
    protected bool $cacheHandlers = true;
    
    // 或者在运行时控制
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public ArticleTransformer $transformer
    ) {
        // 开发环境禁用缓存
        if (app()->environment('local')) {
            $this->disableHandlerCache();
        }
    }
}
```

---

## 📅 更新日志

- **2024-12-04**：初始版本，提供 makeMacro 依赖注入的多种优化方案

