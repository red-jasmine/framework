<?php

namespace RedJasmine\Announcement\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\Domain\Repositories\CategoryReadRepositoryInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class CategoryReadRepository extends QueryBuilderReadRepository implements CategoryReadRepositoryInterface
{
    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = AnnouncementCategory::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('biz'),
            AllowedFilter::callback('owner', fn(Builder $builder, $value) => $builder->onlyOwner(
                is_array($value) ? UserData::from($value) : $value
            )),
        ];
    }
}
