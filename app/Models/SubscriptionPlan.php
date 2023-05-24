<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "subcription_plans";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userid', 'uuid', 'stripe_planid', 'stripe_priceid', 'interval_count', 'name', 'amount', 'interval', 'plan_type', 'currency', 'status', 'trial_days', 'created_by', 'updated_by', 'deleted_by', 'deleted_at','annual_sales_to','annual_sales_from'
    ];
    /**
     * get companies to used this plan
     *
     * @return void
     */
    public function getCompanies()
    {
        return $this->hasMany(User::class, 'planid');
    }
    public function getParentCompany()
    {
        return $this->hasMany(User::class, 'planid')->whereNull('customer_type')->where('status', config('params.active'));

    }
    public function getCustomer()
    {
        return $this->hasMany(User::class, 'planid')->whereIn('customer_type', [config('params.individual_brand'), config('params.parent_company')])->where('status', config('params.active'));

    }
}
