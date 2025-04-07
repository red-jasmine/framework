<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateArticle extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = ArticleResource::class;
}
