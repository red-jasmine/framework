<?php

namespace RedJasmine\Order\Tests\Fixtures\Users;

use RedJasmine\Support\Contracts\UserInterface;

class Seller extends \Illuminate\Foundation\Auth\User implements UserInterface
{


    protected $fillable = [
        'id', 'type',
    ];


    public static function make(int $id, string $type = 'seller') : static
    {
        return new static([ 'id' => $id, 'type' => $type ]);
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getID() : int
    {
        return $this->getKey();
    }

    public function getNickname() : ?string
    {
        return fake()->name;
    }

    public function getAvatar() : ?string
    {
        return fake()->imageUrl;
    }


}
