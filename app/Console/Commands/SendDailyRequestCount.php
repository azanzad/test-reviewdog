<?php

namespace App\Console\Commands;

use App\Jobs\DailyRequestQueue;
use App\Models\User;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDailyRequestCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyrequest:user';

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
        $users = User::whereHas('getEmailAutomations')->where('status', config('params.active'))->get();

        if (!empty($users) && count($users) > 0) {
            foreach ($users as $customer) {

                $daily_success_request_count = (new SettingService())->getDailyCount($customer->id);

                //send mail
                $emailData['name'] = $customer->name ?? '';
                $emailData['email'] = $customer->email;
                $emailData['daily_request'] = $daily_success_request_count;

                if (!empty($customer->getEmailAutomations) && count($customer->getEmailAutomations) > 0) {
                    foreach ($customer->getEmailAutomations as $customer_email) {
                        //daily request count
                        if ($customer_email->email_periods == 1) {

							User::where('id',$customer_email->user_id)
								->increment('email_count', 1);	
                            $emailData['request_date'] = Carbon::yesterday()->format('d-m-Y');
                            $registrationEmailQueue = new DailyRequestQueue($emailData);
                            dispatch($registrationEmailQueue);
							
                        }

                    }
                }

            }
        }
        return true;

    }
}