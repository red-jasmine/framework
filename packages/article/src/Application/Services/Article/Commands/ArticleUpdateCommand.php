<?php

namespace RedJasmine\Article\Application\Services\Article\Commands;

use RedJasmine\Article\Domain\Data\ArticleData;

class ArticleUpdateCommand extends ArticleData
{

    public int $id;

}