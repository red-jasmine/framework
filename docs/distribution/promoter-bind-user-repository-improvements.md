# 分销员绑定用户仓库查询方法封装改进

## 改进目标

将应用层中的复杂查询条件封装到仓库层，让应用层调用更简洁的接口方法，提高代码的可维护性和复用性。

## 改进内容

### 1. 仓库接口定义

#### PromoterBindUserRepositoryInterface
```php
interface PromoterBindUserRepositoryInterface extends RepositoryInterface
{
    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user): ?PromoterBindUser;
}
```

#### PromoterBindUserReadRepositoryInterface
```php
interface PromoterBindUserReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user): ?PromoterBindUser;
    
    /**
     * 查找用户与分销员的有效绑定关系
     */
    public function findActiveBind(int $promoterId, UserInterface $user): ?PromoterBindUser;
    
    /**
     * 查找用户的当前有效绑定关系（不指定分销员）
     */
    public function findUserActiveBind(UserInterface $user): ?PromoterBindUser;
}
```

### 2. 仓库实现

#### PromoterBindUserRepository（写仓库）
```php
public function findBindRelation(int $promoterId, UserInterface $user): ?PromoterBindUser
{
    /** @var PromoterBindUser $modelClass */
    $modelClass = static::$eloquentModelClass;
    return $modelClass::query()
        ->where('promoter_id', $promoterId)
        ->where('user_type', $user->getType())
        ->where('user_id', $user->getID())
        ->first();
}
```

#### PromoterBindUserReadRepository（读仓库）
```php
public function findBindRelation(int $promoterId, UserInterface $user): ?PromoterBindUser
{
    return $this->query()
        ->where('promoter_id', $promoterId)
        ->where('user_type', $user->getType())
        ->where('user_id', $user->getID())
        ->first();
}

public function findActiveBind(int $promoterId, UserInterface $user): ?PromoterBindUser
{
    return $this->query()
        ->where('promoter_id', $promoterId)
        ->where('user_type', $user->getType())
        ->where('user_id', $user->getID())
        ->where('status', PromoterBindUserStatusEnum::BOUND)
        ->where('expires_at', '>', now())
        ->first();
}

public function findUserActiveBind(UserInterface $user): ?PromoterBindUser
{
    return $this->query()
        ->where('user_type', $user->getType())
        ->where('user_id', $user->getID())
        ->where('status', PromoterBindUserStatusEnum::BOUND)
        ->where('expires_at', '>', now())
        ->first();
}
```

### 3. 应用层改进

#### 绑定命令处理器
**改进前：**
```php
$existingBind = PromoterBindUser::where('promoter_id', $command->promoterId)
    ->where('user_type', $command->user->getType())
    ->where('user_id', $command->user->getID())
    ->where('status', PromoterBindUserStatusEnum::BOUND)
    ->first();
```

**改进后：**
```php
$existingBind = $this->service->readRepository->findUserActiveBind($command->user);
```

#### 解绑命令处理器
**改进前：**
```php
$bindUser = PromoterBindUser::where('promoter_id', $command->promoterId)
    ->where('user_type', $command->user->getType())
    ->where('user_id', $command->user->getID())
    ->where('status', PromoterBindUserStatusEnum::BOUND)
    ->first();
```

**改进后：**
```php
$bindUser = $this->service->readRepository->findActiveBind($command->promoterId, $command->user);
```

## 改进优势

### 1. 代码复用性
- 查询逻辑封装在仓库层，多个地方可以复用
- 避免重复的查询条件拼装

### 2. 可维护性
- 查询逻辑集中管理，修改时只需要在一个地方更新
- 应用层代码更简洁，专注于业务逻辑

### 3. 可测试性
- 仓库方法可以独立测试
- 应用层测试时可以更容易mock仓库方法

### 4. 语义化
- 方法名明确表达查询意图
- `findUserActiveBind` 比复杂的where条件更容易理解

### 5. 类型安全
- 仓库方法有明确的参数类型和返回类型
- IDE可以提供更好的代码提示和检查

## 查询方法说明

| 方法名 | 用途 | 查询条件 |
|-------|------|----------|
| `findBindRelation` | 查找任意状态的绑定关系 | promoter_id + user_type + user_id |
| `findActiveBind` | 查找特定分销员的有效绑定关系 | promoter_id + user_type + user_id + status=BOUND + expires_at>now |
| `findUserActiveBind` | 查找用户当前的有效绑定关系 | user_type + user_id + status=BOUND + expires_at>now |

## 业务逻辑优化

通过使用 `findUserActiveBind` 方法，绑定逻辑得到了优化：

1. **检查重复绑定**：先检查用户是否已有有效绑定
2. **区分错误类型**：
   - 如果绑定的是同一个分销员：提示"用户已经绑定该分销员"
   - 如果绑定的是不同分销员：提示"用户已经绑定了其他分销员"

这样的错误提示更加精确，用户体验更好。 