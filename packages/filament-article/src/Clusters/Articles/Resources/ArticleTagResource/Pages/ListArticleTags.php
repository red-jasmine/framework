<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArticleTags extends ListRecords
{
    protected static string $resource = ArticleTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
