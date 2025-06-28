<?php

namespace App\Http\Controllers\User;

use App\Models\Reward;
use App\Models\UserReward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminRewardController;

class UserRewardController extends Controller
{
    /**
     * Display the user's rewards dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
{
    $user = Auth::user();

    $rewards = UserReward::with('reward')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('user.sections.rewards.index', compact('rewards'));
}

    /**
     * Claim a reward by voucher code.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function claim(Request $request)
    {
        // Validate the request input
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        // Find the reward by voucher code and ensure it's not already claimed
        $userReward = UserReward::with('reward')
            ->whereHas('reward', function ($query) use ($request) {
                $query->where('voucher_code', $request->input('voucher_code')); // Fetch voucher code from rewards
            })
            ->where('user_id', Auth::id())
            ->where('is_claimed', false)
            ->first();

        // Check if the reward exists and is eligible for claiming
        if (!$userReward) {
            return redirect()->back()->with('error', 'Invalid or already claimed voucher.');
        }

        // Mark the reward as claimed
        $userReward->update([
            'is_claimed' => true,
            'claimed_at' => now(),
        ]);

       return redirect()->back()->with('success', 'Reward Claimed Successfully.');
    }

    /**
     * Assign eligible rewards to the user based on their donation amount.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRewards()
    {
        $user = Auth::user();

        // Fetch all rewards where the minimum donation is less than or equal to the user's total donations
        $eligibleRewards = Reward::where('min_donation', '<=', $user->totalDonation())->get();

        foreach ($eligibleRewards as $reward) {
            // Check if the reward is already assigned
            $exists = UserReward::where('user_id', $user->id)
                ->where('reward_id', $reward->id)
                ->exists();

            if (!$exists) {
                // Generate a unique voucher code
                $voucherCode = $reward->voucher_code;

                // Assign the reward to the user
                UserReward::create([
                    'user_id' => $user->id,
                    'reward_id' => $reward->id,
                    'voucher_code' => $voucherCode,
                    'is_claimed' => false,
                    'discount_amount' => $reward->discount_per_thousand, // Assign discount
                ]);
            }
        }

        return redirect()->back()->with('success', 'Eligible rewards have been assigned.');
    }
}