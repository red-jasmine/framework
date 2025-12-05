<?php

namespace RedJasmine\Project\Infrastructure\Helpers;

use RedJasmine\Project\Domain\Repositories\ProjectRepositoryInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;

class ProjectCodeGenerator
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {
    }

    /**
     * 生成项目代码
     */
    public function generate(UserInterface $owner, string $name): string
    {
        $baseCode = $this->extractBaseCode($name);
        $counter = 1;
        $code = $baseCode;

        while ($this->projectRepository->codeExists($owner, $code)) {
            $code = $baseCode . $counter;
            $counter++;
        }

        return $code;
    }

    /**
     * 从项目名称提取基础代码
     */
    protected function extractBaseCode(string $name): string
    {
        // 移除特殊字符，只保留字母和数字
        $baseCode = preg_replace('/[^a-zA-Z0-9]/', '', $name);

        // 转换为大写
        $baseCode = strtoupper($baseCode);

        // 限制长度
        $maxLength = config('project.code_generation.max_length', 20);
        $minLength = config('project.code_generation.min_length', 2);

        if (strlen($baseCode) > $maxLength) {
            $baseCode = substr($baseCode, 0, $maxLength);
        }

        if (strlen($baseCode) < $minLength) {
            $baseCode = str_pad($baseCode, $minLength, 'X');
        }

        return $baseCode;
    }

    /**
     * 验证项目代码格式
     */
    public function validate(string $code): bool
    {
        $maxLength = config('project.code_generation.max_length', 20);
        $minLength = config('project.code_generation.min_length', 2);

        return strlen($code) >= $minLength
            && strlen($code) <= $maxLength
            && preg_match('/^[A-Z0-9]+$/', $code);
    }
}
