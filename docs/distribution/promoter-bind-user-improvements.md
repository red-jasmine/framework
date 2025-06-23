# 分销员绑定用户命令处理器改进

## 改进说明

参考 `RedJasmine\Distribution\Application\Promoter\Services\Commands` 中的实现模式，对分销员绑定用户的命令处理器进行了以下改进：

## 主要改进点

### 1. 事务支持

**改进前：**
```php
public function handle(PromoterBindUserCommand $command): PromoterBindUser
{
    // 直接执行业务逻辑，没有事务保护
    $bindUser = new PromoterBindUser();
    // ...
    return $this->service->repository->store($bindUser);
}
```

**改进后：**
```php
public function handle(PromoterBindUserCommand $command): PromoterBindUser
{
    $this->beginDatabaseTransaction();
    try {
        // 业务逻辑
        $bindUser = $this->service->newModel();
        // ...
        $this->service->repository->store($bindUser);
        
        // 触发事件
        PromoterBindUserEvent::dispatch($bindUser);
        
        $this->commitDatabaseTransaction();
    } catch (AbstractException $abstractException) {
        $this->rollBackDatabaseTransaction();
        throw $abstractException;
    } catch (Throwable $throwable) {
        $this->rollBackDatabaseTransaction();
        throw $throwable;
    }
    
    return $bindUser;
}
```

### 2. 统一的异常处理

**改进内容：**
- 添加了 `AbstractException` 和 `Throwable` 的异常处理
- 确保异常发生时自动回滚事务
- 保持异常的原始类型向上抛出

### 3. 标准的构造函数模式

**改进前：**
```php
public function __construct(
    protected PromoterBindUserApplicationService $service
) {
}
```

**改进后：**
```php
public function __construct(protected PromoterBindUserApplicationService $service)
{
    $this->context = new HandleContext();
}
```

### 4. 使用应用服务的模型工厂

**改进前：**
```php
$bindUser = new PromoterBindUser();
```

**改进后：**
```php
$bindUser = $this->service->newModel();
```

### 5. 事件触发

**新增功能：**
- 在绑定成功后触发 `PromoterBindUserEvent` 事件
- 在解绑成功后触发 `PromoterUnbindUserEvent` 事件
- 事件在事务提交前触发，确保数据一致性

## 完整的命令处理器实现

### PromoterBindUserCommandHandler

```php
<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Commands;

use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Application\PromoterBindUser\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Domain\Events\PromoterBindUser\PromoterBindUserEvent;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class PromoterBindUserCommandHandler extends CommandHandler
{
    public function __construct(protected PromoterBindUserApplicationService $service)
    {
        $this->context = new HandleContext();
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterBindUserCommand $command): PromoterBindUser
    {
        $this->beginDatabaseTransaction();
        try {
            // 检查是否已经绑定
            $existingBind = PromoterBindUser::where('promoter_id', $command->promoterId)
                ->where('user_type', $command->user->getType())
                ->where('user_id', $command->user->getID())
                ->where('status', PromoterBindUserStatusEnum::BOUND)
                ->first();

            if ($existingBind) {
                throw new \InvalidArgumentException('用户已经绑定该分销员');
            }

            // 创建绑定记录
            $bindUser = $this->service->newModel();
            $bindUser->promoter_id = $command->promoterId;
            $bindUser->user_type = $command->user->getType();
            $bindUser->user_id = $command->user->getID();
            $bindUser->status = PromoterBindUserStatusEnum::BOUND;
            $bindUser->bind_time = Carbon::now();
            $bindUser->protection_time = Carbon::now()->addDays(30);
            $bindUser->expiration_time = Carbon::now()->addYear();
            $bindUser->bind_reason = $command->bindReason ?? '邀请注册';
            $bindUser->invitation_code = $command->invitationCode;

            $this->service->repository->store($bindUser);

            // 触发绑定事件
            PromoterBindUserEvent::dispatch($bindUser);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $bindUser;
    }
}
```

### PromoterUnbindUserCommandHandler

```php
<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Commands;

use RedJasmine\Distribution\Application\PromoterBindUser\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Domain\Events\PromoterBindUser\PromoterUnbindUserEvent;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class PromoterUnbindUserCommandHandler extends CommandHandler
{
    public function __construct(protected PromoterBindUserApplicationService $service)
    {
        $this->context = new HandleContext();
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterUnbindUserCommand $command): bool
    {
        $this->beginDatabaseTransaction();
        try {
            // 查找绑定记录
            $bindUser = PromoterBindUser::where('promoter_id', $command->promoterId)
                ->where('user_type', $command->user->getType())
                ->where('user_id', $command->user->getID())
                ->where('status', PromoterBindUserStatusEnum::BOUND)
                ->first();

            if (!$bindUser) {
                throw new \InvalidArgumentException('未找到绑定记录');
            }

            // 检查保护期
            if ($bindUser->protection_time && $bindUser->protection_time->isFuture()) {
                throw new \InvalidArgumentException('绑定关系在保护期内，无法解绑');
            }

            // 更新状态为解绑
            $bindUser->status = PromoterBindUserStatusEnum::UNBOUND;
            $bindUser->unbind_reason = $command->unbindReason ?? '主动解绑';
            $bindUser->unbind_time = now();

            $this->service->repository->update($bindUser);

            // 触发解绑事件
            PromoterUnbindUserEvent::dispatch($bindUser);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return true;
    }
}
```

## 改进带来的好处

1. **数据一致性**: 通过事务确保操作的原子性
2. **异常安全**: 统一的异常处理机制
3. **事件驱动**: 支持领域事件，便于扩展业务逻辑
4. **代码规范**: 遵循项目的标准模式
5. **可维护性**: 清晰的错误处理和日志记录

## 注意事项

1. 事件在事务提交前触发，确保数据已持久化
2. 异常会自动回滚事务，保证数据一致性
3. 使用应用服务的 `newModel()` 方法创建模型实例
4. 所有数据库操作都在事务保护下执行 