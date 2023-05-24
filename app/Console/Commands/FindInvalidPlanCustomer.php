<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FindInvalidPlanCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'findInvalidplanCustomer';

    protected $cron = [
        // Set cron data
        'hour' => '',
        'date' => '',
        'report_type' => '',
        'cron_title' => '',
        'cron_name' => '',
        'store_id' => '',
        'fetch_report_log_id' => '',
        'report_source' => '1', //SP API
        'report_freq' => '2', //Daily
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Getting the annual business sales of the customer and suggesting the plan accordingly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');

        $this->cron['hour'] = (int) date('H', time());
        $this->cron['date'] = date('Y-m-d');
        $this->cron['cron_name'] = 'CRON_' . time();

        //get over sales customer
        $this->fetchOverSalesCustomer();
    }

    public function fetchOverSalesCustomer()
    {

        $users = User::whereHas('stores', function ($query) {
            $query->where('status', config('params.active'));
        })->with('getPlan')->where('role', '!=', config('params.admin_role'))
            ->where('is_db_created', 1)->where('over_sales', 0)
            ->get();


        foreach ($users as $user) {

            if ($user->getPlan->annual_sales_to < $user->over_sales_amount) {
                User::where('id', $user->id)->update(['over_sales' => 1, 'over_sales_amount' => $user->over_sales_amount]);
            }
        }
    }
}
