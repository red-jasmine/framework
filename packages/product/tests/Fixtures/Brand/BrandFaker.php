<?php

namespace RedJasmine\Product\Tests\Fixtures\Brand;

use Illuminate\Support\Str;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;

class BrandFaker
{

    public function data() : array
    {
        return [
            'parent_id'    => 0,
            'sort'         => fake()->numberBetween(0, 10000000),
            'name'         => fake()->name,
            'english_name' => fake('en')->name,
            'logo'         => fake()->imageUrl(200, 200),
            'initial'      => Str::upper(fake()->randomLetter()),
            'status'       => fake()->randomElement(BrandStatusEnum::values()),
            'expands'      => null,
            'is_show'      => 1,
        ];
    }

    public function command() : BrandCreateCommand
    {
        return BrandCreateCommand::from($this->data());
    }

}
