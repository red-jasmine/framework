<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Message\Domain\Data\MessageCategoryData;
use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 消息分类转换器
 */
class MessageCategoryTransformer implements TransformerInterface
{
    /**
     * 将数据转换为模型
     */
    public function transform($data, $model): Model
    {
        if (!$data instanceof MessageCategoryData) {
            throw new \InvalidArgumentException('数据必须是 MessageCategoryData 类型');
        }

        if (!$model instanceof MessageCategory) {
            throw new \InvalidArgumentException('模型必须是 MessageCategory 类型');
        }

        // 基础字段映射
        $model->biz = $data->biz;
        $model->name = $data->name;
        $model->description = $data->description;
        $model->icon = $data->icon;
        $model->color = $data->color;
        $model->sort = $data->sort;
        $model->status = $data->status;

        // 设置所属者
        $model->owner_id = (string) $data->owner->getKey();

        return $model;
    }

    /**
     * 验证数据
     */
    public function validateData($data): void
    {
        if (!$data instanceof MessageCategoryData) {
            throw new \InvalidArgumentException('数据必须是 MessageCategoryData 类型');
        }

        // 验证必需字段
        if (empty($data->name)) {
            throw new \InvalidArgumentException('分类名称不能为空');
        }

        // 验证名称长度
        if (mb_strlen($data->name) > 100) {
            throw new \InvalidArgumentException('分类名称长度不能超过100个字符');
        }

        // 验证排序值
        if ($data->sort < 0) {
            throw new \InvalidArgumentException('排序值不能为负数');
        }

        // 验证颜色格式
        if ($data->color && !$this->isValidColor($data->color)) {
            throw new \InvalidArgumentException('颜色格式不正确');
        }
    }

    /**
     * 映射属性
     */
    public function mapProperties($data, $model): void
    {
        $this->transform($data, $model);
    }

    /**
     * 验证配置
     */
    public function validateConfig($data): void
    {
        $this->validateData($data);

        // 可以添加额外的配置验证逻辑
        if ($data->icon && !$this->isValidIcon($data->icon)) {
            throw new \InvalidArgumentException('图标格式不正确');
        }
    }

    /**
     * 映射配置
     */
    public function mapConfiguration($data, $model): void
    {
        $this->transform($data, $model);
    }

    /**
     * 验证颜色格式
     */
    private function isValidColor(string $color): bool
    {
        // 支持十六进制颜色码和预定义颜色名称
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            return true;
        }

        $validColors = [
            'primary', 'secondary', 'success', 'danger', 'warning', 'info',
            'light', 'dark', 'red', 'green', 'blue', 'yellow', 'purple',
            'orange', 'pink', 'gray', 'black', 'white'
        ];

        return in_array($color, $validColors, true);
    }

    /**
     * 验证图标格式
     */
    private function isValidIcon(string $icon): bool
    {
        // 支持 Heroicon、FontAwesome 等图标格式
        $iconPatterns = [
            '/^heroicon-[os]-[\w-]+$/',           // Heroicon
            '/^fa[slrb]? fa-[\w-]+$/',            // FontAwesome
            '/^icon-[\w-]+$/',                    // 自定义图标
            '/^[\w-]+$/',                         // 简单图标名称
        ];

        foreach ($iconPatterns as $pattern) {
            if (preg_match($pattern, $icon)) {
                return true;
            }
        }

        return false;
    }
}
