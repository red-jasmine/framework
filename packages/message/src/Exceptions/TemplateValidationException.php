<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 模板验证异常
 */
class TemplateValidationException extends MessageException
{
    public function __construct(
        string $templateName,
        array $validationErrors = [],
        int $code = 422,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: "模板验证失败: {$templateName}",
            errorCode: 'TEMPLATE_VALIDATION_ERROR',
            errorDetails: [
                'template_name' => $templateName,
                'validation_errors' => $validationErrors,
            ],
            code: $code,
            previous: $previous
        );
    }

    /**
     * 创建模板语法错误异常
     */
    public static function syntaxError(string $templateName, string $error): self
    {
        return new self(
            templateName: $templateName,
            validationErrors: ['syntax' => [$error]]
        );
    }

    /**
     * 创建未定义变量异常
     */
    public static function undefinedVariables(string $templateName, array $variables): self
    {
        return new self(
            templateName: $templateName,
            validationErrors: ['undefined_variables' => $variables]
        );
    }

    /**
     * 创建缺少必需变量异常
     */
    public static function missingRequiredVariables(string $templateName, array $variables): self
    {
        return new self(
            templateName: $templateName,
            validationErrors: ['missing_required_variables' => $variables]
        );
    }

    /**
     * 创建变量类型错误异常
     */
    public static function invalidVariableType(string $templateName, string $variable, string $expectedType, string $actualType): self
    {
        return new self(
            templateName: $templateName,
            validationErrors: [
                'invalid_variable_type' => [
                    'variable' => $variable,
                    'expected_type' => $expectedType,
                    'actual_type' => $actualType,
                ]
            ]
        );
    }

    /**
     * 创建模板内容为空异常
     */
    public static function emptyTemplate(string $templateName, string $field): self
    {
        return new self(
            templateName: $templateName,
            validationErrors: ['empty_template' => ["{$field}模板不能为空"]]
        );
    }

    /**
     * 创建大括号不匹配异常
     */
    public static function unmatchedBraces(string $templateName): self
    {
        return new self(
            templateName: $templateName,
            validationErrors: ['syntax' => ['模板中的大括号不匹配']]
        );
    }
}
