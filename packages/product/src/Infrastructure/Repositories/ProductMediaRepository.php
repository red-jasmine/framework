<?php

namespace RedJasmine\Product\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Product\Domain\Media\Models\Enums\MediaTypeEnum;
use RedJasmine\Product\Domain\Media\Models\ProductMedia;
use RedJasmine\Product\Domain\Media\Repositories\ProductMediaRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 商品媒体资源仓库实现
 *
 * 基于Repository实现，提供商品媒体资源的读写操作能力
 */
class ProductMediaRepository extends Repository implements ProductMediaRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ProductMedia::class;

    /**
     * 获取商品主图
     */
    public function findProductPrimary(int $productId): ?ProductMedia
    {
        return $this->query()
            ->forProduct($productId)
            ->primary()
            ->enabled()
            ->first();
    }

    /**
     * 获取变体主图
     */
    public function findVariantPrimary(int $variantId): ?ProductMedia
    {
        return $this->query()
            ->forVariant($variantId)
            ->primary()
            ->enabled()
            ->first();
    }

    /**
     * 获取商品媒体列表
     */
    public function findProductMedia(int $productId, ?string $mediaType = null)
    {
        $query = $this->query()->forProduct($productId)->enabled()->ordered();

        if ($mediaType) {
            $query->ofType(MediaTypeEnum::from($mediaType));
        }

        return $query->get();
    }

    /**
     * 获取变体媒体列表
     */
    public function findVariantMedia(int $variantId, ?string $mediaType = null)
    {
        $query = $this->query()->forVariant($variantId)->enabled()->ordered();

        if ($mediaType) {
            $query->ofType(MediaTypeEnum::from($mediaType));
        }

        return $query->get();
    }

    /**
     * 设置商品主图
     */
    public function setProductPrimary(int $productId, int $mediaId): bool
    {
        return $this->query()->getConnection()->transaction(function () use ($productId, $mediaId) {
            // 取消所有商品主图标记
            $this->query()
                ->forProduct($productId)
                ->primary()
                ->update(['is_primary' => false]);

            // 设置新的主图
            return $this->query()
                ->where('id', $mediaId)
                ->where('product_id', $productId)
                ->whereNull('variant_id')
                ->update(['is_primary' => true]);
        });
    }

    /**
     * 设置变体主图
     */
    public function setVariantPrimary(int $variantId, int $mediaId): bool
    {
        return $this->query()->getConnection()->transaction(function () use ($variantId, $mediaId) {
            // 取消所有变体主图标记
            $this->query()
                ->forVariant($variantId)
                ->primary()
                ->update(['is_primary' => false]);

            // 设置新的主图
            return $this->query()
                ->where('id', $mediaId)
                ->where('variant_id', $variantId)
                ->update(['is_primary' => true]);
        });
    }



    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('position'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }

    /**
     * 获取所有者的媒体列表
     */
    public function findOwnerMedia($owner, ?string $mediaType = null)
    {
        $query = $this->query()
            ->forOwner($owner)
            ->enabled()
            ->ordered();

        if ($mediaType) {
            $query->ofType(MediaTypeEnum::from($mediaType));
        }

        return $query->get();
    }

    /**
     * 获取独立媒体列表（未关联商品）
     */
    public function findStandaloneMedia($owner, ?string $mediaType = null)
    {
        $query = $this->query()
            ->forOwner($owner)
            ->standalone()
            ->enabled()
            ->ordered();

        if ($mediaType) {
            $query->ofType(MediaTypeEnum::from($mediaType));
        }

        return $query->get();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('variant_id'),
            AllowedFilter::exact('media_type'),
            AllowedFilter::exact('is_primary'),
            AllowedFilter::exact('is_enabled'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'product',
            'variant',
        ];
    }
}

