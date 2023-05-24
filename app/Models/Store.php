<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['uuid', 'user_id', 'store_name', 'store_marketplace', 'store_type', 'store_config_id', 'is_sqs_registered', 'currency_code', 'status', 'aws_region', 'is_master_store', 'max_quantity_post', 'created_by', 'updated_by'];
    public function storeCredentials()
    {
        return $this->hasOne(StoreCredential::class, 'store_id', 'id');
    }
    public function getCustomer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', config('params.active'));
    }

    public function storeConfig()
    {
        return $this->hasOne(StoreConfig::class, 'store_type', 'store_type');
    }
}