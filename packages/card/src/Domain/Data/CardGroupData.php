<?php

namespace RedJasmine\Card\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class CardGroupData extends Data
{

    public UserInterface $owner;

    public string $name;

    public ?string $remarks;

    /**
     * 内容模板
     * @var string|null
     */
    public ?string $contentTemplate;
}
