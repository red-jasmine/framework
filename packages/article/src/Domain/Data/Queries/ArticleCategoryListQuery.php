<?php

namespace RedJasmine\Article\Domain\Data\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 文章分类列表查询
 */
class ArticleCategoryListQuery extends PaginateQuery
{
    public ?int $parent_id = null;
    public ?string $name = null;
    public ?bool $is_show = null;
}
