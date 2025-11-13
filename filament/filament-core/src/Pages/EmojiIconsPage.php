<?php

namespace RedJasmine\FilamentCore\Pages;

use Filament\Pages\Page;

class EmojiIconsPage extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-sparkles';
    protected string $view = 'red-jasmine-filament-core::emoji-icons';

    protected static ?string $navigationLabel = 'Emoji 图标库';

    protected static ?string $title = 'Emoji 图标库';

    protected static ?int $navigationSort = 100;
}

