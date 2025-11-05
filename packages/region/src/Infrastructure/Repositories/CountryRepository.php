<?php

namespace RedJasmine\Region\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 国家仓库实现
 *
 * 基于Repository实现，提供国家实体的读写操作能力
 */
class CountryRepository extends Repository implements CountryRepositoryInterface
{
    use HasTree;

    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Country::class;

    /**
     * 默认排序
     */
    protected mixed $defaultSort = 'code';

    /**
     * 根据代码查找国家
     */
    public function find(FindQuery $query): ?Model
    {
        return $this->query($query->except($query->getPrimaryKey()))
                    ->where('code', $query->getKey())
                    ->firstOrFail();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('code'),
            AllowedFilter::exact('iso_alpha_3'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('region'),
            AllowedFilter::exact('native'),
            AllowedFilter::exact('currency'),
            AllowedFilter::exact('phone_code'),
        ];
    }
}
