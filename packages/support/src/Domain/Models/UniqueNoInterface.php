<?php

namespace RedJasmine\Support\Domain\Models;

interface UniqueNoInterface
{
    public static function getUniqueNoKey() : string;

    public static function checkUniqueNo(string $no) : bool;
}