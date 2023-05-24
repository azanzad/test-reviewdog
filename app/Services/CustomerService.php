<?php
namespace App\Services;

use App\Jobs\SendStoreIntegrateQueue;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerService
{
    /**
     * getData function
     * get individual and sub customer from users table
     * @param Request $request
     * @return void
     */
    public function getData(Request $request)
    {

        return User::with('getCompany')
            ->where('role', config('params.user_roles.customer'))
            ->when(request('keyword'), function ($query) {
                $query->where('name', 'LIKE', '%' . request('keyword') . '%');
            })
            ->when(auth()->user()->role == config('params.company_role'), function ($query) {
                $query->where('companyid', auth()->user()->id);
            })
            ->when(request('companyid'), function ($query) {
                $query->where('companyid', request('companyid'));
            })
            ->when(!empty($request->customer_ids), function ($query) use ($request) {
                $query->whereIn('id', explode(",", $request->customer_ids));
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when($request['customer_name'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('name', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['email'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('email', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['parent_company'], function ($query, $request) {
                $query->whereHas('getCompany', function ($query) use ($request) {
                    $terms = explode(',', $request);
                    $query->where(function ($query) use ($terms) {
                        foreach ($terms as $term) {
                            $query->orWhere('name', 'like', "%" . trim($term) . "%");
                        };
                    });
                });
            })
            ->when($request['customer_type'], function ($query) use ($request) {
                $query->where('customer_type', $request['customer_type']);
            })
            ->when($request['status'], function ($query) use ($request) {
                $query->where('status', $request['status']);
            })
            ->when($request['store_created_date'], function ($query, $request) {
                $dates = explode('to', $request);
                $query->when(count($dates) == 1, function ($query) use ($dates) {
                    $query->whereDate('created_at', '=', $dates[0]);
                })
                    ->when(count($dates) == 2, function ($query) use ($dates) {
                        $query->whereBetween('created_at', [$dates[0], $dates[1]]);
                    });
            });
    }
    /**
     * sendStoreLink function
     *
     * @param Request $request
     * @return boolean
     */
    public function sendStoreLink(Request $request)
    {
        $customer = User::where('id', $request->id)->first();

        $emailData['name'] = $customer->name ?? '';
        $emailData['email'] = $customer->email;
        $emailData['store_link'] = config('params.app_url') . '/store/create' . '/' . $customer->uuid;

        $registrationEmailQueue = new SendStoreIntegrateQueue($emailData);
        dispatch($registrationEmailQueue);
        return true;
    }
    /**
     * sendBulkStoreLink function
     *
     * @param Request $request
     * @return void
     */
    public function sendBulkStoreLink(Request $request)
    {
        $ids = explode(",", $request->ids);
        $customers = User::whereIn('id', $ids)->get();

        if (!empty($customers) && count($customers) > 0) {
            foreach ($customers as $customer) {

                if (!empty($customer->email)) {

                    $emailData['name'] = $customer->name ?? '';
                    $emailData['email'] = $customer->email;
                    $emailData['store_link'] = config('params.app_url') . '/store/create' . '/' . $customer->uuid;

                    $registrationEmailQueue = new SendStoreIntegrateQueue($emailData);
                    dispatch($registrationEmailQueue);

                }
            }
        }
        return true;
    }
}