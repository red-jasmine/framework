<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateArticleTag extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = ArticleTagResource::class;
}
