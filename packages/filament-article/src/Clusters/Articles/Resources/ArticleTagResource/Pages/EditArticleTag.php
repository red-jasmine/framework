<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticleTag extends EditRecord
{
    protected static string $resource = ArticleTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
