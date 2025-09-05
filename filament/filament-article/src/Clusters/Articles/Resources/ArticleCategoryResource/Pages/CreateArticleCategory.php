<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateArticleCategory extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = ArticleCategoryResource::class;
}
