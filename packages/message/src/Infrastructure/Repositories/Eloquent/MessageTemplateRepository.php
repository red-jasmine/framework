<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\Repositories\Eloquent;

use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Message\Domain\Repositories\MessageTemplateRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * 消息模板仓库实现
 */
class MessageTemplateRepository extends Repository implements MessageTemplateRepositoryInterface
{
    protected static string $modelClass = MessageTemplate::class;

    // 基础接口方法实现
    public function findByName(string $name): ?MessageTemplate
    {
        return MessageTemplate::where('name', $name)->first();
    }

    public function findActive(): array
    {
        return MessageTemplate::where('status', 'enable')->get()->toArray();
    }

    public function findInactive(): array
    {
        return MessageTemplate::where('status', 'disable')->get()->toArray();
    }

    public function batchEnable(array $ids): int
    {
        return MessageTemplate::whereIn('id', $ids)->update(['status' => 'enable']);
    }

    public function batchDisable(array $ids): int
    {
        return MessageTemplate::whereIn('id', $ids)->update(['status' => 'disable']);
    }

    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = MessageTemplate::where('name', $name);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    public function findByVariable(string $variable): array
    {
        return MessageTemplate::whereJsonContains('variables', $variable)->get()->toArray();
    }

    public function getUsageStats(): array
    {
        return MessageTemplate::selectRaw('id, name, usage_count')->get()->toArray();
    }

    public function findUsedTemplates(): array
    {
        return MessageTemplate::where('usage_count', '>', 0)->get()->toArray();
    }

    public function findUnusedTemplates(): array
    {
        return MessageTemplate::where('usage_count', 0)->get()->toArray();
    }

    public function duplicate(int $templateId): ?MessageTemplate
    {
        $template = MessageTemplate::find($templateId);
        if ($template) {
            $newTemplate = $template->replicate();
            $newTemplate->name = $template->name . '_copy';
            $newTemplate->save();
            return $newTemplate;
        }
        return null;
    }

    public function getVersionHistory(int $templateId): array
    {
        // 简化实现
        return [];
    }

    public function createVersion(int $templateId, array $data): bool
    {
        // 简化实现
        return true;
    }
}
