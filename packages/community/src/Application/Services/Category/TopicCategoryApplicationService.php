<?php

namespace RedJasmine\Community\Application\Services\Category;

use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Community\Domain\Repositories\TopicCategoryRepositoryInterface;
use RedJasmine\Community\Domain\Transformer\TopicCategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;

/**
 * 话题分类应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 */
class TopicCategoryApplicationService extends ApplicationService
{
    public function __construct(
        public TopicCategoryRepositoryInterface $repository,
        public TopicCategoryTransformer $transformer
    ) {
    }

    protected static string $modelClass = TopicCategory::class;

    /**
     * 获取树形结构
     */
    public function tree(Query $query) : array
    {
        return $this->repository->tree($query);
    }
}
