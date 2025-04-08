<?php

namespace RedJasmine\Community\Application\Services\Tag;

use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Community\Domain\Repositories\TopicTagReadRepositoryInterface;
use RedJasmine\Community\Domain\Repositories\TopicTagRepositoryInterface;
use RedJasmine\Community\Domain\Transformer\TopicTagTransformer;
use RedJasmine\Support\Application\ApplicationService;

class TopicTagApplicationService extends ApplicationService
{
    public function __construct(
        public TopicTagRepositoryInterface $repository,
        public TopicTagReadRepositoryInterface $readRepository,
        public TopicTagTransformer $transformer,
    ) {
    }

    protected static string $modelClass = TopicTag::class;
}