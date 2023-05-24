<?php

namespace App\Console\Commands;

use App\Jobs\WeeklyRequestQueue;
use App\Models\User;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendWeeklyRequestCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weeklyrequest:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly success request count of users.';

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

                $weekly_success_request_count = (new SettingService())->getWeeklyCount($customer->id);

                //send mail
                $emailData['name'] = $customer->name ?? '';
                $emailData['email'] = $customer->email;
                $emailData['weekly_request'] = $weekly_success_request_count;

                if (!empty($customer->getEmailAutomations) && count($customer->getEmailAutomations) > 0) {
                    foreach ($customer->getEmailAutomations as $customer_email) {
                        //weekly request count
                        if ($customer_email->email_periods == 2) {
							User::where('id',$customer_email->user_id)
								->increment('email_count', 1);	
                            $emailData['request_date'] = Carbon::now()->startOfWeek()->format('d-m-Y') . ' to ' . Carbon::now()->endOfWeek()->format('d-m-Y');
                            $registrationEmailQueue = new WeeklyRequestQueue($emailData);
                            dispatch($registrationEmailQueue);
                        }
                    }
                }

            }
        }
        return true;

    }
}