<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Application\Services\Category;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\Domain\Repositories\MessageCategoryRepositoryInterface;
use RedJasmine\Message\Domain\Transformers\MessageCategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;

/**
 * 消息分类应用服务
 */
class MessageCategoryApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'message.category.application';

    protected static string $modelClass = MessageCategory::class;

    public function __construct(
        public MessageCategoryRepositoryInterface $repository,
        public MessageCategoryTransformer $transformer
    ) {
    }

    public function tree(Query $query) : array
    {

        return $this->readRepository->tree($query);
    }

}
