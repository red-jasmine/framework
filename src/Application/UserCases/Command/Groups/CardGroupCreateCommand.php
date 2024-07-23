<?php

namespace RedJasmine\Card\Application\UserCases\Command\Groups;


use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Contracts\UserInterface;

class CardGroupCreateCommand extends Command
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
