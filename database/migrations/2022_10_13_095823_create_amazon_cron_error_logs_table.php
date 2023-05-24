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
        Schema::create('amazon_cron_error_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->length(11)->comment('primary key of stores table')->unsigned()->nullable();
            $table->integer('batch_id')->length(11)->comment('Batch id which uniquely identifies one iteration')->unsigned()->nullable();
            $table->string('module', 50)->comment('Name of the module where error occured')->nullable();
            $table->string('submodule', 50)->comment('Name of the submodule where error occured')->nullable();
            $table->mediumText('error_content', 50)->comment('error description or content in serialize format')->nullable();
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
        Schema::dropIfExists('amazon_cron_error_logs');
    }
};