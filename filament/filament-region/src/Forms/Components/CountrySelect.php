<?php

namespace RedJasmine\FilamentRegion\Forms\Components;

use Filament\Forms\Components\Select;
use RedJasmine\Region\Application\Services\Country\CountryService;

class CountrySelect extends Select
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->searchable();
        $this->preload();

        $this->options(function () {
            return $this->getCountryOptions();
        });
    }

    /**
     * 获取国家选项
     *
     * @return array
     */
    protected function getCountryOptions() : array
    {
        $locale         = app()->getLocale();
        $countryService = app(CountryService::class);
        $countries      = $countryService->all($locale);
        $options        = [];
        foreach ($countries as $country) {
            $options[$country['code']] = $country['name'];
        }
        // 按名称排序
        //asort($options);
        return $options;
    }

    /**
     * 设置默认为中国
     *
     * @return static
     */
    public function defaultChina() : static
    {
        $this->default('CN');

        return $this;
    }
}

