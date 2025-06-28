<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reward_id',
        'voucher_code',
        'is_claimed',
        'claimed_at',
    ];

 /**
     * Cast dates to Carbon instances.
     */
    protected $casts = [
        'claimed_at' => 'datetime', // This ensures claimed_at is treated as a Carbon instance
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Define the relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship to the Reward model.
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    /**
     * Check if the reward can be claimed.
     *
     * @return bool
     */
    public function canBeClaimed(): bool
    {
        return !$this->is_claimed && $this->user->totalDonation() >= $this->reward->min_donation;
    }

    /**
     * Mark the reward as claimed.
     *
     * @return bool
     */
    public function claimReward(): bool
    {
        if ($this->canBeClaimed()) {
            $this->update([
                'is_claimed' => true,
                'claimed_at' => now(),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Scope to get unclaimed rewards for a user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnclaimedForUser($query, int $userId)
    {
        return $query->where('user_id', $userId)
                     ->where('is_claimed', false);
    }

    /**
     * Scope to get claimed rewards for a user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClaimedForUser($query, int $userId)
    {
        return $query->where('user_id', $userId)
                     ->where('is_claimed', true);
    }

    /**
     * Show reward details with associated user and reward data.
     *
     * @param int $id
     * @return array
     */
    public function getRewardDetails(int $id)
    {
        $reward = $this->with(['reward', 'user'])->findOrFail($id);

        return [
            'reward' => $reward,
            'user' => $reward->user,
        ];
    }
}
