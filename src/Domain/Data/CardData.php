<?php

namespace RedJasmine\Card\Domain\Data;

use RedJasmine\Card\Domain\Enums\CardStatus;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class CardData extends Data
{

    public UserInterface $owner;

    public int $groupId = 0;

    public string $content;

    public CardStatus $status = CardStatus::ENABLE;

    public ?string $remarks;


}
