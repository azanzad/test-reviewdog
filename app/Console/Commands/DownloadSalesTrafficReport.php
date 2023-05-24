<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Models\AmazonReportLog;
use App\Models\AmazonCronLog;
use App\Models\AmazonProduct;
use App\Models\AmazonCronErrorLog;
use App\Models\FetchedReportLog;
use App\Models\SalesTrafficByAsin;
use App\Models\SalesTrafficByDate;
use App\Models\StoreCredential;
use App\Models\Store;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tops\AmazonSellingPartnerAPI\Api\ReportsApi;

class DownloadSalesTrafficReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloadSalesTrafficReport:amazon {user?} {store_id?}';

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
    protected $description = 'Download sales and traffic report of last 15 days starting from yesterday';

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
                    Artisan::call('downloadSalesTrafficReport:amazon', [
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

            // Log cron start
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

            // Log cron end
            $addedCron->updateEndTime();
        }
    }

    private function fetchSalesTrafficReport($userId = null, $storeId = null)
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

            $salesDate = Carbon::now()->format('Y-m-d');

            $amazonSpApi = new ReportsApi($this->configArr);

            $amazonReportLog = AmazonReportLog::getAmazonReportLogByRequestedDateForDownload($userId, $storeId, $this->cron['report_type'], $salesDate);

            $inserted = !empty($amazonReportLog) ? $amazonReportLog->created_at->format(config('constants.INSERT_DATE_FORMAT')) : '';

            // Get Report Request Id
            if (!empty($amazonReportLog->id) && !empty($amazonReportLog->request_id)) {

                $result = $amazonSpApi->getReport($amazonReportLog->request_id);

                // If success to get report id
                if (isset($result['payload']['processingStatus']) && $result['payload']['processingStatus'] == 'DONE') {

                    // Function call to get report document api
                    $report_document = $amazonSpApi->getReportDocument($result['payload']['reportDocumentId']);

                    //Function call downloadDocument function for download report document
                    $response = $this->downloadDocument($report_document);

                    // If success to get report data
                    if (!empty($response) && !isset($response['errorCode'])) {
                        // Function to update products
                        $this->reportRequestId = $amazonReportLog->request_id;
                        $filename = $amazonReportLog->request_id . '.csv';
                        if (!empty($response)) {
                            // Storage::disk('local')->put($filename, $response);
                            $fetchedReportLog->file_name = $filename;
                            $fetchedReportLog->save();
                        }

                        $this->saveSalesTrafficReportData($response);

                        AmazonReportLog::where('id', $amazonReportLog->id)
                            ->update([
                                "is_processed" => "1"
                            ]);
                    } else {
                        $time = new \DateTime($inserted);
                        $time->add(new \DateInterval('PT' . 50 . 'M'));
                        $addedTime = $time->format(config('constants.INSERT_DATE_FORMAT'));
                        $currentTime = date('Y-m-d H:m:i');
                        if ($currentTime > $addedTime) {

                            AmazonReportLog::where('request_id', $amazonReportLog->request_id)
                                ->update([
                                    "is_processed" => "2"
                                ]);
                        } else {
                            // Check for error
                            $this->sendErrorLog($response);
                        }
                    }


                } else if (isset($result['processingStatus']) && ($result['processingStatus'] == 'CANCELLED' || $result['processingStatus'] == 'FATAL')) {

                    AmazonReportLog::where('request_id', $amazonReportLog->request_id)
                        ->update([
                            "is_processed" => "2"
                        ]);
                }
            }

            // //log report end
            $fetchedReportLog->update([
                'status' => '1',
                'cron_end' => CommonHelper::getInsertedDateTime()
            ]);
        }catch (\Exception$e) {
            // Send error log
            $this->sendErrorLog($e->getMessage());
        }
    }

    private function downloadDocument($response = [])
    {

        if (isset($response['payload']) && !empty($response['payload'])) {
            $reportDetails = $response['payload'];

            $iv = base64_decode(CommonHelper::getValue($reportDetails, 'encryptionDetails|initializationVector'));

            $key = base64_decode(CommonHelper::getValue($reportDetails, 'encryptionDetails|key'));

            $url = CommonHelper::getValue($reportDetails, 'url');

            $report_data = openssl_decrypt(file_get_contents($url), "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv);

            $report_data = CommonHelper::extractFileContent($report_data, $response['payload']['reportDocumentId']);
        }

        return $report_data ?? [];
    }

    private function saveSalesTrafficReportData($reportData)
    {
        $report_folder = storage_path("app/public/temp/");
        $file_name = $report_folder . 'data.txt';

        $report_data = file_get_contents($file_name);

        $storeId = $this->argument('store_id');

        $reportObject = json_decode($reportData);

        $salesTrafficByDateObj = $reportObject->salesAndTrafficByDate;

        // If store id and report data not empty
        if (!empty($storeId) && !empty($salesTrafficByDateObj)) {

            // Save data in sales_traffic_by_dates table : START
            $salesByDateObj = $salesTrafficByDateObj[0]->salesByDate;

            // // Get existing row by sales date from db.
            // $existingSalesTrafficDataByDate = SalesTrafficByDate::existingSalesTrafficReportDataByDate([
            //     'storeId' => $storeId,
            //     'salesDate' => $salesTrafficByDateObj[0]->date,
            // ]);

            // Common fields to insert in both insert and update scenario
            $this->saveSalesTrafficByDate($salesByDateObj, $storeId, $salesTrafficByDateObj);
        }
    }

    /*@Description  : Function to send error log to update in db
    @Author         : Sanjay Chabhadiya
    @Input          :
    @Output         :
    @Date           : 09-03-2021
     */

    function sendErrorLog($error = null)
    {
        AmazonCronErrorLog::create([
            'store_id'      => $this->argument('store_id'),
            'module'        => 'Amazon Product Report List Cron',
            'submodule'     => $this->cron['cron_name'],
            'error_content' => serialize($error)
        ]);
    }

    function saveSalesTrafficByDate($salesByDateObj, $storeId, $salesTrafficByDateObj)
    {
        $dataArray = [
            "store_id" => $storeId,
            "sales_date" => $salesTrafficByDateObj[0]->date,
            "currency_code" => $salesByDateObj->orderedProductSales->currencyCode,
            "ordered_product_sales_amount" => $salesByDateObj->orderedProductSales->amount,
            "ordered_product_sales_amount_b2b" => $salesByDateObj->orderedProductSalesB2B->amount,
            "units_ordered" => $salesByDateObj->unitsOrdered,
            "units_ordered_b2b" => $salesByDateObj->unitsOrderedB2B,
            "total_order_items" => $salesByDateObj->totalOrderItems,
            "total_order_items_b2b" => $salesByDateObj->totalOrderItemsB2B,
        ];
        dd($dataArray);
        // // Insert a new row if no data sales data found for sales date
        // if (empty($existingSalesTrafficDataByDate)) {
        //     SalesTrafficByDate::create($dataArray);
        // } else {

        //     // Updates the existing row if sales date already exist
        //     SalesTrafficByDate::where('id', $existingSalesTrafficDataByDate->id)
        //         ->update($dataArray);
        // }
    }
}
