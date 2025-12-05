<?php

namespace RedJasmine\Support\Domain\Models\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Foundation\Data\Data;
use Throwable;

class ValueObject extends Data implements CastsAttributes
{
    /**
     * 从数据库获取值时的转换
     * 将 JSON 字符串转换为 ValueObject 实例
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?static
    {
        if (is_null($value)) {
            return null;
        }

        // 如果已经是当前类的实例，直接返回
        if ($value instanceof static) {
            return $value;
        }

        try {
            // 如果是 JSON 字符串，先解码
            if (is_string($value)) {
                $data = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } elseif (is_array($value)) {
                $data = $value;
            } else {
                // 其他类型尝试转换为数组
                $data = (array) $value;
            }

            // 使用 Laravel Data 的 from 方法创建实例
            return static::from($data);
        } catch (Throwable $e) {
            // 转换失败时返回 null 或抛出异常，根据业务需求决定
            return null;
        }
    }

    /**
     * 存储到数据库时的转换
     * 将 ValueObject 实例转换为 JSON 字符串
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        try {
            // 如果是当前类的实例，转换为数组然后 JSON 编码
            if ($value instanceof static) {
                return json_encode($value->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            }

            // 如果是数组，直接 JSON 编码
            if (is_array($value)) {
                return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            }

            // 尝试创建实例然后转换
            $instance = static::from($value);
            return json_encode($instance->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            // 转换失败时返回 null 或抛出异常，根据业务需求决定
            return null;
        }
    }
}
