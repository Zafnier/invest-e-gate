<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reward;
use App\Models\User;
use App\Models\UserReward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserRewardController;
use Illuminate\Support\Str;

class AdminRewardController extends Controller
{
    /**
     * Generate a unique voucher code
     */
    private function generateUniqueVoucherCode($length = 10)
    {
        do {
            $voucherCode = Str::upper(Str::random($length));
            $exists = Reward::where('voucher_code', $voucherCode)->exists();
        } while ($exists);

        return $voucherCode;
    }

    /**
     * Display all rewards
     */
    public function index(Request $request){
    $query = Reward::query();

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%')
            ->orWhere('description', 'like', '%' . $request->search . '%');
    }

    // Use withCount to count only claimed rewards
    $rewards = $query->select(
            'id',
            'title',
            'description',
            'min_donation',
            'discount_per_thousand',
            'voucher_code',
            'is_claimable'
        )
        ->withCount(['userRewards as claimed_count' => function ($query) {
            $query->where('is_claimed', true); // Count only claimed rewards
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('admin.sections.rewards.index', compact('rewards'));
    }

    /**
     * Show form to create a new reward
     */
    public function create()
    {
        $page_title = 'Add Reward';
        return view('admin.sections.rewards.create', compact('page_title'));
    }

    /**
     * Store a new reward in the database
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'min_donation' => 'required|numeric|min:0',
            'discount_per_thousand' => 'nullable|numeric|min:0',
        ]);

        $voucherCode = $this->generateUniqueVoucherCode();

        $reward = Reward::create([
            'title' => $request->title,
            'description' => $request->description,
            'min_donation' => $request->min_donation,
            'voucher_code' => $voucherCode,
            'discount_per_thousand' => $request->discount_per_thousand ?? 0,
            'is_claimable' => $request->has('is_claimable'),
        ]);

        $this->assignRewardToEligibleUsers($reward);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward created and assigned successfully!');
    }

    /**
     * Assign the reward to eligible users based on their donation amount
     *
     * @param Reward $reward
     */
    private function assignRewardToEligibleUsers(Reward $reward)
    {
        $eligibleUsers = User::whereHas('transactions', function ($query) use ($reward) {
            $query->havingRaw('SUM(request_amount) >= ?', [$reward->min_donation]);
        })->get();

        foreach ($eligibleUsers as $user) {
            $exists = UserReward::where('user_id', $user->id)
                ->where('reward_id', $reward->id)
                ->exists();

            if (!$exists) {
                UserReward::create([
                    'user_id' => $user->id,
                    'reward_id' => $reward->id,
                    'voucher_code' => $reward->voucher_code,
                    'is_claimed' => false,
                ]);
            }
        }
    }

    /**
     * Show the form to edit an existing reward
     */
    public function edit($id)
    {
        $reward = Reward::findOrFail($id);
        return view('admin.sections.rewards.edit', compact('reward'));
    }

    /**
     * Update a specific reward and reassign it to eligible users if needed
     */
    public function update(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'min_donation' => 'required|numeric|min:0',
            'discount_per_thousand' => 'nullable|numeric|min:0',
        ]);

        $voucherCode = $reward->voucher_code;

        if ($request->has('generate_new_voucher') && $request->generate_new_voucher) {
            $voucherCode = $this->generateUniqueVoucherCode();
        }

        $reward->update([
            'title' => $request->title,
            'description' => $request->description,
            'min_donation' => $request->min_donation,
            'voucher_code' => $voucherCode,
            'discount_per_thousand' => $request->discount_per_thousand ?? 0,
            'is_claimable' => $request->has('is_claimable'),
        ]);

        $this->assignRewardToEligibleUsers($reward);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward updated and reassigned to eligible users!');
    }

    /**
     * Delete a specific reward
     */
    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->delete();

        return redirect()->route('admin.rewards.index')->with('success', 'Reward deleted successfully!');
    }
}
