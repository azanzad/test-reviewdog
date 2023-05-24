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
        Schema::table('customer_cards', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('last_number');
            $table->dropColumn('exp_month');
            $table->dropColumn('exp_year');
            $table->dropColumn('cvc');
            $table->dropColumn('brand');
            $table->string('stripe_token')->after('status')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_cards', function (Blueprint $table) {
            $table->dropColumn('stripe_token');
        });

    }
};