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
        Schema::table('subcription_plans', function (Blueprint $table) {
            $table->string('annual_sales_from')->after('plan_type')->nullable();
            $table->string('annual_sales_to')->after('annual_sales_from')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcription_plans', function (Blueprint $table) {
            //
        });
    }
};
