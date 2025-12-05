<?php

namespace RedJasmine\Product\Domain\Attribute\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeGroupRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * 验证属性组是否存在（Laravel 验证规则）
 * 
 * 使用 Laravel 标准的 ValidationRule 接口，可以在 Spatie Laravel Data 的 rules() 方法中直接使用
 */
class ProductAttributeGroupExistsRule implements ValidationRule
{
    public function __construct(
        protected ?ProductAttributeGroupRepositoryInterface $repository = null
    ) {
        // 如果没有注入，则从容器中解析
        if ($this->repository === null) {
            $this->repository = app(ProductAttributeGroupRepositoryInterface::class);
        }
    }

    /**
     * 验证属性值
     * 
     * @param string $attribute 属性名
     * @param mixed $value 属性值
     * @param Closure $fail 失败回调
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 属性组是可选的，如果为空则跳过验证
        if (empty($value) || $value === 0) {
            return;
        }

        // 领域规则：验证属性组是否存在
        $group = $this->repository->find($value);

        if (!$group) {
            $fail('属性组不存在: ' . $value);
        }
    }
}

