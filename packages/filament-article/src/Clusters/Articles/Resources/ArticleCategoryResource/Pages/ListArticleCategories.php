<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArticleCategories extends ListRecords
{
    protected static string $resource = ArticleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
