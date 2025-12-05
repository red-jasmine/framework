<?php

namespace RedJasmine\Product\Domain\Attribute\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * 验证属性是否存在（Laravel 验证规则）
 *
 * 使用 Laravel 标准的 ValidationRule 接口，可以在 Spatie Laravel Data 的 rules() 方法中直接使用
 */
class ProductAttributeExistsRule implements ValidationRule
{
    public function __construct(
        protected ?ProductAttributeRepositoryInterface $repository = null
    ) {
        // 如果没有注入，则从容器中解析
        if ($this->repository === null) {
            $this->repository = app(ProductAttributeRepositoryInterface::class);
        }
    }

    /**
     * 验证属性值
     *
     * @param  string  $attribute  属性名
     * @param  mixed  $value  属性值
     * @param  Closure  $fail  失败回调
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (empty($value)) {
            return; // 空值由 required 规则处理
        }

        // 领域规则：验证属性是否存在
        $attributeModel = $this->repository->find($value);

        if (!$attributeModel) {
            $fail('属性不存在: '.$value);
        }
    }
}

