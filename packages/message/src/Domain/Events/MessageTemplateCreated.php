<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\MessageTemplate;

/**
 * 消息模板创建事件
 */
class MessageTemplateCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MessageTemplate $template
    ) {
    }

    /**
     * 获取事件标识
     */
    public function getEventId(): string
    {
        return 'message.template.created';
    }

    /**
     * 获取模板ID
     */
    public function getTemplateId(): int
    {
        return $this->template->id;
    }

    /**
     * 获取模板名称
     */
    public function getTemplateName(): string
    {
        return $this->template->name;
    }

    /**
     * 是否启用
     */
    public function isEnabled(): bool
    {
        return $this->template->isEnabled();
    }

    /**
     * 获取模板变量数量
     */
    public function getVariableCount(): int
    {
        return count($this->template->getTemplateVariables());
    }

    /**
     * 获取模板变量名称列表
     */
    public function getVariableNames(): array
    {
        $variables = $this->template->getTemplateVariables();
        return array_map(fn($var) => $var->name, $variables);
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId(),
            'template_id' => $this->getTemplateId(),
            'template_name' => $this->getTemplateName(),
            'is_enabled' => $this->isEnabled(),
            'variable_count' => $this->getVariableCount(),
            'variable_names' => $this->getVariableNames(),
            'created_at' => $this->template->created_at?->toISOString(),
        ];
    }
}
