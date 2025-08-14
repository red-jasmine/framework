<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Message\Domain\Models\Enums\StatusEnum;
use RedJasmine\Message\Domain\Models\ValueObjects\TemplateVariable;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

/**
 * 消息模板聚合根
 */
class MessageTemplate extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'title_template',
        'content_template',
        'variables',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'variables' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // 模板创建时的业务规则
        static::creating(function (self $template) {
            $template->validateCreation();
        });

        // 模板更新时的业务规则
        static::updating(function (self $template) {
            $template->validateUpdate();
        });

        // 模板删除时的业务规则
        static::deleting(function (self $template) {
            $template->validateDeletion();
        });
    }

    /**
     * 消息关联
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'template_id');
    }

    /**
     * 获取模板变量值对象数组
     * @return TemplateVariable[]
     */
    public function getTemplateVariables(): array
    {
        $variables = $this->variables ?? [];
        $templateVariables = [];

        foreach ($variables as $variable) {
            $templateVariables[] = TemplateVariable::fromArray($variable);
        }

        return $templateVariables;
    }

    /**
     * 渲染模板标题
     */
    public function renderTitle(array $variables = []): string
    {
        return $this->renderTemplate($this->title_template, $variables);
    }

    /**
     * 渲染模板内容
     */
    public function renderContent(array $variables = []): string
    {
        return $this->renderTemplate($this->content_template, $variables);
    }

    /**
     * 渲染模板
     */
    public function renderTemplate(string $template, array $variables = []): string
    {
        $this->validateVariables($variables);

        // 简单的模板渲染（可以替换为更复杂的模板引擎）
        $rendered = $template;

        foreach ($variables as $name => $value) {
            $rendered = str_replace("{{$name}}", (string) $value, $rendered);
        }

        // 检查是否还有未替换的变量
        if (preg_match('/\{\{(\w+)\}\}/', $rendered, $matches)) {
            $missingVar = $matches[1];
            
            // 尝试使用默认值
            $templateVariables = $this->getTemplateVariables();
            foreach ($templateVariables as $templateVar) {
                if ($templateVar->name === $missingVar) {
                    $defaultValue = $templateVar->getValue();
                    $rendered = str_replace("{{$missingVar}}", (string) $defaultValue, $rendered);
                    break;
                }
            }
        }

        return $rendered;
    }

    /**
     * 验证模板变量
     */
    public function validateVariables(array $variables): void
    {
        $templateVariables = $this->getTemplateVariables();
        
        foreach ($templateVariables as $templateVar) {
            $value = $variables[$templateVar->name] ?? null;
            
            if ($templateVar->required && $value === null) {
                throw new \InvalidArgumentException(
                    "模板变量 '{$templateVar->name}' 是必需的"
                );
            }

            if ($value !== null && !$templateVar->validateValue($value)) {
                throw new \InvalidArgumentException(
                    "模板变量 '{$templateVar->name}' 的值不符合要求"
                );
            }
        }
    }

    /**
     * 获取必需的变量名称
     */
    public function getRequiredVariableNames(): array
    {
        $templateVariables = $this->getTemplateVariables();
        $required = [];

        foreach ($templateVariables as $templateVar) {
            if ($templateVar->required) {
                $required[] = $templateVar->name;
            }
        }

        return $required;
    }

    /**
     * 启用模板
     */
    public function enable(): void
    {
        if ($this->status === StatusEnum::ENABLE) {
            return;
        }

        $this->status = StatusEnum::ENABLE;
        $this->save();

        // 发布模板启用事件
        $this->dispatchTemplateEnabledEvent();
    }

    /**
     * 禁用模板
     */
    public function disable(): void
    {
        if ($this->status === StatusEnum::DISABLE) {
            return;
        }

        $this->status = StatusEnum::DISABLE;
        $this->save();

        // 发布模板禁用事件
        $this->dispatchTemplateDisabledEvent();
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
     * 获取使用此模板的消息数量
     */
    public function getMessageCount(): int
    {
        return $this->messages()->count();
    }

    /**
     * 是否可以删除
     */
    public function canDelete(): bool
    {
        return $this->getMessageCount() === 0;
    }

    /**
     * 验证模板创建
     */
    protected function validateCreation(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('模板名称不能为空');
        }

        if (empty($this->title_template)) {
            throw new \InvalidArgumentException('标题模板不能为空');
        }

        if (empty($this->content_template)) {
            throw new \InvalidArgumentException('内容模板不能为空');
        }

        // 验证名称唯一性
        $this->validateNameUniqueness();

        // 验证模板语法
        $this->validateTemplateSyntax();
    }

    /**
     * 验证模板更新
     */
    protected function validateUpdate(): void
    {
        if ($this->isDirty('name')) {
            if (empty($this->name)) {
                throw new \InvalidArgumentException('模板名称不能为空');
            }
            
            // 验证名称唯一性
            $this->validateNameUniqueness();
        }

        if ($this->isDirty(['title_template', 'content_template'])) {
            if (empty($this->title_template)) {
                throw new \InvalidArgumentException('标题模板不能为空');
            }

            if (empty($this->content_template)) {
                throw new \InvalidArgumentException('内容模板不能为空');
            }

            // 验证模板语法
            $this->validateTemplateSyntax();
        }
    }

    /**
     * 验证模板删除
     */
    protected function validateDeletion(): void
    {
        if (!$this->canDelete()) {
            throw new \InvalidArgumentException('该模板正在被使用，不能删除');
        }
    }

    /**
     * 验证名称唯一性
     */
    protected function validateNameUniqueness(): void
    {
        $query = static::where('name', $this->name);

        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        if ($query->exists()) {
            throw new \InvalidArgumentException('模板名称已存在');
        }
    }

    /**
     * 验证模板语法
     */
    protected function validateTemplateSyntax(): void
    {
        // 检查标题模板中的变量
        $this->validateTemplateVariablesInContent($this->title_template);
        
        // 检查内容模板中的变量
        $this->validateTemplateVariablesInContent($this->content_template);
    }

    /**
     * 验证模板内容中的变量
     */
    protected function validateTemplateVariablesInContent(string $content): void
    {
        // 提取模板中使用的变量
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);
        $usedVariables = $matches[1] ?? [];

        // 获取定义的变量
        $definedVariables = [];
        $templateVariables = $this->getTemplateVariables();
        foreach ($templateVariables as $templateVar) {
            $definedVariables[] = $templateVar->name;
        }

        // 检查未定义的变量
        $undefinedVariables = array_diff($usedVariables, $definedVariables);
        if (!empty($undefinedVariables)) {
            throw new \InvalidArgumentException(
                '模板中使用了未定义的变量: ' . implode(', ', $undefinedVariables)
            );
        }
    }

    /**
     * 发布模板启用事件
     */
    protected function dispatchTemplateEnabledEvent(): void
    {
        // 这里可以发布领域事件
        // event(new MessageTemplateEnabledEvent($this));
    }

    /**
     * 发布模板禁用事件
     */
    protected function dispatchTemplateDisabledEvent(): void
    {
        // 这里可以发布领域事件
        // event(new MessageTemplateDisabledEvent($this));
    }

    /**
     * 查询作用域：启用的模板
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', StatusEnum::ENABLE);
    }

    /**
     * 查询作用域：禁用的模板
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', StatusEnum::DISABLE);
    }

    /**
     * 查询作用域：按名称查询
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }
}
