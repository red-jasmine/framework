<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Message\Domain\Data\MessagePushLogData;
use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 消息推送日志转换器
 */
class MessagePushLogTransformer implements TransformerInterface
{
    /**
     * 将数据转换为模型
     */
    public function transform($data, $model): Model
    {
        if (!$data instanceof MessagePushLogData) {
            throw new \InvalidArgumentException('数据必须是 MessagePushLogData 类型');
        }

        if (!$model instanceof MessagePushLog) {
            throw new \InvalidArgumentException('模型必须是 MessagePushLog 类型');
        }

        // 基础字段映射
        $model->message_id = $data->messageId;
        $model->channel = $data->channel;
        $model->status = $data->status;
        $model->pushed_at = $data->pushedAt;
        $model->error_message = $data->errorMessage;
        $model->retry_count = $data->retryCount;
        $model->response_data = $data->responseData;
        $model->external_id = $data->externalId;
        $model->response_time = $data->responseTime;

        return $model;
    }

    /**
     * 验证数据
     */
    public function validateData($data): void
    {
        if (!$data instanceof MessagePushLogData) {
            throw new \InvalidArgumentException('数据必须是 MessagePushLogData 类型');
        }

        // 验证必需字段
        if (empty($data->messageId)) {
            throw new \InvalidArgumentException('消息ID不能为空');
        }

        if (!$data->channel) {
            throw new \InvalidArgumentException('推送渠道不能为空');
        }

        // 验证重试次数
        if ($data->retryCount < 0) {
            throw new \InvalidArgumentException('重试次数不能为负数');
        }

        // 验证响应时间
        if ($data->responseTime < 0) {
            throw new \InvalidArgumentException('响应时间不能为负数');
        }

        // 验证状态和错误信息的一致性
        if ($data->isFailed() && empty($data->errorMessage)) {
            throw new \InvalidArgumentException('推送失败时必须提供错误信息');
        }

        // 验证推送时间
        if ($data->pushedAt && $data->pushedAt > new \DateTimeImmutable()) {
            throw new \InvalidArgumentException('推送时间不能晚于当前时间');
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
     * 验证推送结果
     */
    public function validatePushResult($data): void
    {
        $this->validateData($data);

        // 额外的推送结果验证
        if ($data->isSuccess()) {
            // 成功状态的额外验证
            if ($data->errorMessage) {
                throw new \InvalidArgumentException('推送成功时不应该有错误信息');
            }

            if ($data->pushedAt === null) {
                throw new \InvalidArgumentException('推送成功时必须有推送时间');
            }
        }

        if ($data->isFailed()) {
            // 失败状态的额外验证
            if (empty($data->errorMessage)) {
                throw new \InvalidArgumentException('推送失败时必须提供错误信息');
            }
        }

        // 验证外部ID格式
        if ($data->externalId && !$this->isValidExternalId($data->externalId)) {
            throw new \InvalidArgumentException('外部推送ID格式不正确');
        }
    }

    /**
     * 映射推送结果
     */
    public function mapPushResult($data, $model): void
    {
        if (!$data instanceof MessagePushLogData) {
            throw new \InvalidArgumentException('数据必须是 MessagePushLogData 类型');
        }

        if (!$model instanceof MessagePushLog) {
            throw new \InvalidArgumentException('模型必须是 MessagePushLog 类型');
        }

        // 只映射推送结果相关的字段
        $model->status = $data->status;
        $model->pushed_at = $data->pushedAt;
        $model->error_message = $data->errorMessage;
        $model->response_data = $data->responseData;
        $model->external_id = $data->externalId;
        $model->response_time = $data->responseTime;
    }

    /**
     * 验证外部ID格式
     */
    private function isValidExternalId(string $externalId): bool
    {
        // 外部ID应该是非空字符串，长度在合理范围内
        if (empty($externalId) || mb_strlen($externalId) > 255) {
            return false;
        }

        // 可以根据不同推送服务的ID格式进行更具体的验证
        // 这里使用通用的格式验证
        return preg_match('/^[a-zA-Z0-9_-]+$/', $externalId);
    }

    /**
     * 验证响应数据格式
     */
    private function validateResponseData(?array $responseData): void
    {
        if ($responseData === null) {
            return;
        }

        // 验证响应数据的基本结构
        $maxSize = 1024 * 1024; // 1MB
        $jsonSize = strlen(json_encode($responseData));
        
        if ($jsonSize > $maxSize) {
            throw new \InvalidArgumentException('响应数据过大');
        }

        // 验证必要的字段
        if (isset($responseData['error_code']) && !is_string($responseData['error_code'])) {
            throw new \InvalidArgumentException('错误码必须是字符串类型');
        }

        if (isset($responseData['error_message']) && !is_string($responseData['error_message'])) {
            throw new \InvalidArgumentException('错误信息必须是字符串类型');
        }
    }
}
