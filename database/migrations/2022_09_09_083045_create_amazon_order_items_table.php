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
        Schema::create('amazon_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('amazon_order_id')->comment('Id of order from amazon_orders');
            $table->bigInteger('amazon_product_id')->nullable();
            $table->char('ASIN', 10)->comment('The Amazon Standard Identification Number (ASIN) of the item');
            $table->char('sku', 40)->comment('The seller SKU of the item');
            $table->char('amazon_order_item_id', 14)->nullable()->comment('An Amazon-defined order item identifier');
            $table->string('title', 500)->nullable()->comment('The name of the item');
            $table->unsignedInteger('qty_ordered')->comment('The number of items in the order');
            $table->unsignedInteger('qty_shipped')->comment('The number of items shipped');
            $table->double('item_price', 12, 2)->unsigned()->nullable()->comment('Selling price of single quantity of the order item. This value is derived by dividing the "item_total_price" by "qty_ordered"');
            $table->double('item_total_price', 12, 2)->unsigned()->nullable()->comment('Selling price of total quantity of the order item. This value is retured by Amazon API for item_price but actaully it\'s item_total_price.');
            $table->char('item_price_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->enum('price_designation', ['0', '1'])->nullable()->comment('0 - if normal price, 1 - if it\'s business price. Indicates that the selling price is a special price that is available only for Amazon Business orders. Returned only for business orders');
            $table->double('shipping_price', 12, 2)->unsigned()->nullable()->comment('Shipping price of single quantity of the order item. This value is derived by dividing the "shipping_total_price" by "qty_ordered"');
            $table->double('shipping_total_price', 12, 2)->unsigned()->nullable()->comment('Shipping price of total quantity of the order item. This value is retured by Amazon API for shipping_price but actaully it\'s shipping_total_price.');
            $table->char('shipping_price_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->double('giftwrap_price', 12, 2)->unsigned()->nullable()->comment('Gift wrap price of single quantity of the order item. This value is derived by dividing the "giftwrap_total_price" by "qty_ordered"');
            $table->double('giftwrap_total_price', 12, 2)->unsigned()->nullable()->comment('Gift wrap price of total quantity of the order item. This value is retured by Amazon API for giftwrap_price but actaully it\'s giftwrap_total_price.');
            $table->char('giftwrap_price_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->string('gift_message_text', 500)->nullable()->comment('A gift message provided by the buyer');
            $table->string('gift_wrap_level', 500)->nullable()->comment('The gift wrap level specified by the buyer');
            $table->double('item_tax', 12, 2)->unsigned()->nullable()->comment('Item tax price of single quantity of the order item. This value is derived by dividing the "item_total_tax" by "qty_ordered"');
            $table->double('item_total_tax', 12, 2)->unsigned()->nullable()->comment('Item tax price of total quantity of the order item. This value is retured by Amazon API for item_tax but actaully it\'s item_total_tax.');
            $table->char('item_tax_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->double('shipping_tax', 12, 2)->unsigned()->nullable()->comment('Shipping tax price of single quantity of the order item. This value is derived by dividing the "shipping_total_tax" by "qty_ordered"');
            $table->double('shipping_total_tax', 12, 2)->unsigned()->nullable()->comment('Shipping tax price of total quantity of the order item. This value is retured by Amazon API for shipping_tax but actaully it\'s shipping_total_tax.');
            $table->char('shipping_tax_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->double('giftwrap_tax', 12, 2)->unsigned()->nullable()->comment('Gift wrap tax price of single quantity of the order item. This value is derived by dividing the "giftwrap_total_tax" by "qty_ordered"');
            $table->double('giftwrap_total_tax', 12, 2)->unsigned()->nullable()->comment('Gift wrap tax price of total quantity of the order item. This value is retured by Amazon API for giftwrap_tax but actaully it\'s giftwrap_total_tax.');
            $table->char('giftwrap_tax_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->double('shipping_discount', 12, 2)->unsigned()->nullable()->comment('Shipping discount price of single quantity of the order item. This value is derived by dividing the "shipping_total_discount" by "qty_ordered"');
            $table->double('shipping_total_discount', 12, 2)->unsigned()->nullable()->comment('Shipping discount price of total quantity of the order item. This value is retured by Amazon API for shipping_discount but actaully it\'s shipping_total_discount');
            $table->char('shipping_discount_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->double('promotion_discount', 12, 2)->unsigned()->nullable()->comment('Promotion discount price of single quantity of the order item. This value is derived by dividing the "promotion_total_discount" by "qty_ordered"');
            $table->double('promotion_total_discount', 12, 2)->unsigned()->nullable()->comment('Promotion discount price of total quantity of the order item. This value is retured by Amazon API for promotion_discount but actaully it\'s promotion_total_discount.');
            $table->char('promotion_discount_currency', 3)->nullable()->comment('Three-digit currency code');
            $table->string('promotion_id', 250)->nullable()->comment('A list of PromotionIds');
            $table->enum('condition_id', ['New', 'Used', 'Collectible', 'Refurbished', 'Preorder', 'Club'])->nullable()->comment('The condition of the item');
            $table->enum('condition_subtype_id', ['New', 'Mint', 'Very Good', 'Good', 'Acceptable', 'Poor', 'Club', 'OEM', 'Warranty', 'Refurbished Warranty', 'Refurbished', 'Open Box', 'Any', 'Other'])->nullable()->comment('The subcondition of the item');
            $table->string('condition_note', 250)->nullable()->comment('The condition of the item as described by the seller');
            $table->string('buyer_customized_info', 500)->nullable()->comment('Buyer information for custom orders from the Amazon Custom program. The location of a zip file containing Amazon Custom data');
            $table->enum('updated', ['0', '1'])->nullable()->default('0')->comment('0 if the order isn\'t updated in order_master table, 1 if the order is updated');
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
        Schema::dropIfExists('amazon_order_items');
    }
};
