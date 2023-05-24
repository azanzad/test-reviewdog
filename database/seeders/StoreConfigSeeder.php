<?php

namespace Database\Seeders;

use App\Models\StoreConfig;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $demoUser = StoreConfig::insert([[
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon US',
            'store_url' => 'https: //www.amazon.com/',
            'seller_central_url' => 'https://sellercentral.amazon.com',
            'aws_endpoint' => 'https: //sellingpartnerapi-na.amazon.com',
            'amazon_marketplace_id' => 'ATVPDKIKX0DER',
            'amazon_region' => 'na',
            'amazon_aws_region' => 'us-east-1',
            'store_currency' => 'USD',
            'store_timezone' => 'America/Los_Angeles',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ], [
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon CA',
            'store_url' => 'https: //www.amazon.ca/',
            'seller_central_url' => 'https: //sellercentral.amazon.ca',
            'aws_endpoint' => 'https: //sellingpartnerapi-na.amazon.com',
            'amazon_marketplace_id' => 'A2EUQ1WTGCTBG2',
            'amazon_region' => 'na',
            'amazon_aws_region' => 'us-east-1',
            'store_currency' => 'CAD',
            'store_timezone' => 'America/Los_Angeles',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ], [
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon UK',
            'store_url' => 'https: //www.amazon.co.uk/',
            'seller_central_url' => 'https: //sellercentral.amazon.co.uk',
            'aws_endpoint' => 'https: //sellingpartnerapi-eu.amazon.com',
            'amazon_marketplace_id' => 'A1F83G8C2ARO7P',
            'amazon_region' => 'eu',
            'amazon_aws_region' => 'eu-west-1',
            'store_currency' => 'GBP',
            'store_timezone' => 'Europe/London',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ], [
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon ES',
            'store_url' => 'https: //www.amazon.es/',
            'seller_central_url' => 'https: //sellercentral.amazon.es',
            'aws_endpoint' => 'https: //sellingpartnerapi-eu.amazon.com',
            'amazon_marketplace_id' => 'A1RKKUPIHCS9HS',
            'amazon_region' => 'eu',
            'amazon_aws_region' => 'eu-west-1',
            'store_currency' => 'EUR',
            'store_timezone' => 'Europe/London',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ], [
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon FR',
            'store_url' => 'https: //www.amazon.fr/',
            'seller_central_url' => 'https: //sellercentral.amazon.fr',
            'aws_endpoint' => 'https: //sellingpartnerapi-eu.amazon.com',
            'amazon_marketplace_id' => 'A13V1IB3VIYZZH',
            'amazon_region' => 'eu',
            'amazon_aws_region' => 'eu-west-1',
            'store_currency' => 'EUR',
            'store_timezone' => 'Europe/London',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ], [
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon DE',
            'store_url' => 'https: //www.amazon.de/',
            'seller_central_url' => 'https: //sellercentral.amazon.de',
            'aws_endpoint' => 'https: //sellingpartnerapi-eu.amazon.com',
            'amazon_marketplace_id' => 'A1PA6795UKMFR9',
            'amazon_region' => 'eu',
            'amazon_aws_region' => 'eu-west-1',
            'store_currency' => 'EUR',
            'store_timezone' => 'Europe/London',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ], [
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon IT',
            'store_url' => 'https: //www.amazon.it/',
            'seller_central_url' => 'https: //sellercentral.amazon.it',
            'aws_endpoint' => 'https: //sellingpartnerapi-eu.amazon.com',
            'amazon_marketplace_id' => 'APJ6JRA9NG5V4',
            'amazon_region' => 'eu',
            'amazon_aws_region' => 'eu-west-1',
            'store_currency' => 'EUR',
            'store_timezone' => 'Europe/London',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ], [
            'uuid' => Str::uuid(),
            'store_type' => 'Amazon IN',
            'store_url' => 'https: //www.amazon.in/',
            'seller_central_url' => 'https: //sellercentral.amazon.in',
            'aws_endpoint' => 'https: //sellingpartnerapi-eu.amazon.com',
            'amazon_marketplace_id' => 'A21TJRUUN4KGV',
            'amazon_region' => 'eu',
            'amazon_aws_region' => 'eu-west-1',
            'store_currency' => 'INR',
            'store_timezone' => 'Europe/London',
            'store_marketplace' => 'Amazon',
            'store_country' => 'US',
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ]]);

    }
}