<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Transformers;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use RedJasmine\Message\Domain\Data\MessageData;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 消息转换器
 */
class MessageTransformer implements TransformerInterface
{
    /**
     * 将数据转换为模型
     */
    public function transform($data, $model) : Model
    {
        if (!$data instanceof MessageData) {
            throw new InvalidArgumentException('数据必须是 MessageData 类型');
        }

        if (!$model instanceof Message) {
            throw new InvalidArgumentException('模型必须是 Message 类型');
        }

        // 基础字段映射
        $model->biz                = $data->biz;
        $model->category_id        = $data->categoryId;
        $model->owner              = $data->owner;
        $model->sender             = $data->sender;
        $model->source             = $data->source;
        $model->template_id        = $data->templateId;
        $model->type               = $data->type;
        $model->priority           = $data->priority;
        $model->is_urgent          = $data->isUrgent;
        $model->is_burn_after_read = $data->isBurnAfterRead;
        $model->expires_at         = $data->expiresAt;
        $model->setMesageContent($data->content);


        // 处理推送渠道
        if ($data->channels !== null) {
            $channels = [];
            foreach ($data->getPushChannels() as $channel) {
                $channels[] = $channel->value;
            }
            $model->channels = $channels;
        }

        return $model;
    }

    /**
     * 验证数据
     */
    public function validateData($data) : void
    {
        if (!$data instanceof MessageData) {
            throw new InvalidArgumentException('数据必须是 MessageData 类型');
        }

        // 验证必需字段
        if (empty($data->title)) {
            throw new InvalidArgumentException('消息标题不能为空');
        }

        if (empty($data->content)) {
            throw new InvalidArgumentException('消息内容不能为空');
        }

        if (empty($data->receiverId)) {
            throw new InvalidArgumentException('接收人不能为空');
        }

        // 验证标题长度
        if (mb_strlen($data->title) > 255) {
            throw new InvalidArgumentException('消息标题长度不能超过255个字符');
        }

        // 验证过期时间
        if ($data->expiresAt && $data->expiresAt < new DateTimeImmutable()) {
            throw new InvalidArgumentException('过期时间不能早于当前时间');
        }
    }

    /**
     * 映射属性
     */
    public function mapProperties($data, $model) : void
    {
        $this->transform($data, $model);
    }
}
