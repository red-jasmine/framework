<?php

namespace RedJasmine\FilamentCore\Filters;

class DateRangeFilter extends \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter
{

    protected function setUp() : void
    {
        parent::setUp(); 

        $this->withIndicator()
             ->alwaysShowCalendar()
             ->timePickerSecond()
             ->displayFormat('YYYY/MM/DD')
             ->format('Y/m/d')
             ->timePicker24()
             ->icon('heroicon-o-backspace')
             ->linkedCalendars()
             ->autoApply();
    }

}
