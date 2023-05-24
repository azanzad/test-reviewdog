<?php

namespace App\Console\Commands;

use App\Models\AmazonCronErrorLog;
use App\Models\AmazonCronLog;
use App\Models\Store;
use App\Models\StoreCredential;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Tops\AmazonSellingPartnerAPI\Authentication;

class UpdateAmazonToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateaccesstoken:amazon {user?} {store_id?}';
    protected $currency = '';
    protected $cron = [
        'hour' => '',
        'date' => '',
        'cron_title' => 'UPDATE_AMAZON_ACCESS_TOKEN',
        'cron_name' => '',
        'store_id' => '',
        'fetch_report_log_id' => '',
        'report_source' => '1',
        'report_freq' => '2',
    ];
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        if (empty($storeId)) {
            $stores = Store::select('id', 'user_id')
                ->where('store_marketplace', 'Amazon')
                ->where('status', config('params.active'))
                ->get();

            if ($stores->count() > 0) {
                foreach ($stores as $store) {
                    Artisan::call('updateaccesstoken:amazon', [
                        'user' => $store->user_id,
                        'store_id' => $store->id,
                    ]);
                }
            }
        } else {
            try {
                $this->cron['hour'] = (int) date('H', time());
                $this->cron['date'] = date('Y-m-d');
                $this->cron['cron_name'] = 'CRON_' . time();
                $this->updateAccessToken($storeId);
            } catch (\Exception$e) {
                $message["Caught Exception"] = (string) $e->getMessage();
                $message["Response Status Code"] = (string) $e->getCode();
                $message["File"] = (string) $e->getFile();
                $message["Line"] = (string) $e->getLine();
                AmazonCronErrorLog::logError($storeId, 1, 'Amazon Access Token', 'updateAccessToken', $message);
            }
        }

    }
    private function updateAccessToken($storeId = null)
    {
        // If store id is not numm or zero
        if (!empty($storeId)) {

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
            // Make credential array

            $sellerConfig = [
                "refresh_token" => $storeConfig->refresh_token,
                "client_id" => $storeConfig->mws_access_key_id,
                "client_secret" => $storeConfig->mws_secret_key,
                'marketplace_ids' => [$storeConfig->store->storeConfig->amazon_marketplace_id ?? ''],
                'access_key' => $storeConfig->aws_access_key_id,
                'secret_key' => $storeConfig->aws_secret_key,
                'region' => $storeConfig->store->storeConfig->amazon_aws_region,
            ];

            $amazonSpApi = new Authentication($sellerConfig['client_id'], $sellerConfig['client_secret']);

            $response = $amazonSpApi->getAccessTokenFromRefreshToken('refresh_token', $sellerConfig['refresh_token']);

            $responseArr = json_decode(json_encode($response), true);

            // update access token
            if (!empty($responseArr) && isset($responseArr['access_token'])) {
                $storeConfig->update(['access_token' => $responseArr['access_token']]);
            }

            // Log cron end
            $addedCron->updateEndTime();
        }

    }
}