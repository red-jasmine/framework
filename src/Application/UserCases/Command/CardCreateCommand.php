<?php

namespace RedJasmine\Card\Application\UserCases\Command;


use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\Support\Application\Command;
use RedJasmine\Support\Contracts\UserInterface;

class CardCreateCommand extends Command
{

    public UserInterface $owner;

    public int $groupId = 0;

    public string $content;

    public ?string $remarks;

    public CardStatus $status = CardStatus::UNSOLD;


}
