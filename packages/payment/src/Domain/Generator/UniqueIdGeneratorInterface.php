<?php

namespace RedJasmine\Payment\Domain\Generator;

interface UniqueIdGeneratorInterface
{
    public function generator(array $factors = []): string;

    public function parse(string $UniqueId) : array;

}
