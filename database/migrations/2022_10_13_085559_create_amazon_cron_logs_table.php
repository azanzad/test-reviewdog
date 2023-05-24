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
        Schema::create('amazon_cron_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->length(11)->comment('primary key of stores table')->unsigned()->nullable();
            $table->string('amazon_feed_id', 200)->nullable();
            $table->string('cron_name', 100)->nullable();
            $table->string('cron_type', 100)->nullable();
            $table->string('cron_param', 100)->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
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
        Schema::dropIfExists('amazon_cron_logs');
    }
};