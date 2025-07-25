---
alwaysApply: true
---

# 领域层(Domain)代码规范

## 领域模型 (Models)

### 规范
- **充血模型**: 领域模型采用充血模型策略，业务逻辑封装在模型内部
- **Trait复用**: 使用 Trait 复用通用功能
- **ID生成**: 通常使用雪花ID作为主键，通过 `HasSnowflakeId` Trait实现
- **所属者管理**: 使用 `HasOwner` Trait管理所属者信息
- **审批功能**: 有审批需求时添加审批功能
- **生命周期钩子**: 使用 `saving`、`deleting`、`restoring` 等钩子处理业务逻辑

### 必需配置
```php
class Product extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;
    
    protected static function boot(): void
    {
        parent::boot();
        // 生命周期钩子
        static::saving(function ($model) {
            // 保存时的业务逻辑
        });
    }
}
```

## 枚举 (Enums)

### 规范
- **辅助Trait**: 使用 `RedJasmine\Support\Helpers\Enums\EnumsHelper` Trait
- **标签配置**: 实现 `labels()` 方法返回枚举标签映射
- **颜色配置**: 实现 `colors()` 方法返回枚举颜色映射
- **图标配置**: 实现 `icons()` 方法返回枚举图标映射

### 代码示例
```php
enum StatusEnum: string
{
    use EnumsHelper;
    
    case ENABLE = 'enable';
    case DISABLE = 'disable';
    
    public static function labels(): array
    {
        return [
            self::ENABLE->value => '启用',
            self::DISABLE->value => '禁用',
        ];
    }
}
```

## 值对象 (ValueObjects)

### 规范
- **基类继承**: 继承 `RedJasmine\Support\Domain\Models\ValueObjects\ValueObject`
- **不可变性**: 值对象应该是不可变的，所有属性都是只读的
- **类型安全**: 使用强类型声明和PHPDoc注释
- **业务表达**: 值对象应该表达特定的业务概念

### 代码示例
```php
class Property extends ValueObject
{
    public int $pid;
    public string $name;
    public ?string $unit;
    public Collection $values;
}
```

## 领域服务 (Domain Services)

### 规范
- **职责明确**: 处理不属于任何实体或值对象的业务逻辑
- **无状态性**: 领域服务应该是无状态的
- **业务聚焦**: 专注于特定的业务逻辑处理
- **命名规范**: 服务名格式为 `{业务概念}{Service/Formatter/Calculator}`

## 数据传输对象 (Data)

### 规范
- **基类继承**: 继承 `RedJasmine\Support\Data\Data`
- **属性类型**: 使用强类型声明，支持 PHP 8.3+ 类型系统
- **枚举转换**: 使用 `#[WithCast(EnumCast::class)]` 属性进行枚举转换
- **用户类型**: 使用 `RedJasmine\Support\Contracts\UserInterface` 作为用户类型

### 代码示例
```php
class ArticleData extends Data
{
    public UserInterface $owner;
    public string $title;
    
    #[WithCast(EnumCast::class, ContentTypeEnum::class)]
    public ContentTypeEnum $contentType = ContentTypeEnum::RICH;
    
    public string $content;
    public ?string $image = null;
}
```

## 转换器 (Transformers)

### 规范
- **接口实现**: 实现 `RedJasmine\Support\Domain\Transformer\TransformerInterface`
- **方法定义**: `transform($data, $model): Model`
- **命名规范**: 转换器名格式为 `{Entity}Transformer`
- **数据映射**: 负责将DTO数据映射到领域模型

## 仓库接口 (Repositories)

### 规范
- **写操作仓库**: 继承 `RedJasmine\Support\Domain\Repositories\RepositoryInterface`
- **只读仓库**: 继承 `RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface`
- **命名规范**: 接口名格式为 `{Entity}RepositoryInterface`
- **职责分离**: 写操作和读操作分离
# 领域层(Domain)代码规范

## 领域模型 (Models)

### 规范
- **充血模型**: 领域模型采用充血模型策略，业务逻辑封装在模型内部
- **Trait复用**: 使用 Trait 复用通用功能
- **ID生成**: 通常使用雪花ID作为主键，通过 `HasSnowflakeId` Trait实现
- **所属者管理**: 使用 `HasOwner` Trait管理所属者信息
- **审批功能**: 有审批需求时添加审批功能
- **生命周期钩子**: 使用 `saving`、`deleting`、`restoring` 等钩子处理业务逻辑

### 必需配置
```php
class Product extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;
    
    protected static function boot(): void
    {
        parent::boot();
        // 生命周期钩子
        static::saving(function ($model) {
            // 保存时的业务逻辑
        });
    }
}
```

## 枚举 (Enums)

### 规范
- **辅助Trait**: 使用 `RedJasmine\Support\Helpers\Enums\EnumsHelper` Trait
- **标签配置**: 实现 `labels()` 方法返回枚举标签映射
- **颜色配置**: 实现 `colors()` 方法返回枚举颜色映射
- **图标配置**: 实现 `icons()` 方法返回枚举图标映射

### 代码示例
```php
enum StatusEnum: string
{
    use EnumsHelper;
    
    case ENABLE = 'enable';
    case DISABLE = 'disable';
    
    public static function labels(): array
    {
        return [
            self::ENABLE->value => '启用',
            self::DISABLE->value => '禁用',
        ];
    }
}
```

## 值对象 (ValueObjects)

### 规范
- **基类继承**: 继承 `RedJasmine\Support\Domain\Models\ValueObjects\ValueObject`
- **不可变性**: 值对象应该是不可变的，所有属性都是只读的
- **类型安全**: 使用强类型声明和PHPDoc注释
- **业务表达**: 值对象应该表达特定的业务概念

### 代码示例
```php
class Property extends ValueObject
{
    public int $pid;
    public string $name;
    public ?string $unit;
    public Collection $values;
}
```

## 领域服务 (Domain Services)

### 规范
- **职责明确**: 处理不属于任何实体或值对象的业务逻辑
- **无状态性**: 领域服务应该是无状态的
- **业务聚焦**: 专注于特定的业务逻辑处理
- **命名规范**: 服务名格式为 `{业务概念}{Service/Formatter/Calculator}`

## 数据传输对象 (Data)

### 规范
- **基类继承**: 继承 `RedJasmine\Support\Data\Data`
- **属性类型**: 使用强类型声明，支持 PHP 8.3+ 类型系统
- **枚举转换**: 使用 `#[WithCast(EnumCast::class)]` 属性进行枚举转换
- **用户类型**: 使用 `RedJasmine\Support\Contracts\UserInterface` 作为用户类型

### 代码示例
```php
class ArticleData extends Data
{
    public UserInterface $owner;
    public string $title;
    
    #[WithCast(EnumCast::class, ContentTypeEnum::class)]
    public ContentTypeEnum $contentType = ContentTypeEnum::RICH;
    
    public string $content;
    public ?string $image = null;
}
```

## 转换器 (Transformers)

### 规范
- **接口实现**: 实现 `RedJasmine\Support\Domain\Transformer\TransformerInterface`
- **方法定义**: `transform($data, $model): Model`
- **命名规范**: 转换器名格式为 `{Entity}Transformer`
- **数据映射**: 负责将DTO数据映射到领域模型

## 仓库接口 (Repositories)

### 规范
- **写操作仓库**: 继承 `RedJasmine\Support\Domain\Repositories\RepositoryInterface`
- **只读仓库**: 继承 `RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface`
- **命名规范**: 接口名格式为 `{Entity}RepositoryInterface`
- **职责分离**: 写操作和读操作分离
