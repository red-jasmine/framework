<?php

namespace RedJasmine\Article\Domain\Data\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 文章列表查询
 */
class ArticleListQuery extends PaginateQuery
{
    public ?int $category_id = null;
    public ?string $title = null;
    public ?string $status = null;
    public ?bool $is_top = null;
    public ?bool $is_show = null;
}
