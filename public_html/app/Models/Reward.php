<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction; // Import the Transaction model

class Reward extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'min_donation',
        'discount_per_thousand',
        'voucher_code',
        'is_claimable',
    ];

    /**
     * Scope to filter claimable rewards
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClaimable($query)
    {
        return $query->where('is_claimable', true);
    }

    /**
     * Check if the reward is eligible for a user based on donation amount
     * Now checking against total donation from the transactions table
     *
     * @param float $totalDonation
     * @return bool
     */
    public function isEligible(float $userId): bool
    {
        // Get the total donation (request_amount) from the transactions table for the user
        $totalDonation = Transaction::where('user_id', $userId)->sum('request_amount');
        
        // Check if the user’s total donation is greater than or equal to the reward’s minimum donation
        return $totalDonation >= $this->min_donation;
    }

    /**
     * Generate a voucher code if needed
     *
     * @return string
     */
    public function generateVoucherCode(): string
    {
        return strtoupper(uniqid('REWARD_'));
    }

    /**
     * Relationship with UserReward (or any pivot table)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userRewards()
    {
    return $this->hasMany(UserReward::class); // This assumes the 'reward_id' is the foreign key
    }
    
     protected static function booted()
    {
        // When a reward is being deleted, delete the associated user_rewards.
        static::deleting(function ($reward) {
            $reward->userRewards()->delete();  // Delete all UserReward records related to this reward
        });
    }
public function claimedUsers()
{
    return $this->hasMany(ClaimedReward::class); // Adjust model name as needed
}
}
