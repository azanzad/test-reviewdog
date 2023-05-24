<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->integer('store_id')->comment('id of store from the stores table')->unsigned();
            $table->char('merchant_id', 14)->comment('Seller Id - Merchant Id of the user to access the mws services of this marketplace')->nullable();
            $table->string('mws_auth_token', 500)->comment('Seller-Developer Authorization Token')->nullable();
            $table->string('instance_id', 50)->comment('Wix instance id')->nullable();
            $table->string('refresh_token', 500)->comment('Seller-Developer Refresh Token')->nullable();
            $table->string('access_token', 500)->comment('Seller-Developer Access Token')->nullable();
            $table->string('mws_access_key_id', 255)->comment('MWS Access Id of the user to access the mws services of this marketplace')->nullable();
            $table->string('mws_secret_key', 100)->comment('Secret key of the user to access the mws services of this marketplace')->nullable();
            $table->string('aws_access_key_id', 50)->comment('AWSAccessKeyId of the user to access the aws services of this marketplace')->nullable();
            $table->string('aws_secret_key', 100)->comment('AWS Secret Key of the user to access the aws services of this marketplace')->nullable();
            $table->string('amazon_aws_region', 25)->nullable();
            $table->string('sqs_query_url', 250)->comment('Amazon SQS Query URL to fetch notifications')->nullable();
            $table->enum('is_fetch_order', ['1', '0'])->comment('fetch order for specific stores')->default('1');
            $table->dateTime('order_fetching_start_date')->comment('Date & time since when we should fetch the orders from the marketplace')->nullable();
            $table->dateTime('return_order_fetch_date')->comment('Date & time since when we should fetch the orders from the marketplace')->nullable();
            $table->dateTime('seller_shipment_start_date')->comment('Date & time since when we should fetch the shipments from the marketplace')->nullable();
            $table->enum('is_return_order_fetched', ['0', '1'])->comment('0 = no , 1 = yes')->default('0');
            $table->enum('is_production', ['0', '1'])->comment('0 = Sandbox or testing environment, 1 = production environment')->default('1');
            $table->integer('created_by')->length(10)->unsigned()->comment('user_id who inserted this record');
            $table->integer('updated_by')->length(10)->unsigned()->comment('user_id who last modified this record');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_credentials');
    }
};