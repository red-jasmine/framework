<?php

namespace RedJasmine\Support\Foundation\Context;

use ArrayAccess;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * 上下文基类
 *
 * 提供通用的上下文数据存储和管理功能
 * 支持键值对存储、链式调用、闭包延迟求值等特性
 *
 *
 */
class Context implements ArrayAccess, IteratorAggregate, JsonSerializable
{
    /**
     * 上下文数据存储
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * 设置上下文数据
     *
     * @param string $key 键名
     * @param mixed $value 值，可以是闭包用于延迟求值
     * @return static
     */
    public function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * 获取上下文数据
     *
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (!isset($this->data[$key])) {
            return $default;
        }

        $item = $this->data[$key];

        // 支持闭包延迟求值
        if (is_callable($item)) {
            return $item();
        }

        return $item;
    }

    /**
     * 获取并删除上下文数据（Laravel 风格）
     *
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->forget($key);
        return $value;
    }

    /**
     * 检查上下文数据是否存在
     *
     * @param string $key 键名
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * 删除上下文数据
     *
     * @param string|array $keys 键名或键名数组
     * @return static
     */
    public function forget(string|array $keys): static
    {
        $keys = is_array($keys) ? $keys : [$keys];

        foreach ($keys as $key) {
            unset($this->data[$key]);
        }

        return $this;
    }

    /**
     * 获取所有上下文数据
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * 只获取指定的键（Laravel 风格）
     *
     * @param string|array $keys 键名或键名数组
     * @return array<string, mixed>
     */
    public function only(string|array $keys): array
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $results = [];

        foreach ($keys as $key) {
            if (isset($this->data[$key])) {
                $results[$key] = $this->get($key);
            }
        }

        return $results;
    }

    /**
     * 排除指定的键（Laravel 风格）
     *
     * @param string|array $keys 键名或键名数组
     * @return array<string, mixed>
     */
    public function except(string|array $keys): array
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $results = $this->data;

        foreach ($keys as $key) {
            unset($results[$key]);
        }

        return $results;
    }

    /**
     * 清空所有上下文数据
     *
     * @return static
     */
    public function flush(): static
    {
        $this->data = [];
        return $this;
    }

    /**
     * 批量设置上下文数据
     *
     * @param array<string, mixed> $data 数据数组
     * @return static
     */
    public function merge(array $data): static
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * 批量替换上下文数据
     *
     * @param array<string, mixed> $data 数据数组
     * @return static
     */
    public function replace(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 条件设置（Laravel 风格）
     *
     * @param mixed $condition 条件
     * @param string $key 键名
     * @param mixed $value 值
     * @return static
     */
    public function when(mixed $condition, string $key, mixed $value): static
    {
        if ($condition) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * 管道操作（Laravel 风格）
     *
     * @param callable $callback 回调函数
     * @return static
     */
    public function tap(callable $callback): static
    {
        $callback($this);
        return $this;
    }

    /**
     * 检查上下文是否为空
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * 获取上下文数据数量
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * 支持属性式访问
     *
     * @param string $key 键名
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * 支持属性式设置
     *
     * @param string $key 键名
     * @param mixed $value 值
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    /**
     * 支持 isset() 检查
     *
     * @param string $key 键名
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * 支持 unset() 删除
     *
     * @param string $key 键名
     * @return void
     */
    public function __unset(string $key): void
    {
        $this->forget($key);
    }

    // ArrayAccess 接口实现

    /**
     * 数组式访问：检查键是否存在
     *
     * @param mixed $offset 键名
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    /**
     * 数组式访问：获取值
     *
     * @param mixed $offset 键名
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * 数组式访问：设置值
     *
     * @param mixed $offset 键名
     * @param mixed $value 值
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * 数组式访问：删除值
     *
     * @param mixed $offset 键名
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->forget($offset);
    }

    // IteratorAggregate 接口实现

    /**
     * 支持迭代遍历
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->data);
    }

    // JsonSerializable 接口实现

    /**
     * JSON 序列化
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
