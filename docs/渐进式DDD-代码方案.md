# 渐进式 DDD - 简化 CRUD 方案

## 设计理念

> **保持架构一致性，支持渐进式复杂度**

```
简单场景：使用默认实现（零代码或少量代码）
复杂场景：重写扩展点（渐进式增加复杂度）
```

## 核心设计


### 1. 分层保持一致（渐进式复杂度）

```
┌─────────────────────────────────────────────────┐
│  UI Layer                                       │
│  - Controller (使用 RestControllerActions)      │
└─────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────┐
│  Application Layer                              │
│  - ApplicationService (统一门面)       [必需]   │
│  - CommandHandler (默认或自定义)       [必需]   │
└─────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────┐
│  Domain Layer                                   │
│  - Model (Eloquent)                    [必需]   │
│  - Data (DTO)                          [必需]   │
│  - Transformer (数据转换)              [可选]   │
│  - DomainService (业务逻辑)            [可选]   │
│  - Repository Interface (仓储接口)     [可选]   │
└─────────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────────┐
│  Infrastructure Layer                           │
│  - Repository Implementation (仓储实现) [可选]  │
└─────────────────────────────────────────────────┘
```

**持久化策略（渐进式）**：
```
模式 A：直接使用 $model->save()  ← 最简单
模式 B：使用 Repository（标准 DDD）
模式 C：Repository + 复杂持久化逻辑
```

### 2. 两种使用模式

#### 模式 A：极简 CRUD（80% 场景）⚡
- **适用场景**：简单的 CRUD，无复杂业务逻辑
- **需要的组件**：只需 **3 个文件**
  - Model (Eloquent)
  - Data (DTO)
  - ApplicationService
- **不需要**：Repository、Transformer、DomainService
- **持久化方式**：直接 `$model->save()` / `delete()`
- **特点**：快速开发，代码最少

#### 模式 C：完整 DDD（20% 场景）🏛️
- **适用场景**：复杂业务逻辑、核心业务
- **需要的组件**：全部组件
  - Model (充血模型)
  - Data + Repository + Transformer + DomainService
  - Factory + Specification + DomainEvent
  - 自定义 CommandHandler
- **持久化方式**：`$repository->store()` + 复杂持久化逻辑
- **特点**：完整的 DDD 模式，长期维护性好

## 方案设计

### 1. 提供默认命令处理器

```php
<?php

namespace RedJasmine\Support\Application\Commands;

use Illuminate\Database\Eloquent\Model;use RedJasmine\Support\Application\HandleContext;use RedJasmine\Support\Foundation\Data\Data;

/**
 * 默认创建命令处理器
 * 
 * 提供开箱即用的创建功能，支持通过钩子扩展
 */
class DefaultCreateCommandHandler extends BaseCommandHandler
{
    protected string $name = 'create';

    /**
     * 步骤1：基础验证（默认空实现）
     * 
     * 扩展点：派生类重写此方法添加验证逻辑
     */
    protected function validate(Data $command): void
    {
        // 默认空实现，可通过钩子扩展
        // hook: {name}.validate
    }

    /**
     * 步骤2：解析新模型（默认实现）
     * 
     * 扩展点：派生类重写使用工厂模式
     */
    protected function resolve(Data $command): Model
    {
        return $this->service->newModel();
    }

    /**
     * 步骤3：执行业务逻辑（默认实现）
     * 
     * 智能选择处理策略（按优先级）：
     * 1. 如果有领域服务且有对应方法 → 调用领域服务
     * 2. 如果有转换器 → 使用转换器
     * 3. 否则 → 使用 Model::fill() 直接填充
     * 
     * 扩展点：派生类重写实现自定义逻辑
     */
    protected function execute(HandleContext $context): void
    {
        $model = $context->getModel();
        $command = $context->getCommand();

        // 策略1：尝试调用领域服务
        if (method_exists($this->service, 'domainService')) {
            $domainService = $this->service->domainService();
            if ($domainService && method_exists($domainService, 'create')) {
                $domainService->create($model, $command);
                $context->setModel($model);
                return;
            }
        }

        // 策略2：尝试使用 Transformer
        if (property_exists($this->service, 'transformer') && $this->service->transformer) {
            $this->service->transformer->transform($model, $command);
            $context->setModel($model);
            return;
        }

        // 策略3：使用 Model::fill() 直接填充（最简单）
        $model->fill($command->toArray());
        $context->setModel($model);
    }

    /**
     * 步骤4：持久化（默认实现）
     * 
     * 智能选择持久化策略：
     * 1. 如果有 Repository → 使用 Repository
     * 2. 否则 → 直接使用 Model::save()
     * 
     * 扩展点：派生类重写处理关联数据
     */
    protected function persist(HandleContext $context): void
    {
        $model = $context->getModel();
        
        // 策略1：使用 Repository（如果存在）
        if (property_exists($this->service, 'repository') && $this->service->repository) {
            $this->service->repository->store($model);
            return;
        }
        
        // 策略2：直接使用 Eloquent save()
        $model->save();
    }
}

/**
 * 默认更新命令处理器
 */
class DefaultUpdateCommandHandler extends BaseCommandHandler
{
    protected string $name = 'update';

    protected function validate(Data $command): void
    {
        if (!$command->getKey()) {
            throw new \InvalidArgumentException('ID不能为空');
        }
    }

    protected function resolve(Data $command): Model
    {
        $model = $this->service->repository->find($command->getKey());
        
        if (!$model) {
            throw new \RuntimeException('记录不存在');
        }
        
        return $model;
    }

    protected function execute(HandleContext $context): void
    {
        $model = $context->getModel();
        $command = $context->getCommand();

        // 策略1：尝试调用领域服务
        if (method_exists($this->service, 'domainService')) {
            $domainService = $this->service->domainService();
            if ($domainService && method_exists($domainService, 'update')) {
                $domainService->update($model, $command);
                $context->setModel($model);
                return;
            }
        }

        // 策略2：尝试使用 Transformer
        if (property_exists($this->service, 'transformer') && $this->service->transformer) {
            $this->service->transformer->transform($model, $command);
            $context->setModel($model);
            return;
        }

        // 策略3：使用 Model::fill() 直接填充
        $model->fill($command->toArray());
        $context->setModel($model);
    }

    protected function persist(HandleContext $context): void
    {
        $model = $context->getModel();
        
        // 策略1：使用 Repository（如果存在）
        if (property_exists($this->service, 'repository') && $this->service->repository) {
            $this->service->repository->update($model);
            return;
        }
        
        // 策略2：直接使用 Eloquent save()
        $model->save();
    }
}

/**
 * 默认删除命令处理器
 */
class DefaultDeleteCommandHandler extends BaseCommandHandler
{
    protected string $name = 'delete';

    protected function validate(Data $command): void
    {
        if (!$command->getKey()) {
            throw new \InvalidArgumentException('ID不能为空');
        }
    }

    protected function resolve(Data $command): Model
    {
        $model = $this->service->repository->find($command->getKey());
        
        if (!$model) {
            throw new \RuntimeException('记录不存在');
        }
        
        return $model;
    }

    protected function execute(HandleContext $context): void
    {
        $model = $context->getModel();

        // 如果有领域服务，调用领域服务
        if (method_exists($this->service, 'domainService')) {
            $domainService = $this->service->domainService();
            if ($domainService && method_exists($domainService, 'delete')) {
                $domainService->delete($model);
            }
        }

        $context->setModel($model);
    }

    protected function persist(HandleContext $context): void
    {
        $model = $context->getModel();
        
        // 策略1：使用 Repository（如果存在）
        if (property_exists($this->service, 'repository') && $this->service->repository) {
            $this->service->repository->delete($model);
            return;
        }
        
        // 策略2：直接使用 Eloquent delete()
        $model->delete();
    }
}
```

### 2. ApplicationService 配置化

```php
<?php

namespace RedJasmine\Support\Application;

/**
 * 应用服务基类（增强版）
 * 
 * 支持：
 * 1. 默认命令处理器
 * 2. 可选的领域服务
 * 3. 钩子扩展
 */
class ApplicationService extends Service
{
    // 默认命令处理器
    protected static array $handlers = [
        'create'   => DefaultCreateCommandHandler::class,
        'update'   => DefaultUpdateCommandHandler::class,
        'delete'   => DefaultDeleteCommandHandler::class,
        'find'     => FindQueryHandler::class,
        'paginate' => PaginateQueryHandler::class
    ];

    // 领域服务（可选）
    protected static ?string $domainServiceClass = null;
    
    protected static string $modelClass = Model::class;

    public function __construct(
        public ?RepositoryInterface $repository = null,    // ✅ Repository 可选
        public ?TransformerInterface $transformer = null   // ✅ Transformer 可选
    ) {
    }

    /**
     * 获取领域服务（如果配置了）
     */
    public function domainService(): ?object
    {
        if (static::$domainServiceClass) {
            return app(static::$domainServiceClass);
        }
        return null;
    }

    /**
     * 获取宏（命令处理器）
     */
    public static function getMacros(): array
    {
        return array_merge(static::$handlers, static::$macros);
    }
}
```

## 使用示例

### 模式 A：极简 CRUD（最简单）

**适用场景**：
- 简单的配置表（标签、地区、枚举配置）
- 纯粹的 CRUD，零业务逻辑
- 简单的属性赋值
- 快速原型开发

**只需要 3 个文件！**（比标准 DDD 少 7+ 个文件）

#### 1. 定义领域模型

```php
<?php

namespace RedJasmine\Article\Domain\Tag\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ArticleTag extends Model
{
    use HasSnowflakeId;

    // ✅ 关键：定义可填充字段
    protected $fillable = ['name', 'slug', 'description', 'sort'];
}
```

#### 2. 定义数据传输对象

```php
<?php

namespace RedJasmine\Article\Domain\Tag\Data;

use RedJasmine\Support\Foundation\Data\Data;

class ArticleTagData extends Data
{
    public ?int $id = null;
    public string $name;
    public ?string $slug = null;
    public ?string $description = null;
    public int $sort = 0;
}
```

#### 3. 定义应用服务（极简配置）

```php
<?php

namespace RedJasmine\Article\Application\Tag\Services;

use RedJasmine\Support\Application\ApplicationService;

/**
 * ✅ 极简！只需 3 行配置
 * ✅ 不需要 Repository
 * ✅ 不需要 Transformer
 * ✅ 不需要 DomainService
 */
class ArticleTagApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'article.tag';
    protected static string $modelClass = ArticleTag::class;

    // ✅ 注意：构造函数都不需要！
}
```

#### 4. 使用（自动拥有 CRUD 功能）

```php
// 控制器中使用
$tag = $tagService->create($createCommand);  // 自动 fill() + save()
$tag = $tagService->update($updateCommand);  // 自动 fill() + save()
$tagService->delete($deleteCommand);         // 自动 delete()
$tag = $tagService->find($id);
$tags = $tagService->paginate($query);
```

**完成！只需 3 个文件，零业务代码，零依赖注入！**

**工作原理**：
```php
// DefaultCreateCommandHandler 的智能逻辑

// execute() - 数据填充
protected function execute(HandleContext $context): void
{
    $model = $context->getModel();
    $command = $context->getCommand();
    
    // 1. 有领域服务？→ 调用领域服务 ❌
    // 2. 有转换器？→ 使用转换器 ❌
    // 3. 都没有？→ 使用 Model::fill() ✅
    
    $model->fill($command->toArray());
}

// persist() - 持久化
protected function persist(HandleContext $context): void
{
    $model = $context->getModel();
    
    // 1. 有 Repository？→ 使用 Repository ❌
    // 2. 都没有？→ 直接 save() ✅
    
    $model->save();  // ← 直接使用 Eloquent
}
```

---

### 模式 C：完整 DDD（复杂）

**适用场景**：
- 复杂的业务逻辑
- 多步骤的业务流程
- 跨聚合协调
- 需要领域事件

**实现步骤**：参考之前的完整 DDD 方案

#### 1. 完整的领域服务

```php
class ProductDomainService extends DomainService
{
    public function create(Product $product, ProductData $data): void
    {
        // 复杂的业务逻辑
        $this->validateCreate($data);
        $this->transformer->transform($product, $data);
        $product->initialize();
        $product->setupVariants($data->variants);
        $product->calculatePrice();
        $product->registerEvent(new ProductCreated($product));
    }
}
```

#### 2. 自定义命令处理器

```php
class ProductCreateCommandHandler extends BaseCommandHandler
{
    protected string $name = 'create';

    protected function validate(Data $command): void
    {
        // 自定义验证
    }

    protected function resolve(Data $command): Model
    {
        // 使用工厂
        return $this->productFactory->create($command);
    }

    protected function execute(HandleContext $context): void
    {
        // 调用领域服务
        $this->productDomainService->create(
            $context->getModel(), 
            $context->getCommand()
        );
    }

    protected function persist(HandleContext $context): void
    {
        // 复杂的持久化逻辑
        $this->service->repository->store($context->getModel());
        $this->handleVariants($context->getModel(), $context->getCommand());
        $this->handleStock($context->getModel(), $context->getCommand());
        $this->publishEvents($context->getModel());
    }
}
```

#### 3. 配置应用服务

```php
class ProductApplicationService extends ApplicationService
{
    protected static string $modelClass = Product::class;
    protected static ?string $domainServiceClass = ProductDomainService::class;

    // ✅ 自定义命令处理器
    protected static $macros = [
        'create' => ProductCreateCommandHandler::class,
        'update' => ProductUpdateCommandHandler::class,
        'delete' => ProductDeleteCommandHandler::class,
    ];
}
```

---

## 扩展点总结

### 1. 钩子扩展（最轻量）

无需继承，通过钩子扩展：

```php
// 在 ServiceProvider 中注册钩子
Hook::register('article.tag.create.validate', function ($command) {
    // 自定义验证逻辑
});

Hook::register('article.tag.create.execute', function ($context) {
    // 自定义业务逻辑
});
```

### 2. 领域服务扩展（推荐）

实现需要的方法：

```php
class CategoryDomainService extends DomainService
{
    // ✅ 只实现需要的方法
    public function create(...) { }
    
    // ❌ 不需要的方法不实现
}
```

### 3. 命令处理器扩展（完全控制）

继承默认命令处理器：

```php
class ArticleCreateCommandHandler extends DefaultCreateCommandHandler
{
    // ✅ 只重写需要定制的步骤
    protected function persist(HandleContext $context): void
    {
        parent::persist($context);
        
        // 额外的持久化逻辑
        $this->handleTags($context->getModel());
    }
}
```

或者完全自定义：

```php
class ProductCreateCommandHandler extends BaseCommandHandler
{
    // ✅ 完全自定义四个步骤
    protected function validate(Data $command): void { }
    protected function resolve(Data $command): Model { }
    protected function execute(HandleContext $context): void { }
    protected function persist(HandleContext $context): void { }
}
```

## 对比矩阵

| 特性 | 模式 A (极简) | 模式 C (完整) |
|-----|---------------|-------------|
| **文件数量** | **3 个** 🚀 | 15+ 个 |
| **Model** | ✅ 必需 | ✅ 必需（充血模型） |
| **Data (DTO)** | ✅ 必需 | ✅ 必需 |
| **ApplicationService** | ✅ 必需 | ✅ 必需 |
| **Repository Interface** | ❌ **不需要** | ✅ 必需 |
| **Repository Implementation** | ❌ **不需要** | ✅ 必需 |
| **Transformer** | ❌ 不需要 | ✅ 必需 |
| **DomainService** | ❌ 不需要 | ✅ 必需 |
| **CommandHandler** | ✅ 使用默认 | ✅ 自定义 |
| **Factory** | ❌ 不需要 | 🟡 可选 |
| **Specification** | ❌ 不需要 | 🟡 可选 |
| **DomainEvent** | ❌ 不支持 | ✅ 支持 |
| **数据填充** | `fill()` | `DomainService` |
| **持久化方式** | **`save()`** 🚀 | `Repository` + 复杂逻辑 |
| **业务逻辑位置** | 无业务逻辑 | DomainService + Model |
| **学习成本** | **极低** ⭐ | 高 ⭐⭐⭐ |
| **开发时间** | **5 分钟** | 2-5 天 |
| **代码量** | **~60 行** | ~1000+ 行 |
| **适用场景** | 配置表、简单业务 | 核心业务、复杂逻辑 |
| **DDD 完整度** | 30% | 100% |
| **使用比例** | **80%** | **20%** |

## 迁移路径

### 从模式 A 升级到模式 C

当简单的 CRUD 需要变成复杂业务时：

```php
// 步骤 1：添加 Repository
+ interface ProductRepositoryInterface extends RepositoryInterface {}
+ class ProductRepository extends Repository
+ {
+     protected static string $modelClass = Product::class;
+ }

// 步骤 2：添加 Transformer
+ class ProductTransformer extends Transformer
+ {
+     public function transform($model, $data) { }
+ }

// 步骤 3：添加 DomainService（完整业务逻辑）
+ class ProductDomainService extends DomainService
+ {
+     public function create(Product $product, ProductData $data): void
+     {
+         $this->validateCreate($data);
+         $this->transformer->transform($product, $data);
+         $product->initialize();
+         $product->setupVariants($data->variants);
+         $product->calculatePrice();
+     }
+ }

// 步骤 4：增强 Model 为充血模型
+ class Product extends AggregateRoot
+ {
+     public function initialize(): void { }
+     public function setupVariants(Collection $variants): void { }
+     public function calculatePrice(): void { }
+ }

// 步骤 5：自定义 CommandHandler
+ class ProductCreateCommandHandler extends BaseCommandHandler
+ {
+     protected function resolve(Data $command): Model
+     {
+         return $this->productFactory->create($command);
+     }
+     
+     protected function execute(HandleContext $context): void
+     {
+         $this->productDomainService->create(...);
+     }
+     
+     protected function persist(HandleContext $context): void
+     {
+         $this->repository->store(...);
+         $this->handleVariants(...);
+         $this->publishEvents(...);
+     }
+ }

// 步骤 6：配置 ApplicationService
  class ProductApplicationService extends ApplicationService
  {
+     protected static ?string $domainServiceClass = ProductDomainService::class;
+     protected static $macros = [
+         'create' => ProductCreateCommandHandler::class,
+     ];
      
      public function __construct(
+         public ProductRepositoryInterface $repository,
+         public ProductTransformer $transformer
      ) {}
  }
```

**迁移成本**：2-5 天，风险中等

## 最佳实践建议

### 1. 开始时保持简单

✅ **优先使用模式 A**：除非确定需要业务逻辑，否则使用默认实现  
✅ **按需扩展**：当需求变化时，再迁移到模式 B 或 C  
✅ **避免过度设计**：不要一开始就使用完整 DDD

### 2. 模式选择

| 需求 | 推荐模式 |
|-----|---------|
| 纯 CRUD，无业务逻辑 | 模式 A |
| 简单验证 | 模式 A + 钩子 |
| 简单业务逻辑 | 模式 A + 钩子 |
| 复杂业务逻辑 | 模式 C |
| 跨聚合协调 | 模式 C |
| 需要领域事件 | 模式 C |

### 3. 代码组织

```
极简模块（模式 A）：
  ├── Domain/
  │   ├── Models/              (领域模型)
  │   └── Data/                (DTO)
  └── Application/
      └── Services/            (ApplicationService)

完整模块（模式 C）：
  ├── Domain/
  │   ├── Models/              (充血模型)
  │   ├── Data/                (DTO)
  │   ├── Repositories/        (仓储接口)
  │   ├── Services/            (完整 DomainService)
  │   ├── Transformers/        (数据转换)
  │   ├── Factories/           (工厂)
  │   ├── Specifications/      (规约)
  │   └── Events/              (领域事件)
  ├── Infrastructure/
  │   └── Repositories/        (仓储实现)
  └── Application/
      └── Services/
          ├── ApplicationService
          └── Commands/        (自定义 CommandHandler)
```

## 总结

通过这个方案，你可以：

✅ **二选一，简单明了**：极简 vs 完整，不再纠结  
✅ **保持架构一致性**：所有模块都遵循 DDD 分层  
✅ **80/20 原则**：80% 用模式 A，20% 用模式 C  
✅ **减少样板代码**：简单场景只需 3 个文件  
✅ **渐进式升级**：需要时可以从 A 升级到 C

## 决策指南

```
你的模块是核心业务吗？
│
├─ 不是（配置表、辅助功能）→ 模式 A ⚡
│   ├─ 例如：标签、分类、地区
│   ├─ 文件：3 个
│   └─ 时间：5 分钟
│
└─ 是（商品、订单、支付）→ 模式 C 🏛️
    ├─ 例如：商品管理、订单流程
    ├─ 文件：15+ 个
    └─ 时间：2-5 天
```

**关键原则**：
- 模式 A：**默认选择**，覆盖 80% 场景
- 模式 C：**核心业务**，只用于关键模块
- **不要混用**：一个模块只用一种模式

