<?php

namespace App\Console\Commands;

use App\Jobs\WeeklyRequestQueue;
use App\Models\User;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class HourlySucucessfullCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hourlysucucessfullcount:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send sucucessfull count value of user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get all user that have set setting configuration
        $users = User::where('role', 2)->get();
		
        if (!empty($users)) {
            foreach ($users as $customer) {
				$dbname = config('params.user_db_name').'_'.$customer->id.'.';
				$orders_count= DB::table($dbname.'amazon_orders')->selectRaw("SUM(is_request_sent='1') as success_count")->first();
				$updateFields = [
					'success_count_update' => Carbon::now(),
					'success_count' => $orders_count->success_count,
				];
                DB::table('users')->where('id', $customer->id)
				->update($updateFields);
              
            }
        }
	
        return true;

    }
}