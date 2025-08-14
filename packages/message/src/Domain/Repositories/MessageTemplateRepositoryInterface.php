<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息模板写操作仓库接口
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
}
