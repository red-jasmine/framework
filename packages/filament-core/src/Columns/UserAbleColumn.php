<?php

namespace RedJasmine\FilamentCore\Columns;

use Filament\Tables\Columns\Column;

class UserAbleColumn extends Column
{

    protected false $hiddenNickname = false;

    public string $nickname = 'nickname';

    public function getNickname() : string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname) : static
    {
        $this->nickname = $nickname;
        return $this;
    }

    public function getHiddenNickname() : false
    {
        return $this->hiddenNickname;
    }

    public function hiddenNickname(bool $hiddenNickname = true) : static
    {
        $this->hiddenNickname = $hiddenNickname;
        return $this;
    }


    protected string $view = 'red-jasmine-filament-core::columns.user-able';


}
