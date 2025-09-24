<?php

namespace RedJasmine\Article\Domain\Repositories;

use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 文章仓库接口
 *
 * 提供文章实体的读写操作统一接口
 *
 * @method Article find($id)
 */
interface ArticleRepositoryInterface extends RepositoryInterface
{

}
