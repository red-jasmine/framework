<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArticleTag extends CreateRecord
{
    protected static string $resource = ArticleTagResource::class;
}
