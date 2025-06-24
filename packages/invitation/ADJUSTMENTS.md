# 邀请领域代码调整总结

根据项目标准规范，对邀请领域代码进行了以下调整：

## 1. 仓库结构调整

### 仓库实现命名调整
- **调整前**: `Infrastructure\Repositories\EloquentInvitationCodeRepository`
- **调整后**: `Infrastructure\Repositories\Eloquent\InvitationCodeRepository`
- **说明**: 去掉了Eloquent前缀，按目录区分

### 新增只读仓库
- **新增接口**: `Domain\ReadRepositories\InvitationCodeReadRepositoryInterface`
- **新增实现**: `Infrastructure\ReadRepositories\Mysql\InvitationCodeReadRepository`
- **说明**: 按照项目标准，分离读写操作

## 2. 枚举类调整

所有枚举类都调整为使用`RedJasmine\Support\Helpers\Enums\EnumsHelper`：

### 调整内容
- 添加 `use RedJasmine\Support\Helpers\Enums\EnumsHelper;`
- 移除原有的方法实现
- 只保留三个核心方法：
  - `labels()`: 返回标签数组
  - `icons()`: 返回图标数组  
  - `colors()`: 返回颜色数组

### 调整的枚举类
1. `GenerateType` - 邀请码生成类型
2. `CodeStatus` - 邀请码状态
3. `DestinationType` - 邀请去向类型
4. `PlatformType` - 平台类型
5. `ActionType` - 动作类型

## 3. 应用服务调整

### InvitationCodeApplicationService
- 添加了只读仓库注入：`InvitationCodeReadRepositoryInterface $readRepository`
- 修改属性为public，符合项目标准
- 添加了`$modelClass`属性
- 调整了构造函数依赖注入

## 4. 服务提供者调整

### InvitationPackageServiceProvider
- 更新了仓库接口绑定路径
- 新增了只读仓库接口绑定
- 更新了相关的use语句

## 5. 控制器完善

### InvitationCodeController
- 重新创建了完整的API控制器
- 包含标准的CRUD操作
- 添加了邀请码使用、链接生成等特殊功能

## 6. 目录结构

调整后的目录结构更符合项目标准：

```
src/
├── Domain/
│   ├── Models/
│   │   ├── Enums/          # 枚举类（使用EnumsHelper）
│   │   └── ValueObjects/   # 值对象
│   ├── Repositories/       # 写仓库接口
│   └── ReadRepositories/   # 只读仓库接口
├── Infrastructure/
│   ├── Repositories/
│   │   └── Eloquent/       # Eloquent仓库实现（无Eloquent前缀）
│   └── ReadRepositories/
│       └── Mysql/          # MySQL只读仓库实现（无Mysql前缀）
├── Application/
│   └── Services/           # 应用服务（注入读写仓库）
└── UI/
    └── Http/
        └── Api/
            └── Controllers/ # API控制器
```

## 7. 主要变更点

1. **命名规范**: 仓库实现类去掉技术前缀，通过目录区分
2. **读写分离**: 明确区分读写仓库，提高性能和扩展性
3. **枚举标准化**: 统一使用EnumsHelper，简化枚举类实现
4. **依赖注入**: 应用服务同时注入读写仓库，支持更多查询场景
5. **属性可见性**: 构造函数参数使用public属性，符合项目习惯

所有调整都符合项目的DDD架构规范和编码标准。 