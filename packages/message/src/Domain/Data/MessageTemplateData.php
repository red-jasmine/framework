<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Data;

use RedJasmine\Message\Domain\Models\Enums\StatusEnum;
use RedJasmine\Message\Domain\Models\ValueObjects\TemplateVariable;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息模板数据传输对象
 */
class MessageTemplateData extends Data
{
    public function __construct(
        public string $name,
        public string $titleTemplate,
        public string $contentTemplate,
        public ?array $variables = null,
        
        #[WithCast(EnumCast::class, StatusEnum::class)]
        public StatusEnum $status = StatusEnum::ENABLE,
    ) {
    }

    /**
     * 获取模板变量值对象数组
     * @return TemplateVariable[]
     */
    public function getTemplateVariables(): array
    {
        if ($this->variables === null) {
            return [];
        }

        $templateVariables = [];
        foreach ($this->variables as $variable) {
            if (is_array($variable)) {
                $templateVariables[] = TemplateVariable::fromArray($variable);
            } elseif ($variable instanceof TemplateVariable) {
                $templateVariables[] = $variable;
            }
        }

        return $templateVariables;
    }

    /**
     * 设置模板变量
     * @param TemplateVariable[] $variables
     */
    public function setTemplateVariables(array $variables): self
    {
        $this->variables = [];
        
        foreach ($variables as $variable) {
            if ($variable instanceof TemplateVariable) {
                $this->variables[] = $variable->toArray();
            } elseif (is_array($variable)) {
                $this->variables[] = $variable;
            }
        }

        return $this;
    }

    /**
     * 添加模板变量
     */
    public function addTemplateVariable(TemplateVariable $variable): self
    {
        if ($this->variables === null) {
            $this->variables = [];
        }

        $this->variables[] = $variable->toArray();

        return $this;
    }

    /**
     * 移除模板变量
     */
    public function removeTemplateVariable(string $variableName): self
    {
        if ($this->variables === null) {
            return $this;
        }

        $this->variables = array_filter(
            $this->variables,
            fn($variable) => ($variable['name'] ?? '') !== $variableName
        );

        $this->variables = array_values($this->variables);

        return $this;
    }

    /**
     * 获取模板变量名称列表
     */
    public function getVariableNames(): array
    {
        $variables = $this->getTemplateVariables();
        return array_map(fn(TemplateVariable $var) => $var->name, $variables);
    }

    /**
     * 获取必需的变量名称列表
     */
    public function getRequiredVariableNames(): array
    {
        $variables = $this->getTemplateVariables();
        $required = [];

        foreach ($variables as $variable) {
            if ($variable->required) {
                $required[] = $variable->name;
            }
        }

        return $required;
    }

    /**
     * 是否包含指定变量
     */
    public function hasVariable(string $variableName): bool
    {
        return in_array($variableName, $this->getVariableNames(), true);
    }

    /**
     * 是否启用
     */
    public function isEnabled(): bool
    {
        return $this->status === StatusEnum::ENABLE;
    }

    /**
     * 是否禁用
     */
    public function isDisabled(): bool
    {
        return $this->status === StatusEnum::DISABLE;
    }

    /**
     * 启用模板
     */
    public function enable(): self
    {
        $this->status = StatusEnum::ENABLE;
        return $this;
    }

    /**
     * 禁用模板
     */
    public function disable(): self
    {
        $this->status = StatusEnum::DISABLE;
        return $this;
    }

    /**
     * 验证模板内容中的变量
     */
    public function validateTemplateVariables(): array
    {
        $errors = [];

        // 检查标题模板
        $titleErrors = $this->validateTemplateContent($this->titleTemplate, 'title');
        $errors = array_merge($errors, $titleErrors);

        // 检查内容模板
        $contentErrors = $this->validateTemplateContent($this->contentTemplate, 'content');
        $errors = array_merge($errors, $contentErrors);

        return $errors;
    }

    /**
     * 验证模板内容
     */
    protected function validateTemplateContent(string $template, string $type): array
    {
        $errors = [];

        // 提取模板中使用的变量
        preg_match_all('/\{\{(\w+)\}\}/', $template, $matches);
        $usedVariables = $matches[1] ?? [];

        // 获取定义的变量名称
        $definedVariables = $this->getVariableNames();

        // 检查未定义的变量
        $undefinedVariables = array_diff($usedVariables, $definedVariables);
        
        foreach ($undefinedVariables as $variable) {
            $errors[] = "{$type}模板中使用了未定义的变量: {$variable}";
        }

        return $errors;
    }
}
