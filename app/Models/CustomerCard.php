<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerCard extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'companyid', 'uuid', 'brand', 'last_number', 'stripe_cardid', 'stripe_customerid', 'stripe_token', 'status', 'cardholder_name', 'is_primary',
    ];
}