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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->unsignedBigInteger('companyid');
            $table->index('companyid');
            $table->foreign('companyid')->references('id')->on('users')->onDelete('cascade');
            $table->string('store_name', 255)->comment('Name of the marketplace store');
            $table->enum('store_marketplace', ['Amazon'])->comment('Main marketplace of the store');
            $table->enum('store_type', ['Amazon US', 'Amazon CA', 'Amazon UK', 'Amazon ES', 'Amazon FR', 'Amazon DE', 'Amazon IT', 'Amazon JP', 'Amazon CN', 'Amazon IN', 'Amazon MX', 'Amazon AUS'])->comment('Type of the store')->nullable();
            $table->integer('store_config_id')->length(11)->default(0);
            $table->enum('is_sqs_registered', ['1', '0'])->default('0');
            $table->string('currency_code', 10)->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1')->comment('1=active,2=in-active,3=deleted');
            $table->enum('aws_region', ['North America', 'Europe', 'Far East'])->default('North America')->comment('Main marketplace of the store');
            $table->tinyInteger('is_master_store')->default('0')->comment('is_master_store = user first store');
            $table->integer('max_quantity_post')->comment('max qty post')->default(0);
            $table->integer('created_by')->length(10)->unsigned()->comment('user_id who inserted this record');
            $table->integer('updated_by')->length(10)->unsigned()->comment('user_id who last modified this record');
            $table->softDeletes();
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
        Schema::dropIfExists('store');
    }
};