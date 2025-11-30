<?php

namespace RedJasmine\Product\Domain\Media\Repositories;

use RedJasmine\Product\Domain\Media\Models\ProductMedia;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 商品媒体资源仓库接口
 *
 * 提供商品媒体资源的读写操作统一接口
 *
 * @method ProductMedia find($id)
 */
interface ProductMediaRepositoryInterface extends RepositoryInterface
{
    /**
     * 获取商品主图
     *
     * @param int $productId 商品ID
     * @return ProductMedia|null
     */
    public function findProductPrimary(int $productId): ?ProductMedia;

    /**
     * 获取变体主图
     *
     * @param int $variantId 变体ID
     * @return ProductMedia|null
     */
    public function findVariantPrimary(int $variantId): ?ProductMedia;

    /**
     * 获取商品媒体列表
     *
     * @param int $productId 商品ID
     * @param string|null $mediaType 媒体类型（可选）
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findProductMedia(int $productId, ?string $mediaType = null);

    /**
     * 获取变体媒体列表
     *
     * @param int $variantId 变体ID
     * @param string|null $mediaType 媒体类型（可选）
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findVariantMedia(int $variantId, ?string $mediaType = null);

    /**
     * 设置商品主图
     *
     * @param int $productId 商品ID
     * @param int $mediaId 媒体ID
     * @return bool
     */
    public function setProductPrimary(int $productId, int $mediaId): bool;

    /**
     * 设置变体主图
     *
     * @param int $variantId 变体ID
     * @param int $mediaId 媒体ID
     * @return bool
     */
    public function setVariantPrimary(int $variantId, int $mediaId): bool;

    /**
     * 获取所有者的媒体列表
     *
     * @param mixed $owner 所有者对象
     * @param string|null $mediaType 媒体类型（可选）
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findOwnerMedia($owner, ?string $mediaType = null);

    /**
     * 获取独立媒体列表（未关联商品）
     *
     * @param mixed $owner 所有者对象
     * @param string|null $mediaType 媒体类型（可选）
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findStandaloneMedia($owner, ?string $mediaType = null);
}

