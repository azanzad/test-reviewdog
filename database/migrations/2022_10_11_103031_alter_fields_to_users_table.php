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
            $table->integer('billing_day')->after('stripe_id')->nullable()->change();
            $table->dateTime('next_billing_date')->after('billing_day')->nullable()->change();
            $table->date('activated_date')->after('currency')->nullable()->change();
            $table->integer('is_plan_active')->after('activated_date')->comment('1=active,2=cancelled')->nullable()->change();
            $table->integer('current_paid_status')->after('is_plan_active')->comment('1=paid,2=failed')->nullable()->change();
            $table->dateTime('cancelled_date')->after('current_paid_status')->nullable()->change();
            $table->date('subscription_expired')->after('cancelled_date')->nullable()->change();

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
