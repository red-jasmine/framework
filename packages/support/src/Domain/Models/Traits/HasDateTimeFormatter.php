<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use DateTimeInterface;

trait HasDateTimeFormatter
{

    protected function serializeDate(DateTimeInterface $date) : string
    {
        return $date->format($this->getDateFormat());
    }

}
