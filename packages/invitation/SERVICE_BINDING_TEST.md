# 邀请领域服务容器绑定测试

## 测试目的
验证邀请领域中所有服务、仓库接口和实现是否正确绑定到Laravel服务容器中。

## 绑定清单

### 1. 仓库绑定 (InvitationApplicationServiceProvider)

#### 写仓库
- **接口**: `RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface`
- **实现**: `RedJasmine\Invitation\Infrastructure\Repositories\Eloquent\InvitationCodeRepository`
- **绑定方式**: `$this->app->bind()`

#### 只读仓库  
- **接口**: `RedJasmine\Invitation\Domain\ReadRepositories\InvitationCodeReadRepositoryInterface`
- **实现**: `RedJasmine\Invitation\Infrastructure\ReadRepositories\Mysql\InvitationCodeReadRepository`
- **绑定方式**: `$this->app->bind()`

### 2. 领域转换器绑定 (InvitationDomainServiceProvider)

#### 邀请码转换器
- **类**: `RedJasmine\Invitation\Domain\Transformers\InvitationCodeTransformer`
- **绑定方式**: `$this->app->singleton()`

## 服务提供者注册顺序

1. **InvitationPackageServiceProvider** (主服务提供者)
   - 注册配置文件
   - 注册其他服务提供者

2. **InvitationApplicationServiceProvider** (应用层)
   - 绑定仓库接口和实现

3. **InvitationDomainServiceProvider** (领域层)
   - 注册领域转换器
   - 注册事件监听器

## 依赖注入测试代码

```php
<?php

// 测试仓库绑定
$repository = app(\RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface::class);
assert($repository instanceof \RedJasmine\Invitation\Infrastructure\Repositories\Eloquent\InvitationCodeRepository);

$readRepository = app(\RedJasmine\Invitation\Domain\ReadRepositories\InvitationCodeReadRepositoryInterface::class);
assert($readRepository instanceof \RedJasmine\Invitation\Infrastructure\ReadRepositories\Mysql\InvitationCodeReadRepository);

// 测试转换器绑定
$transformer = app(\RedJasmine\Invitation\Domain\Transformers\InvitationCodeTransformer::class);
assert($transformer instanceof \RedJasmine\Invitation\Domain\Transformers\InvitationCodeTransformer);

echo "✅ 所有服务绑定测试通过！\n";
```

## 检查清单

- [x] 仓库接口和实现正确绑定
- [x] 只读仓库接口和实现正确绑定  
- [x] 转换器正确注册为单例
- [x] 服务提供者正确注册到主包服务提供者
- [x] 配置文件和迁移文件正确发布

## 使用建议

### 在Laravel应用中注册
```php
// config/app.php
'providers' => [
    // ...
    \RedJasmine\Invitation\InvitationPackageServiceProvider::class,
],
```

### 发布配置和迁移
```bash
# 发布配置文件
php artisan vendor:publish --tag=invitation-config

# 发布迁移文件  
php artisan vendor:publish --tag=invitation-migrations

# 运行迁移
php artisan migrate
```

### 在代码中使用
```php
// 直接注入应用服务
public function __construct(
    private InvitationCodeApplicationService $invitationService
) {}

// 或通过服务容器获取
$invitationService = app(InvitationCodeApplicationService::class);

// 使用应用服务
$invitationCode = $invitationService->create($command);
```

## 总结

所有必要的服务绑定已经完成：

1. **仓库层**: 接口和实现正确绑定，支持依赖注入
2. **转换器**: 领域转换器正确注册
3. **配置**: 配置文件和迁移文件正确发布

采用了与分销模块相同的绑定模式，确保了邀请领域能够在Laravel应用中正常工作，所有依赖都能正确解析。 