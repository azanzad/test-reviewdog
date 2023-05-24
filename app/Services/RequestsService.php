<?php

namespace App\Services;

use App\Models\AmazonOrder;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RequestsService
{
    /**
     * getData function
     *
     * @param Request $request
     * @return object
     */
    public function getData(Request $request)
    {
        $userid = $request->userid ?? auth()->user()->id;
        $dbname = env('DB_DATABASE', 'sterio').'.';
        if ((!empty($userid)) && (auth()->user()->role!=1) || (!empty($request->userid ))) {
            $dbname = config('params.user_db_name').'_'.$userid.'.';

        }
        return DB::table($dbname.'amazon_orders')->join('users', 'users.id', '=', 'amazon_orders.user_id')
            ->when($request['order_date_range'], function ($query, $request) {
                $dates = explode('to', $request);
                $query->when(count($dates) == 1, function ($query) use ($dates) {
                    $query->whereDate('order_date', '=', $dates[0]);
                })
                    ->when(count($dates) == 2, function ($query) use ($dates) {

                        $to_date = date('Y-m-d', strtotime($dates[1].' +1 day'));
                        $query->whereBetween('order_date', [ConvertInDefaultTimezone($dates[0]), ConvertInDefaultTimezone($to_date)]);
                    });
            })
            ->when($request['requested_date_range'], function ($query, $request) {
                $dates = explode('to', $request);
                $query->when(count($dates) == 1, function ($query) use ($dates) {
                    $query->whereDate('request_sent_date', '=', $dates[0]);
                })
                    ->when(count($dates) == 2, function ($query) use ($dates) {

                        $to_date = date('Y-m-d', strtotime($dates[1].' +1 day'));
                        $query->whereBetween('request_sent_date', [ConvertInDefaultTimezone($dates[0]), ConvertInDefaultTimezone($to_date)]);
                    });
            })
            ->when($request['order_ids'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('amazon_order_id', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['order_status'], function ($query, $request) {
                $query->whereIn('order_status', array_column(json_decode($request), 'text'));
            })
            ->when(isset($request['request_status']), function ($query) use ($request) {
                $query->where('is_request_sent', $request['request_status']);
            })
            ->when(auth()->user()->customer_type == config('params.individual_brand'), function ($query) use ($request) {
                $query->where('user_id', auth()->user()->id);
            })
            ->when(auth()->user()->role == config('params.company_role') && auth()->user()->customer_type != config('params.individual_brand'), function ($query) use ($request) {
                $customer_ids = (new StoreService())->getSubCustomerIds(auth()->user()->id);
                $query->whereIn('user_id', $customer_ids);
            });
    }
    /**
     * getOrderStatus function
     *
     * @return object
     */
    public function getOrderStatus()
    {
        return AmazonOrder::select("order_status")
            ->distinct()->get();
    }
}
