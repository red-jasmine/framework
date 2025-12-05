<?php

namespace RedJasmine\Product\Domain\Attribute\Services;

use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeGroupRepositoryInterface;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeRepositoryInterface;
use RedJasmine\Product\Exceptions\ProductAttributeException;

/**
 * 商品属性领域服务
 *
 * 负责商品属性领域的核心业务规则验证
 * 可以被多个应用服务复用，避免重复代码
 */
class ProductAttributeDomainService
{
    public function __construct(
        protected ProductAttributeRepositoryInterface $attributeRepository,
        protected ProductAttributeGroupRepositoryInterface $groupRepository,
    ) {
    }

    /**
     * 验证属性组是否存在
     *
     * @param int|null $groupId 属性组ID，可选
     * @return void
     * @throws ProductAttributeException
     */
    public function validateGroupExists(?int $groupId): void
    {
        // 属性组是可选的，如果为空则跳过验证
        if (empty($groupId) || $groupId === 0) {
            return;
        }

        $group = $this->groupRepository->find($groupId);
        if (!$group) {
            throw new ProductAttributeException('属性组不存在');
        }
    }

    /**
     * 验证属性是否存在
     *
     * @param int $attributeId 属性ID
     * @return void
     * @throws ProductAttributeException
     */
    public function validateAttributeExists(int $attributeId): void
    {
        if (empty($attributeId)) {
            throw new ProductAttributeException('属性ID不能为空');
        }

        $attribute = $this->attributeRepository->find($attributeId);
        if (!$attribute) {
            throw new ProductAttributeException('属性不存在');
        }
    }

    /**
     * 验证属性值数据
     * 同时验证属性ID和属性组ID
     *
     * @param int $attributeId 属性ID（必填）
     * @param int|null $groupId 属性组ID（可选）
     * @return void
     * @throws ProductAttributeException
     */
    public function validateAttributeValueData(int $attributeId, ?int $groupId = null): void
    {
        // 验证属性是否存在
        $this->validateAttributeExists($attributeId);

        // 验证属性组是否存在（如果提供了）
        $this->validateGroupExists($groupId);
    }
}

