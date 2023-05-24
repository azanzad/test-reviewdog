<?php
namespace App\Services;

use App\Models\AmazonOrder;
use App\Models\Store;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getRecordsCount(Request $request)
    {
        $data = [];
        /**get counts of parent company */
        $data['parent_company'] = User::whereNull('customer_type')
            ->where('role', '!=', config('params.admin_role'))
            ->selectRaw('COUNT(*) as total_count,
                           SUM(status=2) as inactive_count,
                           SUM(status=1) as active_count')
            ->first();
        /**get counts of plans */
        $data['plan'] = SubscriptionPlan::selectRaw('COUNT(*) as total_count,
                           SUM(status=2) as inactive_count,
                           SUM(status=1) as active_count')->first();

        /**get counts of customers */
        $data['customer'] = User::where('customer_type', config('params.parent_company'))
            ->when(auth()->user()->role == config('params.company_role'), function ($query) {
                $query->where('companyid', auth()->user()->id);
            })
            ->selectRaw('COUNT(*) as total_count,
                           SUM(status=2) as inactive_count,
                           SUM(status=1) as active_count')
            ->first();

        /**get counts of individual customers */
        $data['individual_customer'] = User::where('customer_type', config('params.individual_brand'))
            ->selectRaw('COUNT(*) as total_count,
                           SUM(status=2) as inactive_count,
                           SUM(status=1) as active_count')
            ->first();

        /**get counts of store */
        $data['store'] = Store::selectRaw('COUNT(*) as total_count,
                           SUM(status=2) as inactive_count,
                           SUM(status=1) as active_count')
            ->when(auth()->user()->customer_type == config('params.individual_brand'), function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->when(auth()->user()->customer_type == null && auth()->user()->role == config('params.company_role'), function ($query) {
                $query->whereIn('user_id', (new StoreService())->getSubCustomerIds(auth()->user()->id));
            })
            ->first();

        /**get counts of order requests */
		if(auth()->user()->customer_type=='1'){
			$data['orders']['success_count'] = auth()->user()->success_count;
		}
		elseif(auth()->user()->role=='1')
		{
			$data['orders']['success_count'] = DB::table('users')->sum('success_count'); 
		}
		else
		{
			$data['orders']['success_count']  = DB::table('users')->where('companyid', auth()->user()->id)->sum('success_count'); 
		}

		/*AmazonOrder::selectRaw("SUM(is_request_sent='1') as success_count")
            ->when(auth()->user()->customer_type == config('params.individual_brand'), function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->when(auth()->user()->customer_type == null && auth()->user()->role == config('params.company_role'), function ($query) {
                $query->whereIn('user_id', (new StoreService())->getSubCustomerIds(auth()->user()->id));
            })
            ->first();*/

        return $data;
    }

}