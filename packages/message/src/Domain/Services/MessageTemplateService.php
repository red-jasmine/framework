<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Services;

use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Message\Domain\Models\ValueObjects\TemplateVariable;

/**
 * 消息模板领域服务
 */
class MessageTemplateService
{
    /**
     * 处理模板渲染
     */
    public function process(MessageTemplate $template, array $variables = []): array
    {
        // 验证模板
        $this->validateTemplate($template);

        // 验证变量
        $template->validateVariables($variables);

        // 渲染标题和内容
        $title = $this->renderTemplate($template->title_template, $variables, $template);
        $content = $this->renderTemplate($template->content_template, $variables, $template);

        return [
            'title' => $title,
            'content' => $content,
            'variables_used' => array_keys($variables),
        ];
    }

    /**
     * 渲染模板
     */
    public function renderTemplate(string $template, array $variables, MessageTemplate $templateModel): string
    {
        $rendered = $template;
        $templateVariables = $templateModel->getTemplateVariables();

        // 替换变量
        foreach ($templateVariables as $templateVar) {
            $placeholder = '{{' . $templateVar->name . '}}';
            $value = $templateVar->getValue($variables[$templateVar->name] ?? null);
            
            // 格式化值
            $formattedValue = $this->formatValue($value, $templateVar);
            
            $rendered = str_replace($placeholder, (string) $formattedValue, $rendered);
        }

        // 检查是否还有未替换的变量
        if (preg_match('/\{\{(\w+)\}\}/', $rendered, $matches)) {
            throw new \InvalidArgumentException(
                "模板中存在未定义的变量: {$matches[1]}"
            );
        }

        return $rendered;
    }

    /**
     * 验证模板
     */
    public function validateTemplate(MessageTemplate $template): void
    {
        if (!$template->isEnabled()) {
            throw new \InvalidArgumentException('模板已被禁用');
        }

        if (empty($template->title_template)) {
            throw new \InvalidArgumentException('标题模板不能为空');
        }

        if (empty($template->content_template)) {
            throw new \InvalidArgumentException('内容模板不能为空');
        }

        // 验证模板语法
        $this->validateTemplateSyntax($template->title_template);
        $this->validateTemplateSyntax($template->content_template);
    }

    /**
     * 验证模板语法
     */
    protected function validateTemplateSyntax(string $template): void
    {
        // 检查大括号是否匹配
        $openCount = substr_count($template, '{');
        $closeCount = substr_count($template, '}');
        
        if ($openCount !== $closeCount) {
            throw new \InvalidArgumentException('模板中的大括号不匹配');
        }

        // 检查变量格式
        if (preg_match_all('/\{\{([^}]*)\}\}/', $template, $matches)) {
            foreach ($matches[1] as $variable) {
                $variable = trim($variable);
                
                if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $variable)) {
                    throw new \InvalidArgumentException(
                        "模板中的变量名格式不正确: {$variable}"
                    );
                }
            }
        }

        // 检查是否有格式错误的变量标记
        if (preg_match('/\{[^{]|\}[^}]/', $template)) {
            throw new \InvalidArgumentException(
                '模板中的变量标记格式错误，应使用 {{variable}} 格式'
            );
        }
    }

    /**
     * 格式化变量值
     */
    protected function formatValue(mixed $value, TemplateVariable $variable): mixed
    {
        if ($value === null) {
            return '';
        }

        return match ($variable->type) {
            'string' => (string) $value,
            'number' => $this->formatNumber($value),
            'boolean' => $value ? '是' : '否',
            'array' => $this->formatArray($value),
            'object' => $this->formatObject($value),
            default => (string) $value,
        };
    }

    /**
     * 格式化数字
     */
    protected function formatNumber(mixed $value): string
    {
        if (!is_numeric($value)) {
            return (string) $value;
        }

        // 如果是整数，直接返回
        if (is_int($value) || (is_float($value) && floor($value) == $value)) {
            return (string) (int) $value;
        }

        // 保留两位小数
        return number_format((float) $value, 2);
    }

    /**
     * 格式化数组
     */
    protected function formatArray(mixed $value): string
    {
        if (!is_array($value)) {
            return (string) $value;
        }

        // 如果是关联数组，转换为键值对字符串
        if ($this->isAssociativeArray($value)) {
            $pairs = [];
            foreach ($value as $key => $val) {
                $pairs[] = "{$key}: {$val}";
            }
            return implode(', ', $pairs);
        }

        // 如果是索引数组，用逗号分隔
        return implode(', ', $value);
    }

    /**
     * 格式化对象
     */
    protected function formatObject(mixed $value): string
    {
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
            
            if (method_exists($value, 'toArray')) {
                return $this->formatArray($value->toArray());
            }
            
            return get_class($value);
        }

        if (is_array($value)) {
            return $this->formatArray($value);
        }

        return (string) $value;
    }

    /**
     * 检查是否为关联数组
     */
    protected function isAssociativeArray(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * 预览模板渲染效果
     */
    public function preview(MessageTemplate $template, array $variables = []): array
    {
        try {
            $result = $this->process($template, $variables);
            
            return [
                'success' => true,
                'title' => $result['title'],
                'content' => $result['content'],
                'variables_used' => $result['variables_used'],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'title' => null,
                'content' => null,
            ];
        }
    }

    /**
     * 获取模板中使用的变量
     */
    public function extractVariables(string $template): array
    {
        preg_match_all('/\{\{(\w+)\}\}/', $template, $matches);
        return array_unique($matches[1] ?? []);
    }

    /**
     * 检查模板完整性
     */
    public function checkTemplateIntegrity(MessageTemplate $template): array
    {
        $issues = [];

        try {
            $this->validateTemplate($template);
        } catch (\Exception $e) {
            $issues[] = [
                'type' => 'error',
                'message' => $e->getMessage(),
            ];
        }

        // 检查未使用的变量
        $titleVars = $this->extractVariables($template->title_template);
        $contentVars = $this->extractVariables($template->content_template);
        $usedVars = array_unique(array_merge($titleVars, $contentVars));
        
        $definedVars = [];
        foreach ($template->getTemplateVariables() as $var) {
            $definedVars[] = $var->name;
        }

        $unusedVars = array_diff($definedVars, $usedVars);
        if (!empty($unusedVars)) {
            $issues[] = [
                'type' => 'warning',
                'message' => '存在未使用的变量: ' . implode(', ', $unusedVars),
            ];
        }

        return $issues;
    }
}
