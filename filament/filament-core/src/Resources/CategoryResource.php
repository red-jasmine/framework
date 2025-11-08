<?php

namespace RedJasmine\FilamentCore\Resources;

use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Resources\Schemas\CategoryForm;
use RedJasmine\FilamentCore\Resources\Tables\CategoryTable;

trait CategoryResource
{

    public static function form(Schema $schema) : Schema
    {
        return CategoryForm::configure($schema, static::$onlyOwner ?? false);
    }

    public static function table(Table $table) : Table
    {
        return CategoryTable::configure($table);
    }


}