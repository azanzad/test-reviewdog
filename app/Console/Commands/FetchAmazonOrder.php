<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Models\AmazonCronErrorLog;
use App\Models\AmazonCronLog;
use App\Models\AmazonOrder;
use App\Models\FetchedReportLog;
use App\Models\Store;
use App\Models\StoreCredential;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Tops\AmazonSellingPartnerAPI\Api\OrdersApi;

class FetchAmazonOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetchorder:amazon {user?} {store_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch amazon orders from marketplace';
    protected $previousHour = -1;
    protected $ordersApi = [];
    protected $cron = [
        // Set cron data
        'hour' => '',
        'date' => '',
        'report_type' => 'FETCH_AMAZON_ORDER',
        'cron_title' => 'FETCH AMAZON ORDER',
        'cron_name' => '',
        'store_id' => '',
        'fetch_report_log_id' => '',
        'report_source' => '1', //SP API
        'report_freq' => '2', //Daily
    ];

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
                ->where('status', config('params.active'))
                ->get();

            if ($stores->count() > 0) {
                foreach ($stores as $store) {
                    Artisan::call('fetchorder:amazon', [
                        'user' => $store->user_id,
                        'store_id' => $store->id,
                    ]);
                }
            }
        } else {
            $this->cron['hour'] = (int) date('H', time());
            $this->cron['date'] = date('Y-m-d');
            $this->cron['cron_name'] = 'CRON_' . time();
            $this->fetchAmazonOrder($userId, $storeId);
        }

    }
    public function fetchAmazonOrder($userId, $storeId)
    {
        //check user's subscription plan active or not
        $user = User::where(['id' => $userId, 'status' => config('params.active'), 'is_plan_active' => 1])->first();
        // If store id is not num or zero
        if (!empty($user) && !empty(trim($storeId)) && (int) trim($storeId) != 0) {
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
                'marketplace_ids' => [$storeCredential->store->storeConfig->amazon_marketplace_id ?? ''],
                'access_key' => $storeCredential->aws_access_key_id,
                'secret_key' => $storeCredential->aws_secret_key,
                'region' => $storeCredential->store->storeConfig->amazon_aws_region,
                'host' => $storeCredential->store->storeConfig->aws_endpoint,
                'report_type' => $this->cron['report_type'],
            ];

            // Call the FBA Shipment List
            $this->invokeOrderApi($storeId, $userId);

            // Log cron end
            $addedCron->updateEndTime();
        }
    }

    private function invokeOrderApi($storeId = null, $userId = null)
    {
        // If store id is not numm or zero
        if (!empty($storeId) && (int) trim($storeId) != 0) {
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

            //log of report start
            $fetchedReportLog = FetchedReportLog::fetchReportLog([
                'store_id' => $storeId,
                'report_source' => $this->cron['report_source'],
                'report_type' => $this->cron['report_type'],
                'report_type_name' => str_replace('_', ' ', $this->cron['cron_title']),
                'report_frequency' => $this->cron['report_freq'],
                'report_url' => 'amazon-order',
            ]);

            $latestOrderDatetime = AmazonOrder::getLatestOrderDatetime($storeId, $userId);

            if (!empty($latestOrderDatetime)) {
                $dateTime = Carbon::parse($latestOrderDatetime)->subHours(config('amazon.ORDER_FETCHING_LAST_HOURS'));
            } else {
                $dateTime = Carbon::parse(config('amazon.ORDER_FETCHING_START_DATE'));
            }

            $modTimeFrom = $dateTime->format('Y-m-d\TH:i:s');

            try {
                $this->ordersApi = new OrdersApi($this->configArr);

                $nextToken = null;
                $body = [
                    'MarketplaceIds' => $this->configArr['marketplace_ids'],
                    'LastUpdatedAfter' => $modTimeFrom,
                ];
                ADD_ONE_TO_NEXT_TOKEN:
                
                $orders = $this->ordersApi->getOrders($body);

                if (!isset($orders->errors)) {
                    if (isset($orders['payload']['Orders']) && count($orders['payload']['Orders']) > 0) {
                        $this->saveAmazonOrders($orders, $modTimeFrom);
                    }

                    //If get next token
                    if (isset($orders['payload']['NextToken']) && !empty($orders['payload']['NextToken'])) {
                        sleep(1);

                        $nextToken = $orders['payload']['NextToken'];

                        $body = [
                            'MarketplaceIds' => $this->configArr['marketplace_ids'],
                            'LastUpdatedAfter' => $modTimeFrom,
                            'NextToken' => $nextToken,
                        ];

                        goto ADD_ONE_TO_NEXT_TOKEN;
                    }
                    //log report end
                    $fetchedReportLog->update([
                        'status' => '1',
                    ]);
                } else {
                    $error = isset($orders->errors) && isset($orders->errors[0]) && isset($orders->errors[0]->message) ? $orders->errors[0]->message : Lang::get('messages.getting_error_amazon');
                    $this->sendErrorLog($error);
                    //log report end
                    $fetchedReportLog->update([
                        'status' => '0',
                        'cron_end' => CommonHelper::getInsertedDateTime(),
                    ]);
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
        }
    }

    public function saveAmazonOrders($orderList, $modTimeFrom = null)
    {
        $storeId = $this->argument('store_id');
        $userId = $this->argument('user');

        // For each order
        if (isset($orderList['payload']) && isset($orderList['payload']['Orders'])) {
            foreach ($orderList['payload']['Orders'] as $order) {

                // If purchase date greater than first order date
                //if ($order->PurchaseDate >= $modTimeFrom) {
                // Set update fields
                $updateFields = array();

                $LastUpdateDate = isset($order['LastUpdateDate']) ? date(config('amazon.INSERT_DATE_FORMAT'), strtotime($order['LastUpdateDate'])) : null;

                $PurchaseDate = isset($order['PurchaseDate']) ? date(config('amazon.INSERT_DATE_FORMAT'), strtotime("+4 hours " . $order['PurchaseDate'])) : null;

                $OrderDate = isset($order['PurchaseDate']) ? date(config('amazon.INSERT_DATE_FORMAT'), strtotime($order['PurchaseDate'])) : null;

                $updateFields = [
                    'user_id' => $userId,
                    'order_date' => $OrderDate,
                    'purchase_date' => $PurchaseDate,
                    'last_updated_date' => $LastUpdateDate,
                    'order_status' => isset($order['OrderStatus']) ? (string) $order['OrderStatus'] : null,
                    'fulfillment_channel' => isset($order['FulfillmentChannel']) ? (string) $order['FulfillmentChannel'] : null,
                    'ship_service_level' => isset($order['ShipServiceLevel']) ? (string) $order['ShipServiceLevel'] : null,
                    'shipping_service_level_category' => isset($order['ShipmentServiceLevelCategory']) ? (string) $order['ShipmentServiceLevelCategory'] : null,
                    'shipping_label_cba' => isset($order['CbaDisplayableShippingLabel']) ? (string) $order['CbaDisplayableShippingLabel'] : null,
                ];

                // If order total found
                if (isset($order['OrderTotal'])) {
                    $orderTotal = $order['OrderTotal'];
                    $updateFields += [
                        'order_currency' => isset($orderTotal['CurrencyCode']) ? (string) $orderTotal['CurrencyCode'] : null,
                        'order_total' => isset($orderTotal['Amount']) ? (double) $orderTotal['Amount'] : null,
                    ];
                } else {
                    // Else blank
                    $updateFields += [
                        'order_currency' => !empty($this->currencyCode) ? $this->currencyCode : null,
                        'order_total' => null,
                    ];
                }

                $updateFields += [
                    // Item shipped, Unshipped, Pay Method, Buyer email & Name
                    'items_shipped' => isset($order['NumberOfItemsShipped']) ? (string) $order['NumberOfItemsShipped'] : null,
                    'items_unshipped' => isset($order['NumberOfItemsUnshipped']) ? (string) $order['NumberOfItemsUnshipped'] : null,
                    'payment_method' => isset($order['PaymentMethod']) ? (string) $order['PaymentMethod'] : null,

                    // Other orders fields
                    'order_type' => isset($order['OrderType']) ? (string) $order['OrderType'] : null,
                    'is_business_order' => isset($order['IsBusinessOrder']) && trim($order['IsBusinessOrder']) === 'true' ? "1" : "0",
                    'sales_channel' => isset($order['SalesChannel']) ? (string) $order['SalesChannel'] : null,
                    'order_channel' => isset($order['OrderChannel']) ? (string) $order['OrderChannel'] : null,
                    'is_prime_order' => isset($order['IsPrime']) && trim($order['IsPrime']) === 'true' ? "1" : "0",
                    'is_premium_order' => isset($order['IsPremiumOrder']) && trim($order['IsPremiumOrder']) === 'true' ? "1" : "0",
                    'shipping_service_level_category' => isset($order['ShipmentServiceLevelCategory']) ? (string) $order['ShipmentServiceLevelCategory'] : null,
                    'seller_order_id' => isset($order['SellerOrderId']) ? (string) $order['SellerOrderId'] : null,

                    // Earlierst and Latest Shipping Date
                    'ship_date_earliest' => isset($order['EarliestShipDate']) && date('Y', strtotime($order['EarliestShipDate'])) != "1970" ? date(config('amazon.INSERT_DATE_FORMAT'), strtotime($order['EarliestShipDate'])) : null,
                    'ship_date_latest' => isset($order['LatestShipDate']) && date('Y', strtotime($order['LatestShipDate'])) != "1970" ? date(config('amazon.INSERT_DATE_FORMAT'), strtotime($order['LatestShipDate'])) : null,

                    // Earliest Delivery Date
                    'delivery_date_earliest' => isset($order['EarliestDeliveryDate']) ? (string) $order['EarliestDeliveryDate'] : null,
                    'delivery_date_earliest' => isset($order['EarliestDeliveryDate']) && date('Y', strtotime($order['EarliestDeliveryDate'])) != "1970" ? date(config('amazon.INSERT_DATE_FORMAT'), strtotime($order['EarliestDeliveryDate'])) : null,
                    'delivery_date_earliest' => isset($order['LatestDeliveryDate']) && date('Y', strtotime($order['LatestDeliveryDate'])) != "1970" ? date(config('amazon.INSERT_DATE_FORMAT'), strtotime($order['LatestDeliveryDate'])) : null,

                    // Flag Updated
                    'updated' => '0',
                ];

                // Begin Transaction
                DB::transaction(function () use ($order, $storeId, $updateFields, $userId) {

                    // Check order exists in our system or not
                    $orderExists = AmazonOrder::orderExists($storeId, $order['AmazonOrderId'], $userId);

                    // If order exists
                    if (!empty($orderExists->id)) {
                        // Set processed 2
                        if ($orderExists->processed != '0') {
                            $updateFields['processed'] = '2';
                        }

                        // Update details in table
                        AmazonOrder::orderForUpdate($storeId, $order['AmazonOrderId'], $userId)->update($updateFields);

                    } else {
                        // Set store id, amazon order id, processed flag and insert date time
                        $updateFields['store_id'] = $storeId;
                        $updateFields['amazon_order_id'] = $order['AmazonOrderId'];
                        $updateFields['processed'] = '0';

                        // Insert into table
                        //$dbname = env('DB_DATABASE', 'sterio');
                        if (!empty($userId)) {
                            $dbname = config('params.user_db_name').'_'.$userId.'.';
                            DB::table($dbname.'amazon_orders')->insert($updateFields);
                        }

                    }
                });

            }

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
    }
}
