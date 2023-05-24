<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Store;
use App\Helpers\CommonHelper;
use App\Models\AmazonCronLog;
use App\Models\AmazonReportLog;
use App\Models\StoreCredential;
use Illuminate\Console\Command;
use App\Models\FetchedReportLog;
use Illuminate\Support\Facades\Artisan;
use Tops\AmazonSellingPartnerAPI\Api\ReportsApi;

class RequestSalesTrafficReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requestSalesTrafficReport:amazon {user?} {store_id?}';

    protected $cron = [
        // Set cron data
        'hour' => '',
        'date' => '',
        'report_type' => 'GET_SALES_AND_TRAFFIC_REPORT',
        'cron_title' => 'GET_SALES_AND_TRAFFIC_REPORT',
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
    protected $description = 'Request sales and traffic report of last 15 days starting from yesterday';

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
        $storeId = $this->argument('store_id');
        $userId = $this->argument('user');

        if (empty($storeId)) {
            $stores = Store::select('id', 'user_id')
                ->where('store_marketplace', 'Amazon')
                ->active()
                ->get();

            if ($stores->count() > 0) {
                foreach ($stores as $store) {
                    Artisan::call('requestSalesTrafficReport:amazon', [
                        'user' => $store->user_id,
                        'store_id' => $store->id
                    ]);
                }
            }
        } else {
            $this->cron['hour'] = (int) date('H', time());
            $this->cron['date'] = date('Y-m-d');
            $this->cron['cron_name'] = 'CRON_' . time();
            $this->updateAmazonSalesTrafficReport($userId, $storeId);
        }
    }

    public function updateAmazonSalesTrafficReport($userId, $storeId)
    {
        // If store id is not num or zero
        if (!empty(trim($storeId)) && (int) trim($storeId) != 0) {
            // Set store id
            $this->cron['store_id'] = $storeId = (int) trim($storeId);

            // Set cron name
            $this->cron['cron_name'] .=  '_' . $storeId;

            $this->cron['cron_param'] =  $storeId;

            // Get store config for store id
            $storeConfig = StoreCredential::getStoreConfig($storeId);

            // If store config found
            if (!isset($storeConfig->id)) {
                return;
            }

            // Set cron data
            $cronStartStop = [
                'cron_type' => $this->cron['cron_title'],
                'cron_name' => $this->cron['cron_name'],
                'store_id' => $storeId,
                'cron_param' => $this->cron['cron_param'],
                'action' => 'start',
            ];

            //Log cron start
            $addedCron = AmazonCronLog::cronStartEndUpdate($cronStartStop);

            $cronStartStop['id'] = $addedCron->id;

            $this->currency = $storeConfig->store->storeConfig->store_currency;

            $this->configArr = [
                'access_token' => $storeConfig->access_token,
                'marketplace_ids' => [$storeConfig->store->storeConfig->amazon_marketplace_id ?? ''],
                'access_key' => $storeConfig->aws_access_key_id,
                'secret_key' => $storeConfig->aws_secret_key,
                'region' => $storeConfig->store->storeConfig->amazon_aws_region,
                'host' => $storeConfig->store->storeConfig->aws_endpoint,
                'report_type' => $this->cron['report_type'],
            ];

            // Call the FBA Shipment List
            $this->fetchSalesTrafficReport($userId, $storeId);

            //Log cron end
            $addedCron->updateEndTime();
        }
    }

    private function fetchSalesTrafficReport($userId, $storeId)
    {
        try{

            //log of report start
            $fetchedReportLog = FetchedReportLog::fetchReportLog([
                'store_id' => $storeId,
                'report_source' => $this->cron['report_source'],
                'report_type' => $this->cron['report_type'],
                'report_type_name' => str_replace('_', ' ', $this->cron['cron_title']),
                'report_frequency' => $this->cron['report_freq'],
                'report_url' => 'amazon',
            ]);

            $amazonSpApi = new ReportsApi($this->configArr);
            $salesDate = Carbon::now()->format('Y-m-d');
            $amazonReportLog = AmazonReportLog::getAmazonReportLogByRequestedDate($userId, $storeId, $this->cron['report_type'], $salesDate);

            // Get Report Request Id
            if (!empty($amazonReportLog->id) && !empty($amazonReportLog->request_id)) {
            } else {

                // Call request report api and get request report id
                $body = array(
                    'reportType' => $this->cron['report_type'],
                    'marketplaceIds' => $this->configArr['marketplace_ids'],
                    'reportOptions' => [
                        "dateGranularity" => "MONTH",
                    ],
                    'dataStartTime' => Carbon::now()->subYear(1)->format('Y-m-d'),
                    'dataEndTime' => $salesDate
                );

                //Call create report api and get request report id
                $response = $amazonSpApi->createReport($body);

                $responseArr = json_decode(json_encode($response), true);

                $data = [
                    'report_type'       => $this->cron['report_type'],
                    'request_id'        => isset($responseArr['payload']['reportId']) ? trim($responseArr['payload']['reportId']) : null,
                    'user_id'           => $userId,
                    'store_id'          => $storeId,
                    'is_processed'      => 0,
                    'requested_date'    => $salesDate,
                    'processed_date'    => $salesDate,
                    'cut_off_time'      => 3000,
                ];

                // Insert entry report log entry
                if (!empty($data['request_id'])) {
                    AmazonReportLog::create($data);
                }
            }

            //log report end
            $fetchedReportLog->update([
                'status' => '1',
                'cron_end' => CommonHelper::getInsertedDateTime()
            ]);

        }catch (\Exception$e) {
            // Send error log
            $this->sendErrorLog($e->getMessage());
            $fetchedReportLog->update([
                'status' => '0',
                'cron_end' => CommonHelper::getInsertedDateTime(),
            ]);
        }
    }
}
