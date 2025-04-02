<?php

namespace RedJasmine\Interaction\Domain\Services;

use Illuminate\Support\Facades\RateLimiter;
use RedJasmine\Interaction\Domain\Contracts\InteractionResourceInterface;
use RedJasmine\Interaction\Domain\Contracts\InteractionTypeInterface;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;
use RedJasmine\Interaction\Exceptions\InteractionLimiterException;

class InteractionDomainService
{


    public function __construct(
        protected InteractionResourceInterface $resource,
        protected InteractionTypeInterface $interactionType,
        public InteractionStatisticRepositoryInterface $repository,
    ) {

    }

    /**
     * @param  InteractionData  $data
     *
     * @return InteractionRecord
     * @throws InteractionLimiterException
     */
    public function interactive(InteractionData $data) : InteractionRecord
    {
        // 验证
        $this->validate($data);

        // 限制流程
        $this->limiter($data);


        return $this->interactionType->makeRecord($data);


    }


    // 初始化
    protected function validate(InteractionData $data) : void
    {
        // 资源校验
        $this->resource->validate($data);
        // 数据验证
        $this->interactionType->validate($data);
    }


    /**
     * @param  InteractionData  $data
     *
     * @return void
     * @throws InteractionLimiterException
     */
    protected function limiter(InteractionData $data) : void
    {
        $config = $this->resource->getInteractionTypeLimiterConfig($data);
        // 单次
        if ($data->quantity > $config->once()) {
            throw   new InteractionLimiterException('超过单次最大限制:', $config->once());
        }
        // 时间间隔
        if ($config->interval()) {
            $intervalKey = $this->getRateLimiterKey($data, 'interval');
            if (RateLimiter::tooManyAttempts($intervalKey, 1)) {
                throw   new InteractionLimiterException('间隔:'.$config->interval());
            }
            RateLimiter::hit($intervalKey, $config->interval());
        }

        // 总量验证
        if ($config->total()) {
            // TODO

        }
    }

    protected function getRateLimiterKey(InteractionData $data, string $type = 'quantity') : string
    {
        return "interaction-$type:$data->interactionType:$data->resourceType:$data->resourceId:{$data->user->getType()}:{$data->user->getId()}";
    }


}