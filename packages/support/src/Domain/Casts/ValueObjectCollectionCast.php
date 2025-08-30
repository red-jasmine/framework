<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;
use Throwable;

/**
 * ValueObject 集合类型转换器
 * 用于将 JSON 数组转换为 ValueObject 实例列表
 */
class ValueObjectCollectionCast implements CastsAttributes
{
    /**
     * ValueObject 类名
     */
    protected string $valueObjectClass;

    /**
     * 构造函数
     * 
     * @param string $valueObjectClass ValueObject 子类的完全限定类名
     */
    public function __construct(string $valueObjectClass)
    {
        if (!is_subclass_of($valueObjectClass, ValueObject::class)) {
            throw new \InvalidArgumentException("类 {$valueObjectClass} 必须继承自 ValueObject");
        }
        
        $this->valueObjectClass = $valueObjectClass;
    }

    /**
     * 从数据库获取值时的转换
     * 将 JSON 数组字符串转换为 ValueObject 实例列表
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (is_null($value)) {
            return null;
        }

        // 如果已经是 ValueObject 实例数组，直接返回
        $className = $this->valueObjectClass;
        if (is_array($value) && !empty($value) && is_a($value[0], $className)) {
            return $value;
        }

        try {
            // 如果是 JSON 字符串，先解码
            if (is_string($value)) {
                $data = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } elseif (is_array($value)) {
                $data = $value;
            } else {
                return null;
            }

            // 检查是否为数组格式
            if (!$this->isJsonArray($data)) {
                return null;
            }

            // 转换为 ValueObject 实例列表
            $className = $this->valueObjectClass;
            return array_map(function ($item) use ($className) {
                return call_user_func([$className, 'from'], $item);
            }, $data);

        } catch (Throwable $e) {
            // 转换失败时返回 null
            return null;
        }
    }

    /**
     * 存储到数据库时的转换
     * 将 ValueObject 实例列表转换为 JSON 数组字符串
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        try {
            // 如果是空数组，返回空 JSON 数组
            if (is_array($value) && empty($value)) {
                return '[]';
            }

            // 如果是 ValueObject 实例数组
            if (is_array($value)) {
                $className = $this->valueObjectClass;
                $arrayData = array_map(function ($item) use ($className) {
                    if (is_a($item, $className)) {
                        return $item->toArray();
                    } elseif (is_array($item)) {
                        // 如果是数组，尝试转换为 ValueObject 再转为数组
                        $instance = call_user_func([$className, 'from'], $item);
                        return $instance->toArray();
                    }
                    return $item;
                }, $value);

                return json_encode($arrayData, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            }

            return null;
        } catch (Throwable $e) {
            // 转换失败时返回 null
            return null;
        }
    }

    /**
     * 判断是否为 JSON 数组格式（索引数组）
     * 
     * @param mixed $data
     * @return bool
     */
    protected function isJsonArray(mixed $data): bool
    {
        if (!is_array($data)) {
            return false;
        }

        // 空数组也算作数组格式
        if (empty($data)) {
            return true;
        }

        // 检查是否为索引数组（数字键从0开始连续）
        return array_keys($data) === range(0, count($data) - 1);
    }

    /**
     * 创建类型转换器实例的静态方法
     * 
     * @param string $valueObjectClass
     * @return static
     */
    public static function of(string $valueObjectClass): static
    {
        return new static($valueObjectClass);
    }
}
