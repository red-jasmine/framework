<?php

namespace RedJasmine\Article\Application\Services\Article;

use RedJasmine\Article\Application\Services\Article\Commands\ArticlePublishCommandHandler;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleReadRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Article\Domain\Transformer\ArticleTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\ApprovalCommandHandler;
use RedJasmine\Support\Application\Commands\SubmitApprovalCommandHandler;

class ArticleApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public ArticleReadRepositoryInterface $readRepository,
        public ArticleTransformer $transformer
    ) {
    }

    protected static string $modelClass = Article::class;


    protected static $macros = [
        'approval'       => ApprovalCommandHandler::class,
        'submitApproval' => SubmitApprovalCommandHandler::class,
        'publish'        => ArticlePublishCommandHandler::class,
    ];


}