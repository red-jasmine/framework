<?php

namespace RedJasmine\Community\Application\Services\Topic;

use RedJasmine\Article\Application\Services\Article\Commands\TopicPublishCommandHandler;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Community\Domain\Repositories\TopicRepositoryInterface;
use RedJasmine\Community\Domain\Transformer\TopicTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\ApprovalCommandHandler;
use RedJasmine\Support\Application\Commands\SubmitApprovalCommandHandler;

/**
 * 话题应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 */
class TopicApplicationService extends ApplicationService
{
    public function __construct(
        public TopicRepositoryInterface $repository,
        public TopicTransformer $transformer
    ) {
    }

    protected static string $modelClass = Topic::class;

    protected static $macros = [
        'publish'        => TopicPublishCommandHandler::class,
        'approval'       => ApprovalCommandHandler::class,
        'submitApproval' => SubmitApprovalCommandHandler::class,
    ];
}
