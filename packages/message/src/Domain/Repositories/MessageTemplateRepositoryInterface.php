<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息模板仓库接口
 *
 * 提供消息模板实体的读写操作统一接口
 */
interface MessageTemplateRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据名称查找模板
     */
    public function findByName(string $name): ?MessageTemplate;

    /**
     * 查找启用的模板
     */
    public function findActive(): array;

    /**
     * 查找禁用的模板
     */
    public function findInactive(): array;

    /**
     * 批量启用模板
     */
    public function batchEnable(array $templateIds): int;

    /**
     * 批量禁用模板
     */
    public function batchDisable(array $templateIds): int;

    /**
     * 检查模板名称是否存在
     */
    public function existsByName(string $name, ?int $excludeId = null): bool;

    /**
     * 查找包含指定变量的模板
     */
    public function findByVariable(string $variableName): array;

    /**
     * 获取模板使用统计
     */
    public function getUsageStats(array $templateIds = []): array;

    /**
     * 查找被使用的模板
     */
    public function findUsedTemplates(): array;

    /**
     * 查找未被使用的模板
     */
    public function findUnusedTemplates(): array;

    /**
     * 复制模板
     */
    public function duplicate(int $templateId, string $newName): ?MessageTemplate;

    /**
     * 获取模板版本历史（如果支持版本控制）
     */
    public function getVersionHistory(int $templateId): array;

    /**
     * 创建模板版本（如果支持版本控制）
     */
    public function createVersion(int $templateId, string $version, array $changes): bool;

    // ===== 以下方法合并自原MessageTemplateReadRepositoryInterface =====

    /**
     * 根据ID列表查找模板
     */
    public function findList(array $ids): \Illuminate\Support\Collection;

    /**
     * 获取启用的模板列表
     */
    public function getActiveList(): \Illuminate\Support\Collection;

    /**
     * 搜索模板
     */
    public function searchTemplates(string $keyword, array $filters = []): \Illuminate\Support\Collection;

    /**
     * 获取模板统计信息
     */
    public function getStatistics(array $filters = []): array;

    /**
     * 获取模板使用排行
     */
    public function getUsageRanking(int $limit = 10): array;

    /**
     * 获取模板使用趋势
     */
    public function getUsageTrend(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 获取模板变量统计
     */
    public function getVariableStats(): array;

    /**
     * 查找相似模板
     */
    public function findSimilarTemplates(int $templateId, int $limit = 5): \Illuminate\Support\Collection;

    /**
     * 获取最近创建的模板
     */
    public function getRecentlyCreated(int $limit = 10): \Illuminate\Support\Collection;

    /**
     * 获取最近更新的模板
     */
    public function getRecentlyUpdated(int $limit = 10): \Illuminate\Support\Collection;

    /**
     * 获取热门模板
     */
    public function getPopularTemplates(int $limit = 10): \Illuminate\Support\Collection;

    /**
     * 获取模板详细统计
     */
    public function getTemplateDetailStats(int $templateId): array;

    /**
     * 获取模板性能统计
     */
    public function getPerformanceStats(array $templateIds = []): array;

    /**
     * 验证模板语法
     */
    public function validateTemplateSyntax(string $template): array;

    /**
     * 预览模板渲染效果
     */
    public function previewTemplate(int $templateId, array $variables = []): array;
}
