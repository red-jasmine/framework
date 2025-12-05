<?php

namespace RedJasmine\Invitation\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeStatusEnum;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeTypeEnum;
use RedJasmine\Invitation\Domain\Models\ValueObjects\InvitationCodeConfig;
use RedJasmine\Invitation\Exceptions\InvitationException;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 邀请码领域模型
 *
 * @property int $id
 * @property string $code
 * @property InvitationCodeTypeEnum $code_type
 * @property InvitationCodeStatusEnum $status
 * @property int $max_usage
 * @property int $used_count
 * @property Carbon|null $expired_at
 * @property array|null $extra
 * @property string|null $description
 */
class InvitationCode extends Model implements OwnerInterface, OperatorInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use HasDateTimeFormatter;


    public $incrementing = false;

    protected $fillable = [
        'code',
        'code_type',
        'status',
        'max_usage',
        'used_count',
        'expired_at',
        'extra_data',
        'description',
    ];

    /**
     * 类型转换配置
     */
    protected function casts() : array
    {
        return [
            'code_type'  => InvitationCodeTypeEnum::class,
            'status'     => InvitationCodeStatusEnum::class,
            'max_usage'  => 'integer',
            'used_count' => 'integer',
            'expired_at' => 'datetime',
            'extra_data' => 'array',
        ];
    }

    /**
     * 模型初始化
     */
    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->status     = InvitationCodeStatusEnum::ACTIVE;
            $instance->max_usage  = 0;
            $instance->used_count = 0;
            $instance->setUniqueIds();
        }

        return $instance;
    }

    /**
     * 生命周期钩子
     */
    protected static function boot() : void
    {
        parent::boot();

        // 保存前验证
        static::saving(function (InvitationCode $invitationCode) {
            // 验证邀请码唯一性
            if ($invitationCode->isDirty('code')) {
                $exists = static::where('code', $invitationCode->code)
                                ->where('id', '!=', $invitationCode->id)
                                ->exists();

                if ($exists) {
                    throw new InvitationException('邀请码已存在');
                }
            }

            // 更新状态
            $invitationCode->updateStatus();
        });
    }

    /**
     * 关联：邀请记录
     */
    public function records() : HasMany
    {
        return $this->hasMany(InvitationRecord::class, 'invitation_code_id');
    }

    /**
     * 查询作用域：激活状态
     */
    public function scopeActive(Builder $query) : Builder
    {
        return $query->where('status', InvitationCodeStatusEnum::ACTIVE);
    }

    /**
     * 查询作用域：未过期
     */
    public function scopeNotExpired(Builder $query) : Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('expired_at')
              ->orWhere('expired_at', '>', now());
        });
    }

    /**
     * 查询作用域：可用
     */
    public function scopeAvailable(Builder $query) : Builder
    {
        return $query->active()->notExpired();
    }

    /**
     * 使用邀请码
     */
    public function use(
        UserInterface $invitee,
        ?array $context = null,
        ?string $targetUrl = null,
        ?string $targetType = null
    ) : InvitationRecord {
        if (!$this->canUse()) {
            throw new InvitationException('邀请码不可使用');
        }

        // 增加使用次数

        $this->used_count = $this->used_count + 1;

        // 检查是否用尽
        if ($this->max_usage > 0 && $this->used_count >= $this->max_usage) {
            $this->status = InvitationCodeStatusEnum::EXHAUSTED;

        }

        // 创建邀请记录
        $record                     = new InvitationRecord();
        $record->invitation_code_id = $this->id;
        $record->invitation_code    = $this->code;
        $record->invitee            = $invitee;
        $record->context            = $context;
        $record->target_url         = $targetUrl;
        $record->target_type        = $targetType;
        $record->invited_at         = Carbon::now();
        $this->setRelation('records', Collection::make());
        $this->records->add($record);

        return $record;
    }

    /**
     * 生成邀请链接
     */
    public function generateInvitationUrl(string $targetUrl, ?string $targetType = null) : string
    {
        $config  = config('invitation.link');
        $baseUrl = rtrim($config['base_url'], '/');
        $path    = trim($config['path'], '/');

        $params = [
            'code'   => $this->code,
            'target' => $targetUrl,
        ];

        if ($targetType) {
            $params['type'] = $targetType;
        }

        // 添加时间戳
        $params['t'] = time();

        // 生成签名
        if ($config['enable_signature'] ?? true) {
            $params['sig'] = $this->generateSignature($params);
        }

        $queryString = http_build_query($params);

        return "{$baseUrl}/{$path}?{$queryString}";
    }

    /**
     * 检查邀请码是否可用
     */
    public function canUse() : bool
    {
        // 检查状态
        if ($this->status !== InvitationCodeStatusEnum::ACTIVE) {
            return false;
        }

        // 检查过期时间
        if ($this->expired_at && $this->expired_at->isPast()) {
            return false;
        }

        // 检查使用次数
        if ($this->max_usage > 0 && $this->used_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    /**
     * 更新状态
     */
    public function updateStatus() : void
    {
        // 检查是否过期
        if ($this->expired_at && $this->expired_at->isPast()) {
            $this->status = InvitationCodeStatusEnum::EXPIRED;
            return;
        }

        // 检查是否用尽
        if ($this->max_usage > 0 && $this->used_count >= $this->max_usage) {
            $this->status = InvitationCodeStatusEnum::EXHAUSTED;
            return;
        }

        // 如果之前是过期或用尽状态，但现在条件满足，则重新激活
        if ($this->status->isFinalStatus() && $this->canUse()) {
            $this->status = InvitationCodeStatusEnum::ACTIVE;
        }
    }

    /**
     * 获取剩余使用次数
     */
    public function getRemainingUsage() : int
    {
        if ($this->max_usage <= 0) {
            return -1; // 无限制
        }

        return max(0, $this->max_usage - $this->used_count);
    }

    /**
     * 检查是否无限制使用
     */
    public function isUnlimited() : bool
    {
        return $this->max_usage <= 0;
    }

    /**
     * 检查是否永久有效
     */
    public function isPermanent() : bool
    {
        return $this->expired_at === null;
    }

    /**
     * 生成系统邀请码
     */
    public static function generateSystemCode() : string
    {
        $config     = config('invitation.code');
        $length     = $config['length'] ?? 8;
        $characters = $config['characters'] ?? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $prefix     = $config['prefix'] ?? '';
        $suffix     = $config['suffix'] ?? '';

        do {
            $code = $prefix.Str::random($length, $characters).$suffix;
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * 根据配置创建邀请码
     */
    public static function createFromConfig(InvitationCodeConfig $config, UserInterface $inviter, ?UserInterface $operator = null) : static
    {
        $invitationCode              = new static();
        $invitationCode->code_type   = $config->codeType;
        $invitationCode->max_usage   = $config->maxUsage;
        $invitationCode->expired_at  = $config->expiredAt;
        $invitationCode->extra_data  = $config->extraData;
        $invitationCode->description = $config->description;
        $invitationCode->inviter     = $inviter;

        if ($operator) {
            $invitationCode->operator = $operator;
        }

        // 生成或设置邀请码
        if ($config->isCustom()) {
            $invitationCode->code = $config->customCode;
        } else {
            $invitationCode->code = static::generateSystemCode();
        }

        return $invitationCode;
    }

    /**
     * 生成签名
     */
    protected function generateSignature(array $params) : string
    {
        $signatureKey = config('invitation.link.signature_key', config('app.key'));

        // 移除签名参数
        unset($params['sig']);

        // 排序参数
        ksort($params);

        // 生成签名字符串
        $signString = http_build_query($params).$signatureKey;

        return hash('sha256', $signString);
    }

    /**
     * 验证签名
     */
    public function validateSignature(array $params) : bool
    {
        if (!isset($params['sig'])) {
            return false;
        }

        $signature         = $params['sig'];
        $expectedSignature = $this->generateSignature($params);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * 是否已过期
     */
    public function isExpired() : bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    /**
     * 是否已用尽
     */
    public function isExhausted() : bool
    {
        return $this->max_usage > 0 && $this->used_count >= $this->max_usage;
    }

    /**
     * 获取使用率
     */
    public function getUsageRate() : float
    {
        if ($this->max_usage <= 0) {
            return 0.0;
        }

        return round(($this->used_count / $this->max_usage) * 100, 2);
    }

    /**
     * 禁用邀请码
     */
    public function disable() : void
    {
        $this->status = InvitationCodeStatusEnum::DISABLED;
    }

    /**
     * 启用邀请码
     */
    public function enable() : void
    {
        if ($this->isExpired()) {
            $this->status = InvitationCodeStatusEnum::EXPIRED;
        } elseif ($this->isExhausted()) {
            $this->status = InvitationCodeStatusEnum::EXHAUSTED;
        } else {
            $this->status = InvitationCodeStatusEnum::ACTIVE;
        }
    }
} 