<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\Timezone;
use App\Models\AmazonOrder;
use Illuminate\Http\Request;
use App\Models\UserEmailAutomation;
use Illuminate\Support\Facades\DB;

class SettingService
{
    /**
     * addOrUpdateCompanyEmailPeriod function
     *
     * @param Request $request
     * @param integer $companyid
     * @return void
     */
    public function addOrUpdateCompanyEmailPeriod(Request $request, int $companyid)
    {

        //update timezone
        if(!empty($request->timezone)){
            $user = User::find(auth()->user()->id);
            $user->timezone = $request->timezone;
            $user->save();
        }

        $new_valueids = [];

        if (!empty($request->email_update)) {
            foreach ($request->email_update as $key => $email_update) {

                $email_automation = UserEmailAutomation::where(['user_id' => $companyid, 'email_periods' => $email_update])->first();
                if (empty($email_automation)) {
                    $email_automation = new UserEmailAutomation();
                }
                $email_automation->user_id = $companyid;
                $email_automation->email_periods = $email_update;
                $email_automation->save();
                array_push($new_valueids, $email_automation->id);

            }

        }

        //delete old attribute value at update time
        if (!empty($companyid)) {
            UserEmailAutomation::whereNotIn('id', $new_valueids)->where('user_id', $companyid)->delete();
        }

    }
    /**
     * getDailyCount function
     *
     * @param integer $user_id
     * @return integer
     */
    public function getDailyCount(int $user_id)
    {
        $dbname = config('params.user_db_name').'_'.$user_id.'.';
        return DB::table($dbname.'amazon_orders')->whereDate('request_sent_date', '=', Carbon::yesterday()->format('Y-m-d'))
            ->where('user_id', $user_id)
            ->where('is_request_sent', '1')
            ->count();

    }
    /**
     * getWeeklyCount function
     *
     * @param integer $companyid
     * @return integer
     */
    public function getWeeklyCount(int $user_id)
    {
        $dbname = config('params.user_db_name').'_'.$user_id.'.';
        return DB::table($dbname.'amazon_orders')->whereBetween('request_sent_date',
            [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]
        )
            ->where('user_id', $user_id)
            ->where('is_request_sent', '1')
            ->count();

    }
    /**
     * getMonthlyCount function
     *
     * @param integer $user_id
     * @return integer
     */
    public function getMonthlyCount(int $user_id)
    {
        $dbname = config('params.user_db_name').'_'.$user_id.'.';
        return DB::table($dbname.'amazon_orders')->whereMonth(
            'request_sent_date', '=', Carbon::now()->subMonth()->month
        )
            ->where('user_id', $user_id)
            ->where('is_request_sent', '1')
            ->count();

    }

    /**
     * getTimezone function
     *
     * @return object
     */
    public function getTimezone(){
        $country_code = Country::where('id',auth()->user()->country_id)->value('country_code');
        $timzone = Timezone::where('country_code', $country_code)->orderBy('timezone', 'asc')->get();
        return $timzone;
    }

    /**
     * getTimezoneByCountryCode function
     * @param int $country_id
     * @return string
     */
    public function getTimezoneByCountryCode($country_id){

        $timezone = Country::where('id',$country_id)->with('timezone')->first();
        $timezone = $timezone->timezone->timezone ?? '';
        return $timezone;
    }

    /**
     * getCountry function
     * @param
     * @return string
     */
    public function getCountry(){

        return Country::orderBy('name','asc')->get();
    }

}
