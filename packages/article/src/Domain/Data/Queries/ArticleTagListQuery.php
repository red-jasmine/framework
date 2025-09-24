<?php

namespace RedJasmine\Article\Domain\Data\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 文章标签列表查询
 */
class ArticleTagListQuery extends PaginateQuery
{
    public ?string $name = null;
    public ?string $color = null;
    public ?bool $is_show = null;
}
