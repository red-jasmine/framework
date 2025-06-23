# 分销员绑定用户功能 - 目录结构调整

## 调整说明

根据项目规范，对分销员绑定用户功能的目录结构进行了以下调整：

### 1. 控制器目录调整

**调整前：**
```
packages/distribution/src/UI/Http/Admin/Api/PromoterBindUserController.php
packages/distribution/src/UI/Http/User/Api/PromoterBindUserController.php
```

**调整后：**
```
packages/distribution/src/UI/Http/Admin/Api/Controllers/PromoterBindUserController.php
packages/distribution/src/UI/Http/User/Api/Controllers/PromoterBindUserController.php
```

**变更内容：**
- 控制器统一放在 `Controllers` 目录下
- 更新了命名空间：
  - `RedJasmine\Distribution\UI\Http\Admin\Api\Controllers`
  - `RedJasmine\Distribution\UI\Http\User\Api\Controllers`
- 更新了路由管理器中的引用

### 2. CommandHandler 目录调整

**调整前：**
```
packages/distribution/src/Application/PromoterBindUser/CommandHandlers/PromoterBindUserCommandHandler.php
packages/distribution/src/Application/PromoterBindUser/CommandHandlers/PromoterUnbindUserCommandHandler.php
```

**调整后：**
```
packages/distribution/src/Application/PromoterBindUser/Commands/PromoterBindUserCommandHandler.php
packages/distribution/src/Application/PromoterBindUser/Commands/PromoterUnbindUserCommandHandler.php
```

**变更内容：**
- CommandHandler 统一放在 `Commands` 目录下，与对应的 Command 类在同一目录
- 更新了命名空间：`RedJasmine\Distribution\Application\PromoterBindUser\Commands`
- 更新了应用服务中的引用

### 3. Events 目录调整

**调整前：**
```
packages/distribution/src/Domain/Events/PromoterBindUserEvent.php
packages/distribution/src/Domain/Events/PromoterUnbindUserEvent.php
```

**调整后：**
```
packages/distribution/src/Domain/Events/PromoterBindUser/PromoterBindUserEvent.php
packages/distribution/src/Domain/Events/PromoterBindUser/PromoterUnbindUserEvent.php
```

**变更内容：**
- Events 按功能模块分目录存放
- 更新了命名空间：`RedJasmine\Distribution\Domain\Events\PromoterBindUser`

## 调整后的完整目录结构

```
packages/distribution/src/
├── Domain/
│   ├── Models/
│   │   ├── PromoterBindUser.php
│   │   └── Enums/
│   │       └── PromoterBindUserStatusEnum.php
│   │
│   ├── Data/
│   │   └── PromoterBindUserData.php
│   │
│   ├── Repositories/
│   │   ├── PromoterBindUserRepositoryInterface.php
│   │   └── PromoterBindUserReadRepositoryInterface.php
│   │
│   ├── Transformers/
│   │   └── PromoterBindUserTransformer.php
│   │
│   └── Events/
│       └── PromoterBindUser/
│           ├── PromoterBindUserEvent.php
│           └── PromoterUnbindUserEvent.php
│
├── Application/
│   └── PromoterBindUser/
│       ├── Commands/
│       │   ├── PromoterBindUserCommand.php
│       │   ├── PromoterUnbindUserCommand.php
│       │   ├── PromoterBindUserCommandHandler.php
│       │   └── PromoterUnbindUserCommandHandler.php
│       │
│       ├── Queries/
│       │   ├── PromoterBindUserPaginateQuery.php
│       │   └── PromoterBindUserFindQuery.php
│       │
│       └── PromoterBindUserApplicationService.php
│
├── Infrastructure/
│   ├── Repositories/
│   │   └── Eloquent/
│   │       └── PromoterBindUserRepository.php
│   │
│   └── ReadRepositories/
│       └── Mysql/
│           └── PromoterBindUserReadRepository.php
│
└── UI/
    └── Http/
        ├── Admin/
        │   ├── Api/
        │   │   ├── Controllers/
        │   │   │   └── PromoterBindUserController.php
        │   │   │
        │   │   └── Resources/
        │   │       └── PromoterBindUserResource.php
        │   │
        │   └── DistributionAdminRoute.php
        │
        └── User/
            ├── Api/
            │   ├── Controllers/
            │   │   └── PromoterBindUserController.php
            │   │
            │   └── Resources/
            │       └── PromoterBindUserResource.php
            │
            └── DistributionUserRoute.php
```

## 规范说明

1. **控制器规范：** 所有控制器都应放在 `Controllers` 目录下
2. **命令处理器规范：** CommandHandler 与对应的 Command 放在同一 `Commands` 目录下
3. **事件规范：** Events 按功能模块分目录组织，便于管理和维护
4. **命名空间规范：** 命名空间要与目录结构保持一致

## 影响范围

本次调整涉及的文件：
- 2个控制器文件（移动位置并更新命名空间）
- 2个命令处理器文件（移动位置并更新命名空间）
- 2个事件文件（移动位置并更新命名空间）
- 2个路由管理器文件（更新引用）
- 1个应用服务文件（更新引用）

所有功能保持不变，仅调整了目录结构以符合项目规范。 