<?php

namespace RedJasmine\Community\Application\Services\Tag;

use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Community\Domain\Repositories\TopicTagRepositoryInterface;
use RedJasmine\Community\Domain\Transformer\TopicTagTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 话题标签应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 */
class TopicTagApplicationService extends ApplicationService
{
    public function __construct(
        public TopicTagRepositoryInterface $repository,
        public TopicTagTransformer $transformer,
    ) {
    }

    protected static string $modelClass = TopicTag::class;
}
