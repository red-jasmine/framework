<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Services;

use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Message\Domain\Models\Enums\PushStatusEnum;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Message\Domain\Models\ValueObjects\ErrorInfo;
use RedJasmine\Message\Domain\Models\ValueObjects\PushResult;
use RedJasmine\Message\Domain\Repositories\MessagePushLogRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;

/**
 * 消息推送领域服务
 */
class MessagePushService
{
    public function __construct(
        protected MessageRepositoryInterface $messageRepository,
        protected MessagePushLogRepositoryInterface $pushLogRepository,
        protected MessageRuleService $ruleService,
    ) {
    }

    /**
     * 推送消息到指定渠道
     */
    public function push(Message $message): array
    {
        $pushResults = [];
        $pushConfig = $message->getPushConfig();

        if (!$pushConfig->isPushEnabled()) {
            return $pushResults;
        }

        foreach ($pushConfig->channels as $channel) {
            try {
                $result = $this->pushToChannel($message, $channel);
                $pushResults[$channel->value] = $result;
            } catch (\Exception $e) {
                $errorResult = PushResult::failed(
                    errorMessage: $e->getMessage(),
                    errorCode: 'PUSH_ERROR'
                );
                
                $this->logPushResult($message, $channel, $errorResult);
                $pushResults[$channel->value] = $errorResult;
            }
        }

        // 更新消息的整体推送状态
        $this->updateMessagePushStatus($message, $pushResults);

        return $pushResults;
    }

    /**
     * 推送到指定渠道
     */
    public function pushToChannel(Message $message, PushChannelEnum $channel): PushResult
    {
        // 验证推送规则
        $this->ruleService->validateChannelRules($message, $channel);

        // 检查频率限制
        $this->ruleService->checkFrequencyLimit($message, $channel);

        // 创建推送日志
        $pushLog = $this->createPushLog($message, $channel);

        try {
            // 执行具体的推送逻辑
            $result = $this->executePush($message, $channel);
            
            // 记录推送结果
            $this->logPushResult($message, $channel, $result, $pushLog);

            return $result;
        } catch (\Exception $e) {
            $errorResult = PushResult::failed(
                errorMessage: $e->getMessage(),
                errorCode: $this->getErrorCode($e)
            );

            $this->logPushResult($message, $channel, $errorResult, $pushLog);

            throw $e;
        }
    }

    /**
     * 重试推送
     */
    public function retryPush(MessagePushLog $pushLog): PushResult
    {
        if (!$pushLog->canRetry()) {
            throw new \InvalidArgumentException('该推送日志不能重试');
        }

        $message = $pushLog->message;
        $channel = $pushLog->channel;

        // 增加重试次数
        $pushLog->incrementRetryCount();
        $this->pushLogRepository->update($pushLog);

        // 计算重试延迟
        $retryDelay = $message->getPushConfig()->calculateRetryDelay($pushLog->retry_count);
        
        // 如果需要延迟，可以将任务放入延迟队列
        if ($retryDelay > 0) {
            // dispatch(new RetryPushJob($pushLog))->delay(now()->addSeconds($retryDelay));
            return PushResult::pending(['retry_delay' => $retryDelay]);
        }

        // 立即重试
        return $this->pushToChannel($message, $channel);
    }

    /**
     * 记录推送结果
     */
    public function logPushResult(
        Message $message,
        PushChannelEnum $channel,
        PushResult $result,
        ?MessagePushLog $pushLog = null
    ): MessagePushLog {
        if (!$pushLog) {
            $pushLog = $this->createPushLog($message, $channel);
        }

        // 更新推送日志
        if ($result->isSuccess()) {
            $pushLog->recordSuccess(
                responseTime: $result->responseTime,
                responseData: $result->responseData,
                externalId: $result->externalId
            );
        } else {
            $pushLog->recordFailure(
                errorMessage: $result->getErrorMessage() ?? '推送失败',
                errorCode: $result->getErrorCode(),
                responseTime: $result->responseTime,
                additionalData: $result->responseData
            );
        }

        $this->pushLogRepository->update($pushLog);

        return $pushLog;
    }

    /**
     * 执行推送
     */
    protected function executePush(Message $message, PushChannelEnum $channel): PushResult
    {
        $startTime = microtime(true);

        try {
            // 根据渠道类型执行不同的推送逻辑
            $result = match ($channel) {
                PushChannelEnum::IN_APP => $this->pushToInApp($message),
                PushChannelEnum::PUSH => $this->pushToNotification($message),
                PushChannelEnum::EMAIL => $this->pushToEmail($message),
                PushChannelEnum::SMS => $this->pushToSms($message),
            };

            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            return PushResult::success(
                responseTime: $responseTime,
                responseData: $result,
                externalId: $result['external_id'] ?? null
            );
        } catch (\Exception $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            return PushResult::failed(
                errorMessage: $e->getMessage(),
                errorCode: $this->getErrorCode($e),
                responseTime: $responseTime
            );
        }
    }

    /**
     * 推送到APP内消息
     */
    protected function pushToInApp(Message $message): array
    {
        // APP内消息通常不需要外部推送，直接标记为成功
        return [
            'channel' => 'in_app',
            'message' => '消息已保存到用户消息列表',
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * 推送到通知
     */
    protected function pushToNotification(Message $message): array
    {
        // 这里应该调用具体的推送服务
        // 例如：JPush、个推、Firebase等
        
        throw new \RuntimeException('推送通知服务未实现');
    }

    /**
     * 推送到邮件
     */
    protected function pushToEmail(Message $message): array
    {
        // 这里应该调用邮件服务
        // 例如：Laravel Mail、SendGrid等
        
        throw new \RuntimeException('邮件推送服务未实现');
    }

    /**
     * 推送到短信
     */
    protected function pushToSms(Message $message): array
    {
        // 这里应该调用短信服务
        // 例如：阿里云短信、腾讯云短信等
        
        throw new \RuntimeException('短信推送服务未实现');
    }

    /**
     * 创建推送日志
     */
    protected function createPushLog(Message $message, PushChannelEnum $channel): MessagePushLog
    {
        $pushLog = new MessagePushLog();
        $pushLog->message_id = $message->id;
        $pushLog->channel = $channel;
        $pushLog->status = PushStatusEnum::PENDING;
        $pushLog->retry_count = 0;

        $this->pushLogRepository->store($pushLog);

        return $pushLog;
    }

    /**
     * 更新消息推送状态
     */
    protected function updateMessagePushStatus(Message $message, array $pushResults): void
    {
        $hasSuccess = false;
        $hasFailure = false;
        $allPending = true;

        foreach ($pushResults as $result) {
            if ($result->isSuccess()) {
                $hasSuccess = true;
                $allPending = false;
            } elseif ($result->isFailed()) {
                $hasFailure = true;
                $allPending = false;
            }
        }

        // 确定整体推送状态
        $overallStatus = match (true) {
            $allPending => PushStatusEnum::PENDING,
            $hasSuccess && !$hasFailure => PushStatusEnum::SENT,
            $hasSuccess && $hasFailure => PushStatusEnum::SENT, // 部分成功也算成功
            default => PushStatusEnum::FAILED,
        };

        $message->updatePushStatus($overallStatus);
    }

    /**
     * 获取错误码
     */
    protected function getErrorCode(\Exception $e): string
    {
        // 根据异常类型返回相应的错误码
        return match (get_class($e)) {
            \InvalidArgumentException::class => 'INVALID_ARGUMENT',
            \RuntimeException::class => 'RUNTIME_ERROR',
            \Exception::class => 'GENERAL_ERROR',
            default => 'UNKNOWN_ERROR',
        };
    }
}
