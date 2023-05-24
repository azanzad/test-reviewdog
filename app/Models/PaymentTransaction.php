<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;
    protected $table = 'payment_transactions';
    protected $fillable = [
        'user_id', 'plan_id', 'customer_id', 'plan_interval', 'amount', 'is_paid', 'currency', 'card_token', 'transaction_status', 'transaction_id', 'transaction_date',
    ];

    public function getPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id', 'id');
    }

    public function getCustomer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}