<?php

namespace App\Models;

use App\Enums\FriendshipStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id_small
 * @property int $user_id_big
 * @property FriendshipStatusEnum $status
 * @property int $requested_by
 * @property \Illuminate\Support\Carbon|null $accepted_at
 * @property int|null $blocked_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $blockedBy
 * @property-read \App\Models\User $requestedBy
 * @property-read \App\Models\User $userBig
 * @property-read \App\Models\User $userSmall
 *
 * @method static Builder<static>|Friendship pair(int $a, int $b)
 * @method static \Database\Factories\FriendshipFactory factory($count = null, $state = [])
 * @method static Builder<static>|Friendship newModelQuery()
 * @method static Builder<static>|Friendship newQuery()
 * @method static Builder<static>|Friendship query()
 * @method static Builder<static>|Friendship whereAcceptedAt($value)
 * @method static Builder<static>|Friendship whereBlockedBy($value)
 * @method static Builder<static>|Friendship whereCreatedAt($value)
 * @method static Builder<static>|Friendship whereId($value)
 * @method static Builder<static>|Friendship whereRequestedBy($value)
 * @method static Builder<static>|Friendship whereStatus($value)
 * @method static Builder<static>|Friendship whereUpdatedAt($value)
 * @method static Builder<static>|Friendship whereUserIdBig($value)
 * @method static Builder<static>|Friendship whereUserIdSmall($value)
 *
 * @mixin \Eloquent
 */
class Friendship extends Model
{
    /** @use HasFactory<\Database\Factories\FriendshipFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id_small',
        'user_id_big',
        'status',
        'requested_by',
        'accepted_at',
    ];

    #[\Override]
    public function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'status' => FriendshipStatusEnum::class,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function userSmall(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_small');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function userBig(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_big');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function blockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $builder
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopePair(Builder $builder, int $userIdSmall, int $userIdBig): Builder
    {
        return $builder->where('user_id_small', $userIdSmall)->where('user_id_big', $userIdBig);
    }
}
