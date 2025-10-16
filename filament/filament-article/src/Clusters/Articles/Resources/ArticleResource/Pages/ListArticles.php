<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListArticles extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
