---
globs: ["packages/filament-*/src/**/*.php"]
description: "Filament 管理界面编码规范，适用于基于 Filament 的管理面板开发"
---

# Filament 管理界面编码规范

## 包结构规范

### 包命名规范
- 使用 `filament-{domain}` 命名格式
- 例如：`filament-article`、`filament-admin`、`filament-core`

### 目录结构
```
packages/filament-{domain}/
├── src/
│   ├── {DomainName}ServiceProvider.php    # 服务提供者
│   ├── {DomainName}Plugin.php             # 插件类
│   ├── Clusters/                          # 功能集群
│   │   ├── {ClusterName}.php              # 集群定义
│   │   └── {ClusterName}/                 # 集群资源
│   │       └── Resources/                 # 资源文件
│   ├── Resources/                         # 独立资源
│   ├── Forms/                             # 表单组件
│   ├── Columns/                           # 列组件
│   ├── Filters/                           # 过滤器
│   ├── Actions/                           # 动作
│   └── Helpers/                           # 帮助类
├── config/
├── resources/
│   ├── lang/                              # 语言包
│   └── views/                             # 视图文件
└── composer.json
```

## 核心类规范

### 1. ServiceProvider 服务提供者
```php
<?php

namespace RedJasmine\Filament{Domain};

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class Filament{Domain}ServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-{domain}';
    public static string $viewNamespace = 'red-jasmine-filament-{domain}';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->hasConfigFile()
                ->hasTranslations()
                ->hasViews(static::$viewNamespace);
    }

    public function packageBooted(): void
    {
        // 资源注册
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );
        
        // 图标注册
        FilamentIcon::register($this->getIcons());
        
        // 宏定义
        $this->registerMacros();
    }
}
```

### 2. Plugin 插件类
```php
<?php

namespace RedJasmine\Filament{Domain};

use Filament\Contracts\Plugin;
use Filament\Panel;

class Filament{Domain}Plugin implements Plugin
{
    public function getId(): string
    {
        return 'red-jasmine-filament-{domain}';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverClusters(
            in: __DIR__ . '/Clusters/', 
            for: 'RedJasmine\\Filament{Domain}\\Clusters'
        );
    }

    public function boot(Panel $panel): void
    {
        // 启动逻辑
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());
        return $plugin;
    }
}
```

### 3. Cluster 集群类
```php
<?php

namespace RedJasmine\Filament{Domain}\Clusters;

use Filament\Clusters\Cluster;

class {ClusterName} extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-c-document-text';

    public static function getNavigationLabel(): string
    {
        return __('red-jasmine-filament-{domain}::{cluster}.label');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('red-jasmine-filament-{domain}::{cluster}.label');
    }
}
```

## 资源类规范

### 1. Resource 资源类
```php
<?php

namespace RedJasmine\Filament{Domain}\Clusters\{ClusterName}\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use RedJasmine\{Domain}\Application\Services\{Entity}\{Entity}ApplicationService;
use RedJasmine\{Domain}\Domain\Data\{Entity}Data;
use RedJasmine\{Domain}\Domain\Models\{Entity};
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class {Entity}Resource extends Resource
{
    use ResourcePageHelper;

    // 必需的静态属性
    protected static string $service = {Entity}ApplicationService::class;
    protected static ?string $createCommand = {Entity}Data::class;
    protected static ?string $updateCommand = {Entity}Data::class;
    protected static bool $onlyOwner = true;

    protected static ?string $model = {Entity}::class;
    protected static ?string $navigationIcon = 'heroicon-c-document-text';
    protected static ?string $cluster = {ClusterName}::class;

    public static function getModelLabel(): string
    {
        return __('red-jasmine-{domain}::{entity}.labels.title');
    }

    public static function callFindQuery(FindQuery $findQuery): FindQuery
    {
        $findQuery->include = ['relation1', 'relation2'];
        return $findQuery;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // 表单字段定义
            Forms\Components\Split::make([
                Forms\Components\Section::make([
                    // 主要字段
                ]),
                Forms\Components\Section::make([
                    // 辅助字段
                    ...static::ownerFormSchemas(),
                    ...static::operateFormSchemas()
                ])->grow(false),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 表格列定义
                ...static::ownerTableColumns(),
                ...static::operateTableColumns()
            ])
            ->filters([
                // 过滤器定义
            ])
            ->actions([
                // 行动作定义
            ])
            ->bulkActions([
                // 批量动作定义
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // 关系管理器
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\List{Entities}::route('/'),
            'create' => Pages\Create{Entity}::route('/create'),
            'edit' => Pages\Edit{Entity}::route('/{record}/edit'),
        ];
    }
}
```

### 2. Page 页面类
```php
<?php

namespace RedJasmine\Filament{Domain}\Clusters\{ClusterName}\Resources\{Entity}Resource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class Create{Entity} extends CreateRecord
{
    use ResourcePageHelper;
    
    protected static string $resource = {Entity}Resource::class;
}
```

## 组件开发规范

### 1. 表单字段组件
```php
<?php

namespace RedJasmine\FilamentCore\Forms\Fields;

use Filament\Forms\Components\Field;

class {ComponentName} extends Field
{
    protected string $view = 'red-jasmine-filament-core::forms.fields.{component-name}';

    protected function setUp(): void
    {
        parent::setUp();
        
        // 组件初始化逻辑
        $this->afterStateHydrated(function ({ComponentName} $component, $state) {
            // 状态处理逻辑
        });
    }
}
```

### 2. 表格列组件
```php
<?php

namespace RedJasmine\FilamentCore\Columns;

use Filament\Tables\Columns\Column;

class {ComponentName} extends Column
{
    protected string $view = 'red-jasmine-filament-core::columns.{component-name}';

    protected function setUp(): void
    {
        parent::setUp();
        
        // 列初始化逻辑
    }
}
```

### 3. 过滤器组件
```php
<?php

namespace RedJasmine\FilamentCore\Filters;

use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class {FilterName} extends Filter
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->form([
            // 表单定义
        ]);
        
        $this->query(function (Builder $query, array $data): Builder {
            // 查询逻辑
        });
    }
}
```

## 编码规范

### 1. 命名规范
- **类名**: 使用 PascalCase，如 `ArticleResource`
- **方法名**: 使用 camelCase，如 `getModelLabel()`
- **静态属性**: 使用 camelCase，如 `$onlyOwner`
- **常量**: 使用 UPPER_SNAKE_CASE
- **翻译键**: 使用 snake_case，如 `article.labels.title`

### 2. 静态属性规范
```php
// 必需的静态属性
protected static string $service = {Entity}ApplicationService::class;
protected static ?string $createCommand = {Entity}Data::class;
protected static ?string $updateCommand = {Entity}Data::class;
protected static bool $onlyOwner = true;

// Filament 原生属性
protected static ?string $model = {Entity}::class;
protected static ?string $navigationIcon = 'heroicon-c-document-text';
protected static ?string $cluster = {ClusterName}::class;
```

### 3. 方法实现规范
```php
// 模型标签
public static function getModelLabel(): string
{
    return __('red-jasmine-{domain}::{entity}.labels.title');
}

// 查询定制
public static function callFindQuery(FindQuery $findQuery): FindQuery
{
    $findQuery->include = ['relation1', 'relation2'];
    return $findQuery;
}

// 解析记录
public static function callResolveRecord(Model $model): Model
{
    // 记录解析逻辑
    return $model;
}
```

### 4. 表单设计规范
```php
public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Split::make([
            // 主要内容区域
            Forms\Components\Section::make([
                // 核心字段
            ]),
            // 辅助信息区域
            Forms\Components\Section::make([
                // 所有者字段
                ...static::ownerFormSchemas(),
                // 操作字段
                ...static::operateFormSchemas()
            ])->grow(false),
        ])->columnSpanFull(),
    ]);
}
```

### 5. 表格设计规范
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            // 基础列
            Tables\Columns\TextColumn::make('id')
                ->label(__('red-jasmine-filament-{domain}::{entity}.fields.id'))
                ->sortable(),
            
            // 所有者列
            ...static::ownerTableColumns(),
            
            // 枚举列使用 useEnum() 方法
            Tables\Columns\TextColumn::make('status')
                ->useEnum(),
            
            // 操作列
            ...static::operateTableColumns()
        ])
        ->filters([
            // 过滤器定义
        ])
        ->actions([
            // 行动作
        ])
        ->bulkActions([
            // 批量动作
        ]);
}
```

## 国际化规范

### 1. 翻译键结构
```php
// 语言包结构
return [
    'label' => '标签',
    'labels' => [
        'title' => '标题',
        'description' => '描述',
    ],
    'fields' => [
        'id' => 'ID',
        'name' => '名称',
        'status' => '状态',
    ],
    'commands' => [
        'create' => '创建',
        'edit' => '编辑',
        'delete' => '删除',
    ],
];
```

### 2. 使用规范
```php
// 标签使用
->label(__('red-jasmine-{domain}::{entity}.fields.name'))

// 操作使用
->label(__('red-jasmine-{domain}::{entity}.commands.create'))
```
### 3.翻译文件存放在对应的领域包内
- 如 red-jasmine-filament-product，这个是商品领域的管理面板，那么字段、命令、标题的翻译就应该在 red-jasmine-product 内
  
## 架构集成规范

### 1. 与业务层解耦
- 通过应用服务交互，不直接操作模型
- 使用命令模式处理数据操作
- 使用查询对象处理数据查询

### 2. 权限控制
```php
// 支持多所有者模式
protected static bool $onlyOwner = true;

// 在查询中应用权限
public static function getEloquentQuery(): Builder
{
    $query = app(static::$service)->readRepository->modelQuery();
    
    if (static::onlyOwner()) {
        $user = auth()->user();
        if (!($user->isAdministrator() ?? false)) {
            $owner = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
            $query->onlyOwner($owner);
        }
    }
    
    return $query;
}
```

### 3. 数据处理
```php
// 创建记录
protected function handleRecordCreation(array $data): Model
{
    $resource = static::getResource();
    $commandService = app($resource::getService());
    $command = ($resource::getCreateCommand())::from($data);
    
    return $commandService->create($command);
}

// 更新记录
protected function handleRecordUpdate(Model $record, array $data): Model
{
    $resource = static::getResource();
    $commandService = app($resource::getService());
    $command = ($resource::getUpdateCommand())::from($data);
    $command->setKey($record->getKey());
    
    return $commandService->update($command);
}
```

## 扩展功能规范

### 1. 宏定义
```php
// 在 ServiceProvider 中注册宏
public function packageBooted(): void
{
    // 列宏 - 枚举使用
    Column::macro('useEnum', function () {
        if (method_exists($this, 'badge')) {
            $this->badge();
        }
        if (method_exists($this, 'formatStateUsing')) {
            $this->formatStateUsing(fn($state) => $state->getLabel());
        }
        if (method_exists($this, 'color')) {
            $this->color(fn($state) => $state->getColor());
        }
        return $this;
    });
    
    // 字段宏 - 枚举使用
    Field::macro('useEnum', function (string $enumClassName) {
        $this->enum($enumClassName);
        if (method_exists($this, 'options')) {
            $this->options($enumClassName::options());
        }
        return $this;
    });
}
```

### 2. 帮助类使用
```php
// 使用 ResourcePageHelper
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class {Entity}Resource extends Resource
{
    use ResourcePageHelper;
    
    // 自动处理所有者相关逻辑
    // 自动处理操作相关逻辑
}
```

## 测试规范

### 1. 功能测试
```php
public function test_can_list_resources(): void
{
    $this->actingAs($this->user)
         ->get(route('filament.admin.resources.{entities}.index'))
         ->assertSuccessful();
}

public function test_can_create_resource(): void
{
    $this->actingAs($this->user)
         ->post(route('filament.admin.resources.{entities}.store'), [
             'name' => 'Test Name',
             // 其他字段
         ])
         ->assertRedirect();
}
```

### 2. 权限测试
```php
public function test_only_owner_can_access_resources(): void
{
    $otherUser = User::factory()->create();
    
    $this->actingAs($otherUser)
         ->get(route('filament.admin.resources.{entities}.index'))
         ->assertStatus(403);
}
```

## 性能优化

### 1. 查询优化
```php
// 预加载关联
public static function callFindQuery(FindQuery $findQuery): FindQuery
{
    $findQuery->include = ['category', 'tags', 'extension'];
    return $findQuery;
}

// 表格查询优化
public static function table(Table $table): Table
{
    return $table
        ->columns([
            // 避免 N+1 查询
            Tables\Columns\TextColumn::make('category.name')
                ->label(__('Category'))
        ])
        ->modifyQueryUsing(fn (Builder $query) => $query->with(['category']));
}
```

### 2. 缓存使用
```php
// 选项缓存
Forms\Components\Select::make('category_id')
    ->options(fn () => Cache::remember('categories', 3600, fn () => 
        Category::pluck('name', 'id')
    ));
```

这套规范确保了 Filament 管理界面的一致性、可维护性和扩展性。
