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
        Schema::table('users', function (Blueprint $table) {
            $table->string('billing_day')->after('stripe_id')->nullable();
            $table->string('next_billing_date')->after('billing_day')->nullable();
            $table->string('currency')->after('next_billing_date')->nullable();
            $table->string('activated_date')->after('currency')->nullable();
            $table->string('is_plan_active')->after('activated_date')->nullable();
            $table->string('current_paid_status')->after('is_plan_active')->nullable();
            $table->string('cancelled_date')->after('current_paid_status')->nullable();
            $table->string('subscription_expired')->after('cancelled_date')->nullable();
            $table->string('cardid')->after('subscription_expired')->nullable();
            $table->string('card_token')->after('cardid')->nullable();
            $table->string('subscription_id')->after('card_token')->nullable();
            $table->string('subscription_item_id')->after('subscription_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};