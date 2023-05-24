<?php

namespace App\Console\Commands;

use App\Jobs\MonthlyRequestQueue;
use App\Models\User;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendMonthlyRequestCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthlyrequest:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily success request count of users.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get all user that have set setting configuration
        $users = User::whereHas('getEmailAutomations')->whereHas('getOrders')->where('status', config('params.active'))->get();

        if (!empty($users) && count($users) > 0) {
            foreach ($users as $customer) {
                $monthly_success_request_count = (new SettingService())->getMonthlyCount($customer->id);
                //send mail
                $emailData['name'] = $customer->name ?? '';
                $emailData['email'] = $customer->email;
                $emailData['monthly_request'] = $monthly_success_request_count;
                if (!empty($customer->getEmailAutomations) && count($customer->getEmailAutomations) > 0) {
                    foreach ($customer->getEmailAutomations as $customer_email) {
                        //monthly request count

                        if ($customer_email->email_periods == 3) {
							User::where('id',$customer_email->user_id)
								->increment('email_count', 1);	
                            $month = Carbon::now()->subMonth()->month;
                            $month_name = date("F", mktime(0, 0, 0, $month, 10));

                            $emailData['request_date'] = $month_name . ', ' . date('Y');

                            $registrationEmailQueue = new MonthlyRequestQueue($emailData);
                            dispatch($registrationEmailQueue);
                        }

                    }
                }

            }
        }
        return true;

    }
}