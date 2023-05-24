<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Models\AmazonCronErrorLog;
use App\Models\AmazonCronLog;
use App\Models\AmazonOrder;
use App\Models\FetchedReportLog;
use App\Models\Store;
use App\Models\StoreCredential;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Tops\AmazonSellingPartnerAPI\Api\SolicitationsApi;

class SellerFeedbackSolicitation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sellerfeedbacksolicitation:amazon {user?} {store_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a solicitation to a buyer asking for seller feedback and a product review for the specified order. Send only one productReviewAndSellerFeedback or free form proactive message per order.';
    protected $previousHour = -1;
    protected $solicitationsApi = [];
    protected $cron = [
        // Set cron data
        'hour' => '',
        'date' => '',
        'report_type' => 'CHECK_ORDER_ELIGIBLE_FOR_FEEDBACK',
        'cron_title' => 'CHECK ORDER ELIGIBLE FOR FEEDBACK',
        'cron_name' => '',
        'store_id' => '',
        'fetch_report_log_id' => '',
        'report_source' => '1', //SP API
        'report_freq' => '2', //Daily
    ];
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
                ->where('status', config('params.active'))
                ->get();

            if ($stores->count() > 0) {
                foreach ($stores as $store) {
                    Artisan::call('sellerfeedbacksolicitation:amazon', [
                        'user' => $store->user_id,
                        'store_id' => $store->id,
                    ]);
                }
            }
        } else {
            $this->cron['hour'] = (int) date('H', time());
            $this->cron['date'] = date('Y-m-d');
            $this->cron['cron_name'] = 'CRON_' . time();
            $this->checkAmazonOrder($userId, $storeId);
        }

    }
    public function checkAmazonOrder($userId, $storeId)
    {

        // If store id is not num or zero
        if (!empty(trim($storeId)) && (int) trim($storeId) != 0) {
            // Set store id
            $this->cron['store_id'] = $storeId = (int) trim($storeId);

            // Set cron name
            $this->cron['cron_name'] .= '_' . $storeId;

            $this->cron['cron_param'] = $storeId;

            // Get store config for store id
            $storeCredential = StoreCredential::getStoreConfig($storeId);

            // If store config found
            if (!isset($storeCredential->id)) {
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

            $this->currency = $storeCredential->store->storeConfig->store_currency ?? '';

            $this->configArr = [
                'access_token' => $storeCredential->access_token,
                'marketplace_ids' => $storeCredential->store->storeConfig->amazon_marketplace_id ?? '',
                'access_key' => $storeCredential->aws_access_key_id,
                'secret_key' => $storeCredential->aws_secret_key,
                'region' => $storeCredential->store->storeConfig->amazon_aws_region,
                'host' => $storeCredential->store->storeConfig->aws_endpoint,
                'report_type' => $this->cron['report_type'],
            ];

            // Call the Solicitation order api
            $this->invokeSolicitationsApi($storeId, $userId);

            // Log cron end
            $addedCron->updateEndTime();
        }
    }

    private function invokeSolicitationsApi($storeId = null, $userId = null)
    {
        // If store id is not numm or zero
        if (!empty($storeId) && (int) trim($storeId) != 0) {
            // Set store id
            $this->cron['store_id'] = $storeId = (int) trim($storeId);

            // Set cron name
            $this->cron['cron_name'] .= '_' . $storeId;
            $this->cron['cron_param'] = $storeId;

            // Get store config for store id
            $storeConfig = StoreCredential::getStoreConfig($storeId);

            // If store config found
            if (!isset($storeConfig->id)) {
                return;
            }

            //log of report start
            $fetchedReportLog = FetchedReportLog::fetchReportLog([
                'store_id' => $storeId,
                'report_source' => $this->cron['report_source'],
                'report_type' => $this->cron['report_type'],
                'report_type_name' => str_replace('_', ' ', $this->cron['cron_title']),
                'report_frequency' => $this->cron['report_freq'],
                'report_url' => 'amazon-solicitations-order',
            ]);

            try {
                //get all pending request orders
                $dbname = config('params.user_db_name').'_'.$userId.'.';
                $fetch_order_date = Carbon::parse(Carbon::now()->subDays(9)->format('Y-m-d'));
                $last_updated_date = Carbon::parse(Carbon::now()->subDays(30)->format('Y-m-d'));

                $orders = DB::table($dbname.'amazon_orders')->select('id', 'amazon_order_id', 'store_id', 'is_request_sent', 'order_date')
                    ->where('is_request_sent', '0')
                    ->where('order_status', 'Shipped')
                    ->whereDate('order_date', '<=', $fetch_order_date)
                    ->whereDate('last_updated_date', '>=', $last_updated_date)
                    ->get();

                if ($orders->count() > 0) {
                    foreach ($orders as $order) {

                        $amazonOrderId = $order->amazon_order_id;
                        $marketplace_ids = $this->configArr['marketplace_ids'];

                        $this->solicitationsApi = new SolicitationsApi($this->configArr);

                        $body = [
                            'marketplaceIds' => $marketplace_ids,
                        ];
                        //check order is eligible for solicitation api call
                        $order_action = $this->solicitationsApi->getSolicitationActionsForOrder($body, $amazonOrderId);
                        sleep(1);

                        if (!isset($order_action->errors)) {
                            //if eligible then call create feedback api call
                            if (isset($order_action['_links']['actions']) && count($order_action['_links']['actions']) > 0) {

                                $this->createProductReviewApi = new SolicitationsApi($this->configArr);

                                $review_res = $this->createProductReviewApi->createProductReviewAndSellerFeedbackSolicitation($body, $amazonOrderId);
                                if (!isset($review_res->errors)) {
                                    //request sent set 1=successfully,
                                    $this->updateAmazonOrderRequestStatus('1', $amazonOrderId);
                                } else {
                                    //request sent set 2=failed,
                                    $this->updateAmazonOrderRequestStatus('2', $amazonOrderId);
                                }
                            } else {
                                //request sent set 1=successfully,
                                //$this->updateAmazonOrderRequestStatus('1', $amazonOrderId);
                            }
                            //log report end
                            $fetchedReportLog->update([
                                'status' => '1',
                            ]);
                        } else {
                            $error = isset($order->errors) && isset($order->errors[0]) && isset($order->errors[0]->message) ? $order->errors[0]->message : Lang::get('messages.getting_error_amazon');
                            $this->sendErrorLog($error);
                            //log report end
                            $fetchedReportLog->update([
                                'status' => '0',
                                'cron_end' => CommonHelper::getInsertedDateTime(),
                            ]);
                        }

                    }
                }

            } catch (\Exception$e) {
                // Send error log
                $this->sendErrorLog($e->getMessage());
                //log report end
                $fetchedReportLog->update([
                    'status' => '0',
                    'cron_end' => CommonHelper::getInsertedDateTime(),
                ]);
            }

            // Log cron end
            // $cronLog->updateEndTime();
        }
    }

    public function updateAmazonOrderRequestStatus($status, $amazonOrderId)
    {

        $userId = $this->argument('user');
        $storeId = $this->argument('store_id');

        // Check order exists in our system or not
        $orderExists = AmazonOrder::orderExists($storeId, $amazonOrderId, $userId);

        // If order exists
        if (!empty($orderExists->id)) {

            $updateFields = [
                'request_sent_date' => Carbon::now(),
                'is_request_sent' => $status,
            ];

            // Update details in table
            $dbname = config('params.user_db_name').'_'.$userId.'.';
            DB::table($dbname.'amazon_orders')->where('store_id', $storeId)
                ->where('amazon_order_id', $amazonOrderId)->update($updateFields);
            //$orderExists->update($updateFields);
            //$this->info("order request sent.");

        } else {
            // Send error log
            $this->sendErrorLog("order not found.");

        }

    }
    public function sendErrorLog($error = null)
    {
        AmazonCronErrorLog::create([
            'store_id' => $this->argument('store_id'),
            'module' => 'Amazon Orders Fetch Cron',
            'submodule' => $this->cron['cron_name'],
            'error_content' => $error,
        ]);
        return $this->error($error);
    }
}
