<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCredential extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['store_id', 'uuid', 'merchant_id', 'mws_auth_token', 'instance_id', 'refresh_token', 'access_token', 'mws_access_key_id', 'mws_secret_key', 'aws_access_key_id', 'aws_secret_key', 'amazon_aws_region', 'sqs_query_url', 'is_fetch_order', 'order_fetching_start_date', 'return_order_fetch_date', 'seller_shipment_start_date', 'is_return_order_fetched', 'is_production', 'created_by', 'updated_by', 'deleted_by', 'deleted_at', 'password'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    /*
    @Description    : Function to get the store specefic configurations
    @Input          : store_id
    @Output         : Object of store credentials and config
     */
    public static function getStoreConfig($storeId)
    {
        // If store id is not empty
        if (!empty($storeId)) {
            // Set credentials and config data for store id
            return self::where('store_id', $storeId)
                ->with(['store' => function ($query) {
                    $query->select('id', 'store_type', 'user_id', 'store_name', 'currency_code', 'store_config_id')
                        ->where('status', config('params.active'))->with('storeConfig');
                }])
                ->whereHas('store', function ($query) {
                    $query->where('status', config('params.active'));
                })->first();
        }

        return [];
    }
}