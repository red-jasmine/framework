# 分销员绑定用户命令处理器 - 最终改进总结

## 改进对比

### 改进前的实现问题

```php
class PromoterBindUserCommandHandler extends CommandHandler
{
    public function __construct(
        protected PromoterBindUserApplicationService $service
    ) {
    }

    public function handle(PromoterBindUserCommand $command): PromoterBindUser
    {
        // ❌ 没有事务保护
        // ❌ 没有异常处理
        // ❌ 直接 new 模型实例
        // ❌ 没有事件触发
        
        $existingBind = PromoterBindUser::where(...)->first();
        $bindUser = new PromoterBindUser();
        // ...
        return $this->service->repository->store($bindUser);
    }
}
```

### 改进后的实现

```php
class PromoterBindUserCommandHandler extends CommandHandler
{
    public function __construct(protected PromoterBindUserApplicationService $service)
    {
        $this->context = new HandleContext(); // ✅ 标准构造函数
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterBindUserCommand $command): PromoterBindUser
    {
        $this->beginDatabaseTransaction(); // ✅ 开始事务
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

            // ✅ 使用应用服务的模型工厂
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

            // ✅ 触发领域事件
            PromoterBindUserEvent::dispatch($bindUser);

            $this->commitDatabaseTransaction(); // ✅ 提交事务
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction(); // ✅ 回滚事务
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction(); // ✅ 回滚事务
            throw $throwable;
        }

        return $bindUser;
    }
}
```

## 具体改进点

### 1. ✅ 事务支持
- **添加了完整的事务管理**
  - `beginDatabaseTransaction()` - 开始事务
  - `commitDatabaseTransaction()` - 提交事务
  - `rollBackDatabaseTransaction()` - 回滚事务

### 2. ✅ 异常处理
- **统一的异常处理机制**
  - 捕获 `AbstractException` 和 `Throwable`
  - 异常时自动回滚事务
  - 保持异常的原始类型向上抛出

### 3. ✅ 标准构造函数
- **遵循项目规范**
  - 初始化 `HandleContext`
  - 标准的依赖注入模式

### 4. ✅ 使用应用服务模型工厂
- **替换直接实例化**
  - 从 `new PromoterBindUser()` 改为 `$this->service->newModel()`
  - 确保模型实例化的一致性

### 5. ✅ 领域事件触发
- **事件驱动架构**
  - 绑定成功后触发 `PromoterBindUserEvent`
  - 解绑成功后触发 `PromoterUnbindUserEvent`
  - 事件在事务提交前触发，确保数据一致性

### 6. ✅ 完整的文档注释
- **添加异常声明**
  - `@throws AbstractException`
  - `@throws Throwable`

## 解绑命令处理器同样改进

```php
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

## 技术收益

1. **数据一致性**: 通过事务确保操作的原子性
2. **异常安全**: 统一的异常处理和事务回滚机制
3. **事件驱动**: 支持领域事件，便于扩展业务逻辑
4. **代码规范**: 完全遵循项目的命令处理器标准模式
5. **可维护性**: 清晰的错误处理和事务边界

## 关于仓库使用的说明

在查询操作中，我们保持使用 `PromoterBindUser::where()` 直接查询，这是因为：

1. **简单性**: 对于简单的存在性检查，直接使用模型查询更简洁
2. **性能**: 避免了仓库层的额外抽象开销
3. **一致性**: 与项目中其他命令处理器的模式保持一致

如果需要通过仓库进行查询，可以考虑在仓库接口中添加专门的查询方法，如：
```php
interface PromoterBindUserRepositoryInterface 
{
    public function findExistingBind(int $promoterId, string $userType, string $userId): ?PromoterBindUser;
}
```

## 总结

命令处理器现在完全符合项目规范，具备了：
- ✅ 完整的事务支持
- ✅ 统一的异常处理
- ✅ 标准的构造函数模式
- ✅ 应用服务模型工厂使用
- ✅ 领域事件触发
- ✅ 清晰的代码结构和注释

所有改进都参考了 `RedJasmine\Distribution\Application\Promoter\Services\Commands` 中的实现模式，确保了代码的一致性和可维护性。 