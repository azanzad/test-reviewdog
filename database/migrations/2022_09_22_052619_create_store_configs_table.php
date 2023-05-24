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
        Schema::create('store_configs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->enum('store_type', ['Amazon US', 'Amazon CA', 'Amazon UK', 'Amazon ES', 'Amazon FR', 'Amazon DE', 'Amazon IT', 'Amazon JP', 'Amazon CN', 'Amazon IN', 'Amazon MX', 'Amazon AUS'])->comment('Type of the store');
            $table->string('store_url', 30)->comment('Main URL of the Marketplace');
            $table->string('seller_central_url', 150)->comment('marketplace seller central');
            $table->string('aws_endpoint', 150);
            $table->char('amazon_marketplace_id', 14)->nullable()->comment('MarketplaceId of respective Amazon Marketplace');
            $table->string('amazon_region', 10)->nullable();
            $table->string('amazon_aws_region', 20)->nullable()->comment('Aws Region we will use the the SQS service for this marketplace');
            $table->char('store_currency', 3)->comment('Each eBay site maps to a unique eBay global ID.');
            $table->string('store_timezone', 20)->comment('Default timezone of the store');
            $table->enum('store_marketplace', ['Amazon'])->comment('Main marketplace of the store');
            $table->string('store_country', 10)->comment('Store country code');
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
        Schema::dropIfExists('store_configs');
    }
};