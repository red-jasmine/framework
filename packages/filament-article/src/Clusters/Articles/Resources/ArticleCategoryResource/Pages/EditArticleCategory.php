<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticleCategory extends EditRecord
{
    protected static string $resource = ArticleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
