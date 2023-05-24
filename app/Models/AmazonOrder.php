<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmazonOrder extends Model
{
    use HasFactory;
    protected $fillable = ['amazon_order_id', 'user_id', 'seller_order_id', 'store_id', 'items_count', 'order_date', 'purchase_date', 'last_updated_date', 'order_status', 'fulfillment_channel', 'order_type', 'sales_channel', 'order_channel', 'ship_service_level', 'shipping_service_level_category', 'shipping_label_cba', 'shipping_address_name', 'shipping_address_line1', 'shipping_address_line2', 'shipping_address_line3', 'shipping_address_city', 'shipping_address_county', 'shipping_address_district', 'shipping_address_state', 'shipping_address_zipcode', 'shipping_address_country', 'shipping_address_phone', 'items_shipped', 'items_unshipped', 'order_total', 'order_currency', 'payment_method', 'buyer_name', 'buyer_email', 'ship_date_earliest', 'ship_date_latest', 'delivery_date_earliest', 'delivery_date_latest', 'buyer_po_number', 'is_prime_order', 'is_business_order', 'is_premium_order', 'processed', 'is_acknowledge', 'is_settled', 'updated', 'is_request_sent', 'request_sent_date'];

    public function amazonOrderItems()
    {
        return $this->hasMany(AmazonOrderItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**fetch the date & time of latest order we have*/
    public static function getLatestOrderDatetime($storeId, $userId = null)
    {
        if (!empty($storeId)) {
            $dbname = config('params.user_db_name').'_'.$userId.'.';
            return DB::table($dbname.'amazon_orders')->where('store_id', $storeId)
                ->max('last_updated_date');
        }

        return [];
    }
    /**check if order already exists */
    public static function orderExists($storeId, $orderId, $userId = null)
    {
        if (!empty($storeId) && !empty($orderId)) {
            $dbname = config('params.user_db_name').'_'.$userId.'.';
            return DB::table($dbname.'amazon_orders')->where('store_id', $storeId)
                ->where('amazon_order_id', $orderId)
                ->first();
        }

        return [];
    }
    /**fetch the list of orders for which we need to fetch list of items */
    public static function pendingOrders($storeId, $userId = null)
    {
        // Get all pending orders having processed flag 0 or 2
        $dbname = config('params.user_db_name').'_'.$userId.'.';
        return DB::table($dbname.'amazon_orders')->select('id', 'amazon_order_id', 'order_status')
            ->where('store_id', !empty($storeId) ? $storeId : 0)
            ->whereIn('processed', array('0', '2'))
            ->limit(15)
            ->get();
    }
    /**get order for update */
    public static function orderForUpdate($storeId, $orderId, $userId = null)
    {
        if (!empty($storeId) && !empty($orderId)) {
            $dbname = config('params.user_db_name').'_'.$userId.'.';
            return DB::table($dbname.'amazon_orders')->where('store_id', $storeId)
                ->where('amazon_order_id', $orderId);
        }

        return [];
    }
    public function getCustomer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}