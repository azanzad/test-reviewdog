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
            $table->string('uuid')->after('id')->nullable();
            $table->string('brand')->after('cardholder_name')->nullable();
            $table->string('last_number')->after('is_primary')->nullable();

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
            $table->dropColumn('uuid');
            $table->dropColumn('brand');
            $table->dropColumn('last_number');

        });

    }
};