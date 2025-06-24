<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 使用邀请码命令
 */
final class InvitationCodeUseCommand extends Data
{
    public function __construct(
        public string $code,                    // 邀请码
        public UserInterface $user,             // 使用者
        public array $context = [],             // 使用上下文
    ) {
    }

    /**
     * 验证规则
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'min:4', 'max:20'],
            'user' => ['required'],
            'context' => ['array'],
        ];
    }

    /**
     * 验证消息
     */
    public function messages(): array
    {
        return [
            'code.required' => '邀请码不能为空',
            'code.min' => '邀请码长度不能少于4位',
            'code.max' => '邀请码长度不能超过20位',
            'user.required' => '使用者不能为空',
        ];
    }
} 