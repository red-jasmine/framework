<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Data;

use RedJasmine\Invitation\Domain\Models\Enums\GenerateType;
use RedJasmine\Support\Data\Data;

/**
 * 邀请码创建命令
 */
final class InvitationCodeCreateCommand extends Data
{
    public function __construct(
        public string $code = '',                      // 邀请码
        public string $inviterType = '',               // 邀请人类型
        public string $inviterId = '',                 // 邀请人ID
        public string $inviterName = '',               // 邀请人姓名
        public string $title = '',                     // 邀请标题
        public string $description = '',               // 邀请描述
        public string $slogan = '',                    // 广告语
        public GenerateType $generateType = GenerateType::SYSTEM, // 生成类型
        public int $maxUsage = 0,                      // 最大使用次数
        public ?string $expiresAt = null,              // 过期时间
        public array $tags = [],                       // 标签
        public array $extraData = [],                  // 扩展数据
        public array $destinations = [],               // 去向配置
    ) {
    }

    /**
     * 验证数据
     */
    public function rules(): array
    {
        return [
            'inviterType' => ['required', 'string', 'max:50'],
            'inviterId' => ['required', 'string', 'max:100'],
            'inviterName' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'slogan' => ['nullable', 'string', 'max:500'],
            'code' => [
                'required_if:generateType,custom',
                'string',
                'min:4',
                'max:20',
                'regex:/^[a-zA-Z][a-zA-Z0-9_-]*$/'
            ],
            'generateType' => ['required', 'string'],
            'maxUsage' => ['integer', 'min:0'],
            'expiresAt' => ['nullable', 'date', 'after:now'],
            'tags' => ['array'],
            'extraData' => ['array'],
            'destinations' => ['array', 'min:1'],
            'destinations.*.destinationType' => ['required', 'string'],
            'destinations.*.platformType' => ['required', 'string'],
            'destinations.*.destinationUrl' => ['nullable', 'string', 'max:1000'],
            'destinations.*.isDefault' => ['boolean'],
        ];
    }

    /**
     * 验证消息
     */
    public function messages(): array
    {
        return [
            'inviterType.required' => '邀请人类型不能为空',
            'inviterId.required' => '邀请人ID不能为空',
            'inviterName.required' => '邀请人姓名不能为空',
            'title.required' => '邀请标题不能为空',
            'code.required_if' => '自定义模式下邀请码不能为空',
            'code.regex' => '邀请码格式不正确',
            'expiresAt.after' => '过期时间必须大于当前时间',
            'destinations.min' => '至少需要配置一个去向',
        ];
    }
} 