<?php

namespace App\Models;

use App\Constants\GlobalConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $appends = ['fullname','userImage','stringStatus','lastLogin','kycStringStatus'];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ["id"];

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
        'firstname'           => 'string',
        'lastname'            => 'string',
        'username'            => 'string',
        'email'               => 'string',
        'mobile_code'         => 'string',
        'mobile'              => 'string',
        'full_mobile'         => 'string',
        'password'            => 'string',
        'refferal_user_id'    => 'integer',
        'image'               => 'string',
        'status'              => 'integer',
        'email_verified_at'   => 'datetime',
        'address'             => 'object',
        'email_verified'      => 'integer',
        'sms_verified'        => 'integer',
        'kyc_verified'        => 'integer',
        'ver_code'            => 'integer',
        'ver_code_send_at'    => 'datetime',
        'two_factor_verified' => 'integer',
        'device_id'           => 'string',
        'social_type'         => 'string',
        'remember_token'      => 'string',
        'deleted_at'          => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    public function scopeEmailUnverified($query)
    {
        return $query->where('email_verified', false);
    }

    public function scopeEmailVerified($query) {
        return $query->where("email_verified",true);
    }

    public function scopeKycVerified($query) {
        return $query->where("kyc_verified",GlobalConst::VERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->whereNot('kyc_verified',GlobalConst::VERIFIED);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', false);
    }

    public function kyc()
    {
        return $this->hasOne(UserKycData::class);
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function wallets()
    {
        return $this->hasMany(UserWallet::class);
    }

    public function getUserImageAttribute() {
        $image = $this->image;

        if($image == null) {
            return files_asset_path('profile-default');
        }else if(filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }else {
            return files_asset_path("user-profile") . "/" . $image;
        }
    }

    public function passwordResets() {
        return $this->hasMany(UserPasswordReset::class,"user_id");
    }

    public function scopeGetSocial($query,$credentials) {
        return $query->where("email",$credentials);
    }

    public function getStringStatusAttribute() {
        $status = $this->status;
        $data = [
            'class' => "",
            'value' => "",
        ];
        if($status == GlobalConst::ACTIVE) {
            $data = [
                'class'     => "badge badge--success",
                'value'     => __("Active"),
            ];
        }else if($status == GlobalConst::BANNED) {
            $data = [
                'class'     => "badge badge--danger",
                'value'     => __("Banned"),
            ];
        }
        return (object) $data;
    }

    public function getStringEmailVerifiedStatusAttribute() {
        $email_verified = $this->email_verified;
        $data = [
            'class' => "",
            'value' => "",
        ];
        if($email_verified == GlobalConst::ACTIVE) {
            $data = [
                'class'     => "badge badge--success",
                'value'     => __("Verified"),
            ];
        }else if($email_verified == GlobalConst::BANNED) {
            $data = [
                'class'     => "badge badge--danger",
                'value'     => __("Unverified"),
            ];
        }
        return (object) $data;
    }



    public function getKycStringStatusAttribute() {
        $status = $this->kyc_verified;
        $data = [
            'class' => "",
            'value' => "",
        ];
        if($status == GlobalConst::APPROVED) {
            $data = [
                'class'     => "badge badge--success",
                'value'     => "Verified",
            ];
        }else if($status == GlobalConst::PENDING) {
            $data = [
                'class'     => "badge badge--warning",
                'value'     => "Pending",
            ];
        }else if($status == GlobalConst::REJECTED) {
            $data = [
                'class'     => "badge badge--danger",
                'value'     => "Rejected",
            ];
        }else {
            $data = [
                'class'     => "badge badge--danger",
                'value'     => "Unverified",
            ];
        }
        return (object) $data;
    }

    public function loginLogs(){
        return $this->hasMany(UserLoginLog::class);
    }

    public function getLastLoginAttribute() {
        if($this->loginLogs()->count() > 0) {
            return $this->loginLogs()->get()->last()->created_at->format("H:i A, d M Y");
        }

        return "N/A";
    }

    public function scopeSearch($query,$data) {
        return $query->where(function($q) use ($data) {
            $q->where("username","like","%".$data."%");
        })->orWhere("email","like","%".$data."%")->orWhere("full_mobile","like","%".$data."%");
    }

    public function modelGuardName() {
        return "web";
    }


 // Define the rewards relationship through the user_rewards table
public function rewards()
{
    return $this->belongsToMany(Reward::class, 'user_rewards')
                ->withPivot('voucher_code', 'is_claimed', 'claimed_at')
                ->withTimestamps();
}

// Define the transactions relationship to get donation records
public function transactions()
{
    return $this->hasMany(Transaction::class);
}

public function totalDonation()
{
    // Calculate the total donation amount for this user
    return $this->transactions()->sum('request_amount'); // Ensure 'amount' is the correct column in your transactions table
}


// Get eligible rewards based on the user's total donation
public function eligibleRewards()
{
    $totalDonation = $this->totalDonation();

    // Fetch rewards where the minimum donation is less than or equal to total donation
    return Reward::where('min_donation', '<=', $totalDonation)->get();
}

// Check if a specific reward has already been earned by the user
public function hasReward($rewardId)
{
    return $this->rewards()->where('reward_id', $rewardId)->exists();
}

// Method to assign eligible rewards to the user
public function assignEligibleRewards()
{
    $eligibleRewards = $this->eligibleRewards();

    foreach ($eligibleRewards as $reward) {
        if (!$this->hasReward($reward->id)) {
            UserReward::create([
                'user_id' => $this->id,
                'reward_id' => $reward->id,
                'voucher_code' => $reward->voucher_code,
                'is_claimed' => false,
            ]);
        }
    }
}

}