<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages;

use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditArticle extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
