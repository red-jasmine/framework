<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\Domain\Repositories\MessageCategoryReadRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageCategoryRepositoryInterface;
use RedJasmine\Message\Domain\Transformers\MessageCategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 消息分类应用服务
 */
class MessageCategoryApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'message.category.application';
    protected static string $modelClass = MessageCategory::class;

    public function __construct(
        public MessageCategoryRepositoryInterface $repository,
        public MessageCategoryReadRepositoryInterface $readRepository,
        public MessageCategoryTransformer $transformer
    ) {
    }

    protected static array $macros = [
        'create' => \RedJasmine\Message\Application\Services\Commands\MessageCategoryCreateCommandHandler::class,
        'update' => \RedJasmine\Message\Application\Services\Commands\MessageCategoryUpdateCommandHandler::class,
        'delete' => \RedJasmine\Message\Application\Services\Commands\MessageCategoryDeleteCommandHandler::class,
        'batchEnable' => \RedJasmine\Message\Application\Services\Commands\MessageCategoryBatchEnableCommandHandler::class,
        'batchDisable' => \RedJasmine\Message\Application\Services\Commands\MessageCategoryBatchDisableCommandHandler::class,
        'updateSort' => \RedJasmine\Message\Application\Services\Commands\MessageCategoryUpdateSortCommandHandler::class,
        
        'find' => \RedJasmine\Message\Application\Services\Queries\MessageCategoryFindQueryHandler::class,
        'paginate' => \RedJasmine\Message\Application\Services\Queries\MessageCategoryPaginateQueryHandler::class,
        'tree' => \RedJasmine\Message\Application\Services\Queries\MessageCategoryTreeQueryHandler::class,
        'list' => \RedJasmine\Message\Application\Services\Queries\MessageCategoryListQueryHandler::class,
    ];

    /**
     * 获取分类树
     */
    public function getTree(?int $parentId = null): array
    {
        return $this->readRepository->getTree($parentId)->toArray();
    }

    /**
     * 获取启用的分类列表
     */
    public function getEnabledList(): array
    {
        return $this->readRepository->getEnabledList()->toArray();
    }

    /**
     * 根据业务线获取分类
     */
    public function getByBiz(string $biz): array
    {
        return $this->readRepository->getByBiz($biz)->toArray();
    }

    /**
     * 获取分类路径
     */
    public function getCategoryPath(int $categoryId): array
    {
        return $this->readRepository->getCategoryPath($categoryId);
    }

    /**
     * 搜索分类
     */
    public function searchCategories(string $keyword): array
    {
        return $this->readRepository->search($keyword)->toArray();
    }

    /**
     * 获取使用统计
     */
    public function getUsageStatistics(): array
    {
        return $this->readRepository->getUsageStatistics()->toArray();
    }

    /**
     * 检查分类名称是否存在
     */
    public function existsByName(string $name, string $ownerId, string $biz, ?int $excludeId = null): bool
    {
        return $this->repository->existsByName($name, $ownerId, $biz, $excludeId);
    }

    /**
     * 获取最大排序值
     */
    public function getMaxSort(string $ownerId, string $biz): int
    {
        return $this->repository->getMaxSort($ownerId, $biz);
    }

    /**
     * 批量更新排序
     */
    public function batchUpdateSort(array $sortData): int
    {
        return $this->repository->batchUpdateSort($sortData);
    }
}
