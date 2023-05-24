<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\CompanyContact;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use ProtoneMedia\LaravelVerifyNewEmail\MustVerifyNewEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable, SoftDeletes, MustVerifyNewEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['uuid', 'companyid', 'planid', 'plan_price', 'name', 'email', 'customer_type', 'role', 'status', 'brand_name', 'contact_number', 'company_description', 'website', 'is_trial', 'trial_days', 'profile_image', 'created_by', 'updated_by', 'deleted_by', 'deleted_at', 'password', 'billing_day',
        'next_billing_date', 'currency', 'activated_date', 'is_plan_active', 'current_paid_status', 'cancelled_date', 'subscription_expired', 'cardid', 'card_token', 'subscription_id', 'subscription_item_id', 'is_first_login', 'is_db_created','country_id','timezone'
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
    ];
    /**
     * getPlan function
     *
     * @return void
     */
    public function getPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'planid', 'id');
    }
    /**
     * Get all contacts of company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(CompanyContact::class, 'companyid');
    }
    /**
     * Get all customers of company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
        return $this->hasMany(User::class, 'companyid', 'id');
    }
    /**
     * getCompany function
     *
     * @return void
     */
    public function getCompany()
    {
        return $this->belongsTo(User::class, 'companyid', 'id');
    }
    /**
     * Get all store of users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function stores()
    {
        return $this->hasMany(Store::class, 'user_id', 'id');
    }
    public function getEmailAutomations()
    {
        return $this->hasMany(UserEmailAutomation::class, 'user_id');
    }
    public function getOrders()
    {
        return $this->hasMany(AmazonOrder::class, 'user_id');
    }
    /**delete contacts of company */
    public static function deleteContacts($companyid, $contact_ids)
    {
        return CompanyContact::whereNotIn('id', $contact_ids)->where('companyid', $companyid)->delete();
    }

}
