<?php

namespace RedJasmine\JwtAuth\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use RedJasmine\Support\Contracts\UserInterface;

class JwtUser implements Authenticatable, UserInterface
{
    protected array $attributes = [];
    protected string $userType;
    protected ?string $rememberToken = null;

    public function __construct(array $attributes = [], ?string $userType = null)
    {
        $this->attributes = $attributes;
        $this->userType = $userType ?? 'user';
    }

    // Authenticatable 接口实现
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->attributes['id'] ?? null;
    }

    public function getAuthPassword(): string
    {
        return $this->attributes['password'] ?? '';
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken($value): void
    {
        $this->rememberToken = $value;
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    // UserInterface 接口实现
    public function getType(): string
    {
        return $this->userType;
    }

    public function getID(): string
    {
        return (string) ($this->attributes['id'] ?? '');
    }

    public function getNickname(): ?string
    {
        return $this->attributes['nickname'] ?? 
               $this->attributes['name'] ?? 
               $this->attributes['username'] ?? 
               null;
    }

    public function getAvatar(): ?string
    {
        return $this->attributes['avatar'] ?? 
               $this->attributes['avatar_url'] ?? 
               $this->attributes['profile_image'] ?? 
               null;
    }

    public function getUserData(): array
    {
        return $this->attributes;
    }

    // 通用属性访问方法
    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->attributes[$name]);
    }

    // 设置属性
    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    // 设置用户类型
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    // 获取所有属性
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    // 设置所有属性
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    // 合并属性
    public function mergeAttributes(array $attributes): void
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    // 转换为数组
    public function toArray(): array
    {
        return [
            'id' => $this->getID(),
            'type' => $this->getType(),
            'nickname' => $this->getNickname(),
            'avatar' => $this->getAvatar(),
            'user_data' => $this->getUserData(),
            'attributes' => $this->attributes,
        ];
    }

    // 转换为JSON
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    // 字符串表示
    public function __toString(): string
    {
        return $this->getNickname() ?? $this->getID() ?? 'JwtUser';
    }

    // 序列化支持
    public function __serialize(): array
    {
        return [
            'attributes' => $this->attributes,
            'userType' => $this->userType,
            'rememberToken' => $this->rememberToken,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->attributes = $data['attributes'] ?? [];
        $this->userType = $data['userType'] ?? 'user';
        $this->rememberToken = $data['rememberToken'] ?? null;
    }
}
