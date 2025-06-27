<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterUnboundUserTypeEnum;
use RedJasmine\Distribution\Domain\Services\DistributionConfigService;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\TimeConfigData;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 推广员绑定用户
 * @property PromoterBindUserStatusEnum $status
 */
class PromoterBindUser extends Model implements OperatorInterface
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;


    protected $fillable = [
        'user_type',
        'user_id',
        'promoter_id',
        'status',
        'bound_time',
        'protection_time',
        'expiration_time',
        'unbound_type',
        'unbound_time'
    ];

    protected function casts() : array
    {
        return [
            'status'          => PromoterBindUserStatusEnum::class,
            'bound_time'      => 'datetime',
            'activation_time' => 'datetime',
            'expiration_time' => 'datetime',
            'protection_time' => 'datetime',
            'unbound_time'    => 'datetime'
        ];
    }


    public function promoter() : BelongsTo
    {
        return $this->belongsTo(Promoter::class, 'id', 'promoter_id');
    }


    public function scopeOnlyPromoter(Builder $builder, Promoter $promoter)
    {
        return $builder->where('promoter_id', $promoter->id);
    }

    public function scopeBound(Builder $builder)
    {
        return $builder->where('status', PromoterBindUserStatusEnum::BOUND);
    }

    public function user() : Attribute
    {
        return Attribute::make(
            get: fn() => ($this->user_type && $this->user_id) ? UserData::from([
                'type' => $this->user_type,
                'id'   => $this->user_id,
            ]) : null,
            set: fn(?UserInterface $user = null) => [
                'user_type' => $user?->getType(),
                'user_id'   => $user?->getID(),
            ],
        );
    }


    /**
     * 是否 超过保护器
     * @return bool
     */
    public function isOverProtection() : bool
    {
        return now() > $this->protection_time;
    }

    /**
     * 是否过期
     * @return bool
     */
    public function isExpired() : bool
    {
        return now() > $this->expiration_time;
    }

    /**
     * 是否属于当前分销员
     *
     * @param  Promoter  $promoter
     *
     * @return bool
     */
    public function isBelongsToPromoter(Promoter $promoter) : bool
    {
        return $this->promoter_id === $promoter->id;
    }


    /**
     * 邀请中用户
     *
     * @param  Promoter  $promoter
     * @param  UserInterface  $user
     *
     * @return void
     */
    public function setInviting(Promoter $promoter, UserInterface $user) : void
    {
        $this->promoter_id = $promoter->id;
        $this->user        = $user;
        // 设置绑定状态、 过期时间、保护时间
        $this->status     = PromoterBindUserStatusEnum::INVITING;
        $this->bound_time = $this->bound_time ?? Carbon::now();
        $activationTime   = $this->activation_time = Carbon::now();

        $distributionConfigService = app(DistributionConfigService::class);

        $this->expiration_time = $this->protection_time =
            $distributionConfigService->getCompeteUserOrderLimitTimeConfig()
                                      ->afterAt($activationTime);

        $this->unbound_time = null;
        $this->unbound_type = null;

        $this->fireModelEvent('inviting', false);
    }

    public function setBound(Promoter $promoter, UserInterface $user) : void
    {
        $this->promoter_id = $promoter->id;
        $this->user        = $user;
        // 设置绑定状态、 过期时间、保护时间
        $this->status     = PromoterBindUserStatusEnum::BOUND;
        $this->bound_time = $this->bound_time ?? Carbon::now();
        $activationTime   = $this->activation_time = Carbon::now();

        $distributionConfigService = app(DistributionConfigService::class);

        $this->protection_time = $distributionConfigService
            ->getProtectionTimeConfig()
            ->afterAt($activationTime);

        $this->expiration_time = $distributionConfigService
            ->getExpirationTimeConfig()
            ->afterAt($activationTime);

        $this->unbound_time = null;
        $this->unbound_type = null;

        $this->fireModelEvent('bound', false);

    }

    public function setUnbound(PromoterUnboundUserTypeEnum $unboundUserType) : void
    {
        $this->status       = PromoterBindUserStatusEnum::UNBOUND;
        $this->unbound_time = Carbon::now();
        $this->unbound_type = $unboundUserType;


        $this->fireModelEvent('unbound', false);
    }


    /**
     * 重新激活
     * @return void
     */
    public function activation() : void
    {
        $distributionConfigService = app(DistributionConfigService::class);
        $activationTime            = $this->activation_time = Carbon::now();

        if ($this->status === PromoterBindUserStatusEnum::INVITING) {
            $this->expiration_time = $this->protection_time =
                $distributionConfigService->getCompeteUserOrderLimitTimeConfig()
                                          ->afterAt($activationTime);
        }

        if ($this->status === PromoterBindUserStatusEnum::BOUND) {
            $this->protection_time = $distributionConfigService
                ->getProtectionTimeConfig()
                ->afterAt($activationTime);

            $this->expiration_time = $distributionConfigService
                ->getExpirationTimeConfig()
                ->afterAt($activationTime);
        }


        $this->fireModelEvent('activation', false);

    }


}