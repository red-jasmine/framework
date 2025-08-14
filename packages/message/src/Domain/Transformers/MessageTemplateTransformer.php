<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Message\Domain\Data\MessageTemplateData;
use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 消息模板转换器
 */
class MessageTemplateTransformer implements TransformerInterface
{
    /**
     * 将数据转换为模型
     */
    public function transform($data, $model): Model
    {
        if (!$data instanceof MessageTemplateData) {
            throw new \InvalidArgumentException('数据必须是 MessageTemplateData 类型');
        }

        if (!$model instanceof MessageTemplate) {
            throw new \InvalidArgumentException('模型必须是 MessageTemplate 类型');
        }

        // 基础字段映射
        $model->name = $data->name;
        $model->title_template = $data->titleTemplate;
        $model->content_template = $data->contentTemplate;
        $model->status = $data->status;

        // 处理模板变量
        if ($data->variables !== null) {
            $model->variables = $data->variables;
        }

        return $model;
    }

    /**
     * 验证数据
     */
    public function validateData($data): void
    {
        if (!$data instanceof MessageTemplateData) {
            throw new \InvalidArgumentException('数据必须是 MessageTemplateData 类型');
        }

        // 验证必需字段
        if (empty($data->name)) {
            throw new \InvalidArgumentException('模板名称不能为空');
        }

        if (empty($data->titleTemplate)) {
            throw new \InvalidArgumentException('标题模板不能为空');
        }

        if (empty($data->contentTemplate)) {
            throw new \InvalidArgumentException('内容模板不能为空');
        }

        // 验证名称长度
        if (mb_strlen($data->name) > 100) {
            throw new \InvalidArgumentException('模板名称长度不能超过100个字符');
        }

        // 验证模板语法
        $this->validateTemplateSyntax($data);
    }

    /**
     * 映射属性
     */
    public function mapProperties($data, $model): void
    {
        $this->transform($data, $model);
    }

    /**
     * 验证模板
     */
    public function validateTemplate($data): void
    {
        $this->validateData($data);

        // 验证模板变量的完整性
        $errors = $data->validateTemplateVariables();
        if (!empty($errors)) {
            throw new \InvalidArgumentException(
                '模板验证失败: ' . implode('; ', $errors)
            );
        }
    }

    /**
     * 映射变量
     */
    public function mapVariables($data, $model): void
    {
        if (!$data instanceof MessageTemplateData) {
            throw new \InvalidArgumentException('数据必须是 MessageTemplateData 类型');
        }

        if (!$model instanceof MessageTemplate) {
            throw new \InvalidArgumentException('模型必须是 MessageTemplate 类型');
        }

        // 只映射变量相关的字段
        if ($data->variables !== null) {
            $model->variables = $data->variables;
        }
    }

    /**
     * 验证模板语法
     */
    private function validateTemplateSyntax(MessageTemplateData $data): void
    {
        // 验证标题模板语法
        $this->validateTemplateContent($data->titleTemplate, '标题');

        // 验证内容模板语法
        $this->validateTemplateContent($data->contentTemplate, '内容');

        // 验证模板变量定义
        $this->validateTemplateVariableDefinitions($data);
    }

    /**
     * 验证模板内容
     */
    private function validateTemplateContent(string $template, string $type): void
    {
        // 检查模板变量语法 {{variable}}
        if (preg_match_all('/\{\{([^}]*)\}\}/', $template, $matches)) {
            foreach ($matches[1] as $variable) {
                $variable = trim($variable);
                
                // 检查变量名格式
                if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $variable)) {
                    throw new \InvalidArgumentException(
                        "{$type}模板中的变量名格式不正确: {$variable}"
                    );
                }
            }
        }

        // 检查是否有未闭合的大括号
        $openCount = substr_count($template, '{');
        $closeCount = substr_count($template, '}');
        
        if ($openCount !== $closeCount) {
            throw new \InvalidArgumentException(
                "{$type}模板中的大括号不匹配"
            );
        }

        // 检查是否有格式错误的变量标记
        if (preg_match('/\{[^{]|\}[^}]|[^{]\{[^{]|[^}]\}[^}]/', $template)) {
            throw new \InvalidArgumentException(
                "{$type}模板中的变量标记格式错误，应使用 {{variable}} 格式"
            );
        }
    }

    /**
     * 验证模板变量定义
     */
    private function validateTemplateVariableDefinitions(MessageTemplateData $data): void
    {
        $templateVariables = $data->getTemplateVariables();
        $variableNames = [];

        foreach ($templateVariables as $variable) {
            // 检查变量名是否重复
            if (in_array($variable->name, $variableNames, true)) {
                throw new \InvalidArgumentException(
                    "模板变量名重复: {$variable->name}"
                );
            }
            
            $variableNames[] = $variable->name;
        }

        // 提取模板中使用的变量
        $usedVariables = [];
        
        preg_match_all('/\{\{(\w+)\}\}/', $data->titleTemplate, $titleMatches);
        preg_match_all('/\{\{(\w+)\}\}/', $data->contentTemplate, $contentMatches);
        
        $usedVariables = array_unique(array_merge(
            $titleMatches[1] ?? [],
            $contentMatches[1] ?? []
        ));

        // 检查未定义的变量
        $undefinedVariables = array_diff($usedVariables, $variableNames);
        if (!empty($undefinedVariables)) {
            throw new \InvalidArgumentException(
                '模板中使用了未定义的变量: ' . implode(', ', $undefinedVariables)
            );
        }

        // 检查未使用的变量（警告性质，不抛出异常）
        $unusedVariables = array_diff($variableNames, $usedVariables);
        if (!empty($unusedVariables)) {
            // 可以记录日志或发出警告
            // Log::warning('模板中定义了未使用的变量', ['variables' => $unusedVariables]);
        }
    }
}
