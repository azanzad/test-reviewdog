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
        Schema::create('amazon_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->char('amazon_order_id', 19)->comment('An Amazon-defined order identifier, in 3-7-7 format.');
            $table->string('seller_order_id', 50)->nullable()->comment('A seller-defined order identifier');
            $table->unsignedInteger('store_id')->comment('Id of store from the store_master');
            $table->unsignedInteger('items_count')->default(0)->comment('Total number of items in the order. Only the count of items not their quantity');
            $table->dateTime('order_date')->nullable()->comment('date time of order based project timestamp');
            $table->dateTime('purchase_date')->nullable()->comment('The date when the order was created');
            $table->dateTime('last_updated_date')->nullable()->comment('The date when the order was created');
            $table->enum('order_status', ['PendingAvailability', 'Pending', 'Unshipped', 'PartiallyShipped', 'Shipped', 'InvoiceUnconfirmed', 'Canceled', 'Unfulfillable'])->nullable()->comment('The current order status');
            $table->enum('fulfillment_channel', ['AFN', 'MFN'])->nullable()->comment('How the order was fulfilled: by Amazon (AFN) or by the seller (MFN)');
            $table->enum('order_type', ['StandardOrder', 'Preorder'])->nullable()->comment('The type of the order. StandardOrder - An order that contains items for which you currently have inventory in stock, Preorder - An order that contains items with a release date that is in the future');
            $table->string('sales_channel', 20)->nullable()->comment('The sales channel of the first item in the order');
            $table->string('order_channel', 20)->nullable()->comment('The order channel of the first item in the order');
            $table->string('ship_service_level', 30)->nullable()->comment('The shipment service level of the order');
            $table->enum('shipping_service_level_category', ['Expedited', 'FreeEconomy', 'NextDay', 'SameDay', 'SecondDay', 'Scheduled', 'Standard'])->nullable()->comment('The shipment service level category of the order');
            $table->string('shipping_label_cba', 50)->nullable()->comment('A seller-customized shipment service level that is mapped to one of the four standard shipping settings supported by Checkout by Amazon (CBA). Note: CBA is available only to sellers in the United States (US), the United Kingdom (UK), and Germany (DE)');
            $table->string('shipping_address_name', 150)->nullable()->comment('The');
            $table->string('shipping_address_line1', 250)->nullable()->comment('Additional street address information, if required');
            $table->string('shipping_address_line2', 250)->nullable()->comment('Additional street address information, if required');
            $table->string('shipping_address_line3', 250)->nullable()->comment('The');
            $table->string('shipping_address_city', 50)->nullable()->comment('The city');
            $table->string('shipping_address_county', 50)->nullable()->comment('The county');
            $table->string('shipping_address_district', 50)->nullable()->comment('The district');
            $table->string('shipping_address_state', 50)->nullable()->comment('The state or region');
            $table->string('shipping_address_zipcode', 10)->nullable()->comment('The postal code');
            $table->char('shipping_address_country', 2)->nullable()->comment('The two-digit country code');
            $table->string('shipping_address_phone', 40)->nullable()->comment('The phone number. Optional. Not returned for Fulfillment by Amazon (FBA) orders');
            $table->unsignedInteger('items_shipped')->nullable()->comment('The number of items shipped');
            $table->unsignedInteger('items_unshipped')->nullable()->comment('The number of items unshipped');
            $table->double('order_total', 12, 2)->nullable()->comment('The total charge for the order');
            $table->char('order_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->enum('payment_method', ['COD', 'CVS', 'Other'])->nullable()->comment('The main payment method of the order');
            $table->string('buyer_name', 150)->nullable()->comment('The name of the buyer');
            $table->string('buyer_email', 150)->nullable()->comment('The anonymized e-mail address of the buyer');
            $table->dateTime('ship_date_earliest')->nullable()->comment('The start of the time period that you have committed to ship the order');
            $table->dateTime('ship_date_latest')->nullable()->comment('The end of the time period that you have committed to ship the order');
            $table->dateTime('delivery_date_earliest')->nullable()->comment('The start of the time period that you have commited to fulfill the order');
            $table->dateTime('delivery_date_latest')->nullable()->comment('The end of the time period that you have commited to fulfill the order');
            $table->string('buyer_po_number', 50)->nullable()->comment('The purchase order (PO) number entered by the buyer at checkout. Optional. Returned only for orders where the buyer entered a PO number at checkout');
            $table->enum('is_prime_order', ['0', '1'])->nullable()->comment('0 - Normal Order, 1 - Prime Order. Indicates that the order is a seller-fulfilled Amazon Prime order');
            $table->enum('is_business_order', ['0', '1'])->nullable()->comment('0 - Normal Order, 1 - Business Order. Indicates that the order is an Amazon Business order. An Amazon Business order is an order where the buyer is a Verified Business Buyer and the seller is an Amazon Business Seller');
            $table->enum('is_premium_order', ['0', '1'])->nullable()->comment('0 - Normal Order, 1 - Premium Order. Indicates that the order has a Premium Shipping Service Level Agreement');
            $table->enum('processed', ['0', '1', '2'])->nullable()->default('0')->comment('0 if the order isn\'t processed for getting items, 1 if the order is processed, 2 if the order is processed but there are updates & we need to update items');
            $table->enum('is_acknowledge', ['0', '1'])->nullable()->default('0')->comment('0 => Processed, 1 => Not Processed');
            $table->enum('is_settled', ['0', '1'])->nullable()->default('0')->comment('0 - Not Settled, 1 - Settled');
            $table->enum('updated', ['0', '1'])->nullable()->default('0')->comment('0 if the order isn\'t updated in order_master table, 1 if the order is updated');
            $table->enum('is_request_sent', ['0', '1', '2', '3'])->nullable()->default('0')->comment('0 - Pending, 1 - Successful, 2 - Fail,3-not eligible');
            $table->dateTime('request_sent_date')->nullable()->comment('The request send date');
            $table->timestamps();
            $table->timestamp('last_modified')->useCurrentOnUpdate()->useCurrent()->comment('Last modification time stamp of this record');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amazon_orders');
    }
};