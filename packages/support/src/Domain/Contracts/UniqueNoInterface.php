<?php

namespace RedJasmine\Support\Domain\Contracts;

interface UniqueNoInterface
{
    public static function getUniqueNoKey() : string;

    public static function checkUniqueNo(string $no) : bool;
}