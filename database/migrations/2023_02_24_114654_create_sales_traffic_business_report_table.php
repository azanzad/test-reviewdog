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
        Schema::create('sales_traffic_business_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_id')->nullable();
            $table->date('sales_date')->nullable();
            $table->char('currency_code', 40)->nullable();
            $table->double('ordered_product_sales_amount', 14, 2)->nullable();
            $table->double('ordered_product_sales_amount_b2b', 14, 2)->nullable();
            $table->double('units_ordered', 14, 2)->nullable();
            $table->double('units_ordered_b2b', 14, 2)->nullable();
            $table->double('total_order_items', 14, 2)->nullable();
            $table->double('total_order_items_b2b', 14, 2)->nullable();
            $table->timestamps();

            $table->index(['store_id', 'sales_date']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_traffic_business_reports');
    }
};
