<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 消息模板只读仓库接口
 */
interface MessageTemplateReadRepositoryInterface extends ReadRepositoryInterface
{
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
