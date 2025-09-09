<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services;

use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Message\Domain\Repositories\MessageTemplateRepositoryInterface;
use RedJasmine\Message\Domain\Transformers\MessageTemplateTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 消息模板应用服务
 */
class MessageTemplateApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'message.template.application';
    protected static string $modelClass = MessageTemplate::class;

    public function __construct(
        public MessageTemplateRepositoryInterface $repository,
        public MessageTemplateTransformer $transformer
    ) {
    }

    protected static $macros = [
        'create' => \RedJasmine\Message\Application\Services\Commands\MessageTemplateCreateCommandHandler::class,
        'update' => \RedJasmine\Message\Application\Services\Commands\MessageTemplateUpdateCommandHandler::class,
        'delete' => \RedJasmine\Message\Application\Services\Commands\MessageTemplateDeleteCommandHandler::class,
        'batchEnable' => \RedJasmine\Message\Application\Services\Commands\MessageTemplateBatchEnableCommandHandler::class,
        'batchDisable' => \RedJasmine\Message\Application\Services\Commands\MessageTemplateBatchDisableCommandHandler::class,
        'duplicate' => \RedJasmine\Message\Application\Services\Commands\MessageTemplateDuplicateCommandHandler::class,

        'find' => \RedJasmine\Message\Application\Services\Queries\MessageTemplateFindQueryHandler::class,
        'paginate' => \RedJasmine\Message\Application\Services\Queries\MessageTemplatePaginateQueryHandler::class,
        'list' => \RedJasmine\Message\Application\Services\Queries\MessageTemplateListQueryHandler::class,
        'popular' => \RedJasmine\Message\Application\Services\Queries\MessageTemplatePopularQueryHandler::class,
    ];

    /**
     * 根据编码查找模板
     */
    public function findByCode(string $code): ?MessageTemplate
    {
        return $this->repository->findByCode($code);
    }

    /**
     * 获取启用的模板列表
     */
    public function getEnabledList(): array
    {
        return $this->repository->getEnabledList()->toArray();
    }

    /**
     * 根据业务线获取模板
     */
    public function getByBiz(string $biz): array
    {
        return $this->repository->getByBiz($biz)->toArray();
    }

    /**
     * 根据分类获取模板
     */
    public function getByCategory(int $categoryId): array
    {
        return $this->repository->getByCategory($categoryId)->toArray();
    }

    /**
     * 根据类型获取模板
     */
    public function getByType(string $type): array
    {
        return $this->repository->getByType($type)->toArray();
    }

    /**
     * 获取热门模板
     */
    public function getPopular(int $limit = 10): array
    {
        return $this->repository->getPopular($limit)->toArray();
    }

    /**
     * 搜索模板
     */
    public function searchTemplates(string $keyword): array
    {
        return $this->repository->search($keyword)->toArray();
    }

    /**
     * 获取使用统计
     */
    public function getUsageStatistics(): array
    {
        return $this->repository->getUsageStatistics()->toArray();
    }

    /**
     * 获取模板变量统计
     */
    public function getVariableStatistics(): array
    {
        return $this->repository->getVariableStatistics();
    }

    /**
     * 检查模板名称是否存在
     */
    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        return $this->repository->existsByName($name, $excludeId);
    }

    /**
     * 复制模板
     */
    public function duplicateTemplate(int $templateId): ?MessageTemplate
    {
        return $this->repository->duplicate($templateId);
    }

    /**
     * 更新使用次数
     */
    public function incrementUsageCount(int $templateId): bool
    {
        return $this->repository->incrementUsageCount($templateId);
    }
}
