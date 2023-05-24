<?php
namespace App\Services;

use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentTransactionService
{
    /**
     * getData function
     * get all payment transaction
     * @param Request $request
     * @return object
     */
    public function getData(Request $request)
    {
        return PaymentTransaction::with('getPlan', 'getCustomer')->select('payment_transactions.*')
            ->when(auth()->user()->role == config('params.company_role'), function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->when($request['status'], function ($query, $request) {
                $query->where('status', $request);
            })
            ->when($request['customer_name'], function ($query, $request) {
                $query->whereHas('getCustomer', function ($query) use ($request) {
                    $terms = explode(',', $request);
                    $query->where(function ($query) use ($terms) {
                        foreach ($terms as $term) {
                            $query->orWhere('name', 'like', "%" . trim($term) . "%");
                        };
                    });
                });
            })
            ->when($request['plan_name'], function ($query, $request) {
                $query->whereHas('getPlan', function ($query) use ($request) {
                    $terms = explode(',', $request);
                    $query->where(function ($query) use ($terms) {
                        foreach ($terms as $term) {
                            $query->orWhere('name', 'like', "%" . trim($term) . "%");
                        };
                    });
                });
            })
            ->when($request['price_operation'] && $request['price_operation'] != 'Range', function ($query) use ($request) {
                $query->where('amount', $request['price_operation'], $request['price']);
            })
            ->when($request['price_operation'] && $request['price_operation'] == 'Range', function ($query) use ($request) {
                $query->whereBetween('amount', [$request['price'], $request['price_to']]);
            })
            ->when($request['transaction_status'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('transaction_status', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['transaction_date'], function ($query, $request) {
                $dates = explode('to', $request);

                $query->when(count($dates) == 1, function ($query) use ($dates) {

                    $query->whereDate('transaction_date', '=', ConvertInDefaultTimezone($dates[0]) );
                })
                    ->when(count($dates) == 2, function ($query) use ($dates) {

                        $to_date = date('Y-m-d', strtotime($dates[1].' +1 day'));

                        $query->whereBetween('transaction_date', [ConvertInDefaultTimezone($dates[0]), ConvertInDefaultTimezone($to_date)] );
                    });
            });
    }

}
