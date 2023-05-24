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
        Schema::create('fetched_report_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->length(11)->unsigned();
            $table->tinyInteger('report_source')->length(2)->comment('1-AWS,2 - Bot')->default(1);
            $table->string('report_type', 250);
            $table->string('report_type_name', 250);
            $table->tinyInteger('report_frequency')->length(2)->comment('1-Hourly,2-Daily,3-Weekly,4-Monthly')->default(2);
            $table->tinyInteger('status')->length(2)->comment('0-Fail,1-Success')->default(1);
            $table->string('report_url', 250)->nullable();
            $table->string('file_name', 250)->nullable();
            $table->dateTime('cron_start')->nullable();
            $table->dateTime('cron_end')->nullable();
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
        Schema::dropIfExists('fetched_report_logs');
    }
};