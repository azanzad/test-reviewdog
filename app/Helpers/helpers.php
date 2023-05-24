<?php

/**
 * Write code on Method
 *
 * @return response()
 */

use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Subscription;

if (!function_exists('checkPermission')) {
    function checkPermission($permissions)
    {
        if (auth()->check()) {
            $userAccess = auth()->user()->role;
            foreach ($permissions as $key => $value) {
                if ($value == $userAccess) {
                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }
}
/**
 * check company payment is pending or not
 */
if (!function_exists('isCompanyPayment')) {
    function isCompanyPayment($companyid)
    {
        return Subscription::where('user_id', $companyid)->first();
    }
}

/**
 * check company store is added or not
 */
if (!function_exists('isStoreAdded')) {
    function isStoreAdded($companyid = '')
    {
        $customer_ids = [$companyid];
        $company = User::find($companyid);
        if ($company->customer_type != config('params.individual_brand')) {
            $customer_ids = User::where('companyid', $companyid)->pluck('id');
        }
        return Store::whereIn('user_id', $customer_ids)->first();
    }
}

/**
 * get the count of sub customer
 */
if (!function_exists('getSubCustomerCount')) {
    function getSubCustomerCount($companyid = '')
    {
        return User::where('companyid', $companyid)->count();

    }
}

if (!function_exists('ConvertTimezone')) {

    function ConvertTimezone($datetime){

            $timezone = (!empty(auth()->user()->timezone)) ?auth()->user()->timezone : env('TIMEZONE');
            $date = new DateTime($datetime, new DateTimeZone(env('TIMEZONE')));
            $date->setTimezone(new DateTimeZone($timezone));
            return $date->format('Y-m-d H:i:s');

    }
}
if (!function_exists('ConvertInDefaultTimezone')) {
    function ConvertInDefaultTimezone($datetime){

        $timezone = (!empty(auth()->user()->timezone)) ?auth()->user()->timezone : env('TIMEZONE');
        $date = new DateTime($datetime, new DateTimeZone($timezone));
        $date->setTimezone(new DateTimeZone(env('TIMEZONE')));
        return $date->format('Y-m-d H:i:s');

    }
}
