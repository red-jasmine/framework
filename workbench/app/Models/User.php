<?php

namespace Workbench\App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use RedJasmine\Support\Domain\Contracts\UserInterface;

//use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements UserInterface
{
    public function getUserData() : array
    {
        return [
            'id'       => $this->getID(),
            'nickname' => $this->getNickname(),
            'avatar'   => $this->getAvatar(),
            'type'     => $this->getType(),
        ];
    }


    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function getType() : string
    {
        return 'user';
    }

    public function getID() : string
    {
        return $this->getKey();
    }

    public function getNickname() : ?string
    {
        return $this->name;
    }

    public function getAvatar() : ?string
    {
        return $this->avatar;
    }
}
